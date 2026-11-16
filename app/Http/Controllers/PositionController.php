<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Position\StorePositionRequest;
use App\Http\Requests\Position\UpdatePositionRequest;
use App\Models\Position;
use App\Services\PositionService;
use Illuminate\Http\Request;

/**
 * Position web controller (Phases 08-12). Thin: validate via Form Request,
 * authorise via Policy, delegate to PositionService, then respond.
 */
class PositionController extends Controller
{
    public function __construct(protected PositionService $service)
    {
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Position::class);

        $positions = $this->service->list($request->only(['search', 'status', 'branch_id', 'country_id', 'company_id', 'agent_id', 'current_status']), $request->user());

        $filterCountries = \App\Models\Country::query()->visibleTo($request->user())->orderBy('name')->get();
        $filterCompanies = \App\Models\Company::query()->visibleTo($request->user())->orderBy('name')->get();

        return view('positions.index', ['items' => $positions, 'filterCountries' => $filterCountries, 'filterCompanies' => $filterCompanies]);
    }

    public function create(Request $request)
    {
        $this->authorize('create', Position::class);

        $countries = \App\Models\Country::query()->visibleTo($request->user())->where('status', 'active')->orderBy('name')->get();
        $companies = \App\Models\Company::query()->visibleTo($request->user())->where('status', 'active')->orderBy('name')->get();

        return view('positions.create', ['countries' => $countries, 'companies' => $companies]);
    }

    public function store(StorePositionRequest $request)
    {
        $position = $this->service->create($request->validated(), $request->user());

        return redirect()->route('positions.show', $position)
            ->with('success', 'Position created successfully.');
    }

    public function show(Position $position)
    {
        $this->authorize('view', $position);

        return view('positions.show', ['position' => $position]);
    }

    public function edit(Request $request, Position $position)
    {
        $this->authorize('update', $position);

        $countries = \App\Models\Country::query()->visibleTo($request->user())->where('status', 'active')->orderBy('name')->get();
        $companies = \App\Models\Company::query()->visibleTo($request->user())->where('status', 'active')->orderBy('name')->get();

        return view('positions.edit', ['position' => $position, 'countries' => $countries, 'companies' => $companies]);
    }

    public function update(UpdatePositionRequest $request, Position $position)
    {
        $this->service->update($position, $request->validated(), $request->user());

        return redirect()->route('positions.show', $position)
            ->with('success', 'Position updated successfully.');
    }

    public function destroy(Position $position)
    {
        $this->authorize('delete', $position);

        $this->service->delete($position, request()->user());

        return redirect()->route('positions.index')
            ->with('success', 'Position removed successfully.');
    }
}
