<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Position\StorePositionRequest;
use App\Http\Requests\Position\UpdatePositionRequest;
use App\Http\Resources\PositionResource;
use App\Models\Position;
use App\Services\PositionService;
use Illuminate\Http\Request;
use Illuminate\Http\Request as HttpRequest;

/**
 * Position API controller (Phases 08-12, /api/v1).
 * Same services and policies as the web controller; never a raw model.
 */
class PositionController extends Controller
{
    public function __construct(protected PositionService $service)
    {
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Position::class);

        $page = $this->service->list($request->only(['search', 'status', 'branch_id', 'country_id', 'company_id', 'agent_id', 'current_status']), $request->user());

        return response()->json([
            'data' => PositionResource::collection($page),
            'meta' => [
                'current_page' => $page->currentPage(),
                'per_page' => $page->perPage(),
                'total' => $page->total(),
                'last_page' => $page->lastPage(),
            ],
            'message' => null,
        ]);
    }

    public function store(StorePositionRequest $request)
    {
        $position = $this->service->create($request->validated(), $request->user());

        return response()->json([
            'data' => new PositionResource($position),
            'message' => 'Position created successfully.',
        ], 201);
    }

    public function show(Position $position)
    {
        $this->authorize('view', $position);

        return response()->json([
            'data' => new PositionResource($position),
            'message' => null,
        ]);
    }

    public function update(UpdatePositionRequest $request, Position $position)
    {
        $position = $this->service->update($position, $request->validated(), $request->user());

        return response()->json([
            'data' => new PositionResource($position),
            'message' => 'Position updated successfully.',
        ]);
    }

    public function destroy(Position $position)
    {
        $this->authorize('delete', $position);

        $this->service->delete($position, request()->user());

        return response()->json([
            'data' => null,
            'message' => 'Position removed successfully.',
        ]);
    }
}
