<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Branch\StoreBranchRequest;
use App\Http\Requests\Branch\UpdateBranchRequest;
use App\Http\Resources\BranchResource;
use App\Models\Branch;
use App\Services\BranchService;
use Illuminate\Http\Request;
use Illuminate\Http\Request as HttpRequest;

/**
 * Branch API controller (Phases 08-12, /api/v1).
 * Same services and policies as the web controller; never a raw model.
 */
class BranchController extends Controller
{
    public function __construct(protected BranchService $service)
    {
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Branch::class);

        $page = $this->service->list($request->only(['search', 'status', 'branch_id', 'country_id', 'company_id', 'agent_id', 'current_status']), $request->user());

        return response()->json([
            'data' => BranchResource::collection($page),
            'meta' => [
                'current_page' => $page->currentPage(),
                'per_page' => $page->perPage(),
                'total' => $page->total(),
                'last_page' => $page->lastPage(),
            ],
            'message' => null,
        ]);
    }

    public function store(StoreBranchRequest $request)
    {
        $branch = $this->service->create($request->validated(), $request->user());

        return response()->json([
            'data' => new BranchResource($branch),
            'message' => 'Branch created successfully.',
        ], 201);
    }

    public function show(Branch $branch)
    {
        $this->authorize('view', $branch);

        return response()->json([
            'data' => new BranchResource($branch),
            'message' => null,
        ]);
    }

    public function update(UpdateBranchRequest $request, Branch $branch)
    {
        $branch = $this->service->update($branch, $request->validated(), $request->user());

        return response()->json([
            'data' => new BranchResource($branch),
            'message' => 'Branch updated successfully.',
        ]);
    }

    public function destroy(Branch $branch)
    {
        $this->authorize('delete', $branch);

        $this->service->delete($branch, request()->user());

        return response()->json([
            'data' => null,
            'message' => 'Branch removed successfully.',
        ]);
    }
}
