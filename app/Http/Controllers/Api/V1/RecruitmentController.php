<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Recruitment\StoreRecruitmentRequest;
use App\Http\Requests\Recruitment\UpdateRecruitmentRequest;
use App\Http\Resources\RecruitmentResource;
use App\Models\Recruitment;
use App\Services\RecruitmentService;
use App\Services\WorkflowService;
use Illuminate\Http\Request;
use Illuminate\Http\Request as HttpRequest;

/**
 * Recruitment API controller (Phases 08-12, /api/v1).
 * Same services and policies as the web controller; never a raw model.
 */
class RecruitmentController extends Controller
{
    public function __construct(protected RecruitmentService $service)
    {
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Recruitment::class);

        $page = $this->service->list($request->only(['search', 'status', 'branch_id', 'country_id', 'company_id', 'agent_id', 'current_status']), $request->user());

        return response()->json([
            'data' => RecruitmentResource::collection($page),
            'meta' => [
                'current_page' => $page->currentPage(),
                'per_page' => $page->perPage(),
                'total' => $page->total(),
                'last_page' => $page->lastPage(),
            ],
            'message' => null,
        ]);
    }

    public function store(StoreRecruitmentRequest $request)
    {
        $recruitment = $this->service->create($request->validated(), $request->user());

        return response()->json([
            'data' => new RecruitmentResource($recruitment),
            'message' => 'Recruitment created successfully.',
        ], 201);
    }

    public function show(Recruitment $recruitment)
    {
        $this->authorize('view', $recruitment);

        return response()->json([
            'data' => new RecruitmentResource($recruitment),
            'message' => null,
        ]);
    }

    public function update(UpdateRecruitmentRequest $request, Recruitment $recruitment)
    {
        $recruitment = $this->service->update($recruitment, $request->validated(), $request->user());

        return response()->json([
            'data' => new RecruitmentResource($recruitment),
            'message' => 'Recruitment updated successfully.',
        ]);
    }

    public function destroy(Recruitment $recruitment)
    {
        $this->authorize('delete', $recruitment);

        $this->service->delete($recruitment, request()->user());

        return response()->json([
            'data' => null,
            'message' => 'Recruitment removed successfully.',
        ]);
    }

    public function cancel(HttpRequest $request, Recruitment $recruitment)
    {
        $this->authorize('cancel', $recruitment);

        $validated = $request->validate(['remarks' => ['nullable', 'string']]);

        $recruitment = $this->service->cancel($recruitment, $validated['remarks'] ?? null, $request->user());

        return response()->json([
            'data' => new RecruitmentResource($recruitment),
            'message' => 'Registration cancelled. The record is kept as history.',
        ]);
    }

    /**
     * Move a recruitment to another workflow stage. The transition itself
     * belongs to the shared WorkflowService (Group A); this endpoint only
     * validates, authorises and delegates. It is registered by the
     * aggregate routes/api-modules/pipeline.php, so it must exist even
     * though stage transitions are exercised by Group C's pipeline work.
     */
    public function transition(HttpRequest $request, Recruitment $recruitment)
    {
        $this->authorize('update', $recruitment);

        $validated = $request->validate([
            'stage' => ['required', 'string', 'max:100'],
            'status' => ['required', 'string', 'max:100'],
            'remarks' => ['nullable', 'string'],
        ]);

        if (! class_exists(WorkflowService::class)) {
            return response()->json([
                'data' => null,
                'message' => 'The workflow engine (WorkflowService) has not been delivered yet, so this transition cannot run.',
            ], 422);
        }

        app(WorkflowService::class)->transition(
            $recruitment,
            $validated['stage'],
            $validated['status'],
            $validated['remarks'] ?? null,
            $request->user()
        );

        return response()->json([
            'data' => new RecruitmentResource($recruitment->fresh()),
            'message' => 'Recruitment moved to '.$validated['stage'].' / '.$validated['status'].'.',
        ]);
    }
}
