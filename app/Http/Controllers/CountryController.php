<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Country\StoreCountryRequest;
use App\Http\Requests\Country\UpdateCountryRequest;
use App\Models\Country;
use App\Services\CountryService;
use Illuminate\Http\Request;

/**
 * Country web controller (Phases 08-12). Thin: validate via Form Request,
 * authorise via Policy, delegate to CountryService, then respond.
 */
class CountryController extends Controller
{
    public function __construct(protected CountryService $service)
    {
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Country::class);

        $countries = $this->service->list($request->only(['search', 'status', 'branch_id', 'country_id', 'company_id', 'agent_id', 'current_status']), $request->user());

        return view('countries.index', ['items' => $countries]);
    }

    public function create(Request $request)
    {
        $this->authorize('create', Country::class);

        return view('countries.create');
    }

    public function store(StoreCountryRequest $request)
    {
        $country = $this->service->create($request->validated(), $request->user());

        return redirect()->route('countries.show', $country)
            ->with('success', 'Country created successfully.');
    }

    public function show(Country $country)
    {
        $this->authorize('view', $country);

        return view('countries.show', ['country' => $country]);
    }

    public function edit(Request $request, Country $country)
    {
        $this->authorize('update', $country);

        return view('countries.edit', ['country' => $country]);
    }

    public function update(UpdateCountryRequest $request, Country $country)
    {
        $this->service->update($country, $request->validated(), $request->user());

        return redirect()->route('countries.show', $country)
            ->with('success', 'Country updated successfully.');
    }

    public function destroy(Country $country)
    {
        $this->authorize('delete', $country);

        $this->service->delete($country, request()->user());

        return redirect()->route('countries.index')
            ->with('success', 'Country removed successfully.');
    }
}
