<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\PassportHolder\StorePassportHolderRequest;
use App\Http\Requests\PassportHolder\UpdatePassportHolderRequest;
use App\Http\Resources\PassportHolderResource;
use App\Models\PassportHolder;
use App\Services\PassportHolderService;
use Illuminate\Http\Request;
use Illuminate\Http\Request as HttpRequest;

/**
 * Passport Holder API controller (Phases 08-12, /api/v1).
 * Same services and policies as the web controller; never a raw model.
 */
class PassportHolderController extends Controller
{
    public function __construct(protected PassportHolderService $service)
    {
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', PassportHolder::class);

        $page = $this->service->list($request->only(['search', 'status', 'branch_id', 'country_id', 'company_id', 'agent_id', 'current_status']), $request->user());

        return response()->json([
            'data' => PassportHolderResource::collection($page),
            'meta' => [
                'current_page' => $page->currentPage(),
                'per_page' => $page->perPage(),
                'total' => $page->total(),
                'last_page' => $page->lastPage(),
            ],
            'message' => null,
        ]);
    }

    public function store(StorePassportHolderRequest $request)
    {
        $passport_holder = $this->service->create($request->validated(), $request->user());

        return response()->json([
            'data' => new PassportHolderResource($passport_holder),
            'message' => 'Passport Holder created successfully.',
        ], 201);
    }

    public function show(PassportHolder $passport_holder)
    {
        $this->authorize('view', $passport_holder);

        return response()->json([
            'data' => new PassportHolderResource($passport_holder),
            'message' => null,
        ]);
    }

    public function update(UpdatePassportHolderRequest $request, PassportHolder $passport_holder)
    {
        $passport_holder = $this->service->update($passport_holder, $request->validated(), $request->user());

        return response()->json([
            'data' => new PassportHolderResource($passport_holder),
            'message' => 'Passport Holder updated successfully.',
        ]);
    }

    public function destroy(PassportHolder $passport_holder)
    {
        $this->authorize('delete', $passport_holder);

        $this->service->delete($passport_holder, request()->user());

        return response()->json([
            'data' => null,
            'message' => 'Passport Holder removed successfully.',
        ]);
    }
}
