<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Agent\StoreAgentRequest;
use App\Http\Requests\Agent\UpdateAgentRequest;
use App\Models\Agent;
use App\Services\AgentService;
use Illuminate\Http\Request;
use App\Models\Country;
use Illuminate\Http\Request as HttpRequest;

/**
 * Agent web controller (Phases 08-12). Thin: validate via Form Request,
 * authorise via Policy, delegate to AgentService, then respond.
 */
class AgentController extends Controller
{
    public function __construct(protected AgentService $service)
    {
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Agent::class);

        $agents = $this->service->list($request->only(['search', 'status', 'branch_id', 'country_id', 'company_id', 'agent_id', 'current_status']), $request->user());

        $filterBranches = \App\Models\Branch::query()->visibleTo($request->user())->orderBy('name')->get();

        return view('agents.index', ['items' => $agents, 'filterBranches' => $filterBranches]);
    }

    public function create(Request $request)
    {
        $this->authorize('create', Agent::class);

        $branches = \App\Models\Branch::query()->visibleTo($request->user())->where('status', 'active')->orderBy('name')->get();
        $countries = \App\Models\Country::query()->visibleTo($request->user())->where('status', 'active')->orderBy('name')->get();
        $users = \App\Models\User::query()->visibleTo($request->user())->orderBy('name')->get();

        return view('agents.create', ['branches' => $branches, 'countries' => $countries, 'users' => $users]);
    }

    public function store(StoreAgentRequest $request)
    {
        $agent = $this->service->create($request->validated(), $request->user());

        return redirect()->route('agents.show', $agent)
            ->with('success', 'Agent created successfully.');
    }

    public function show(Agent $agent)
    {
        $this->authorize('view', $agent);

        return view('agents.show', ['agent' => $agent]);
    }

    public function edit(Request $request, Agent $agent)
    {
        $this->authorize('update', $agent);

        $branches = \App\Models\Branch::query()->visibleTo($request->user())->where('status', 'active')->orderBy('name')->get();
        $countries = \App\Models\Country::query()->visibleTo($request->user())->where('status', 'active')->orderBy('name')->get();
        $users = \App\Models\User::query()->visibleTo($request->user())->orderBy('name')->get();

        return view('agents.edit', ['agent' => $agent, 'branches' => $branches, 'countries' => $countries, 'users' => $users]);
    }

    public function update(UpdateAgentRequest $request, Agent $agent)
    {
        $this->service->update($agent, $request->validated(), $request->user());

        return redirect()->route('agents.show', $agent)
            ->with('success', 'Agent updated successfully.');
    }

    public function destroy(Agent $agent)
    {
        $this->authorize('delete', $agent);

        $this->service->delete($agent, request()->user());

        return redirect()->route('agents.index')
            ->with('success', 'Agent removed successfully.');
    }

    /**
     * Update the agent's authorised countries (agent_country pivot).
     */
    public function syncCountries(HttpRequest $request, Agent $agent)
    {
        $this->authorize('manageCountries', $agent);

        $validated = $request->validate([
            'country_ids' => ['nullable', 'array'],
            'country_ids.*' => ['integer', 'exists:countries,id'],
        ]);

        $this->service->syncCountries($agent, $validated['country_ids'] ?? [], $request->user());

        return redirect()->route('agents.show', $agent)
            ->with('success', 'Authorised countries updated.');
    }
}
