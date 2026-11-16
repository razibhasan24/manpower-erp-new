<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Agent\StoreAgentRequest;
use App\Http\Requests\Agent\UpdateAgentRequest;
use App\Http\Resources\AgentResource;
use App\Models\Agent;
use App\Services\AgentService;
use Illuminate\Http\Request;
use Illuminate\Http\Request as HttpRequest;

/**
 * Agent API controller (Phases 08-12, /api/v1).
 * Same services and policies as the web controller; never a raw model.
 */
class AgentController extends Controller
{
    public function __construct(protected AgentService $service)
    {
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Agent::class);

        $page = $this->service->list($request->only(['search', 'status', 'branch_id', 'country_id', 'company_id', 'agent_id', 'current_status']), $request->user());

        return response()->json([
            'data' => AgentResource::collection($page),
            'meta' => [
                'current_page' => $page->currentPage(),
                'per_page' => $page->perPage(),
                'total' => $page->total(),
                'last_page' => $page->lastPage(),
            ],
            'message' => null,
        ]);
    }

    public function store(StoreAgentRequest $request)
    {
        $agent = $this->service->create($request->validated(), $request->user());

        return response()->json([
            'data' => new AgentResource($agent),
            'message' => 'Agent created successfully.',
        ], 201);
    }

    public function show(Agent $agent)
    {
        $this->authorize('view', $agent);

        return response()->json([
            'data' => new AgentResource($agent),
            'message' => null,
        ]);
    }

    public function update(UpdateAgentRequest $request, Agent $agent)
    {
        $agent = $this->service->update($agent, $request->validated(), $request->user());

        return response()->json([
            'data' => new AgentResource($agent),
            'message' => 'Agent updated successfully.',
        ]);
    }

    public function destroy(Agent $agent)
    {
        $this->authorize('delete', $agent);

        $this->service->delete($agent, request()->user());

        return response()->json([
            'data' => null,
            'message' => 'Agent removed successfully.',
        ]);
    }
}
