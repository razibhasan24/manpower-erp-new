<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Company\StoreCompanyRequest;
use App\Http\Requests\Company\UpdateCompanyRequest;
use App\Models\Company;
use App\Services\CompanyService;
use Illuminate\Http\Request;

/**
 * Company web controller (Phases 08-12). Thin: validate via Form Request,
 * authorise via Policy, delegate to CompanyService, then respond.
 */
class CompanyController extends Controller
{
    public function __construct(protected CompanyService $service)
    {
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Company::class);

        $companies = $this->service->list($request->only(['search', 'status', 'branch_id', 'country_id', 'company_id', 'agent_id', 'current_status']), $request->user());

        $filterCountries = \App\Models\Country::query()->visibleTo($request->user())->orderBy('name')->get();
        $filterCompanies = \App\Models\Company::query()->visibleTo($request->user())->orderBy('name')->get();

        return view('companies.index', ['items' => $companies, 'filterCountries' => $filterCountries, 'filterCompanies' => $filterCompanies]);
    }

    public function create(Request $request)
    {
        $this->authorize('create', Company::class);

        $countries = \App\Models\Country::query()->visibleTo($request->user())->where('status', 'active')->orderBy('name')->get();
        $companies = \App\Models\Company::query()->visibleTo($request->user())->where('status', 'active')->orderBy('name')->get();

        return view('companies.create', ['countries' => $countries, 'companies' => $companies]);
    }

    public function store(StoreCompanyRequest $request)
    {
        $company = $this->service->create($request->validated(), $request->user());

        return redirect()->route('companies.show', $company)
            ->with('success', 'Company created successfully.');
    }

    public function show(Company $company)
    {
        $this->authorize('view', $company);

        return view('companies.show', ['company' => $company]);
    }

    public function edit(Request $request, Company $company)
    {
        $this->authorize('update', $company);

        $countries = \App\Models\Country::query()->visibleTo($request->user())->where('status', 'active')->orderBy('name')->get();
        $companies = \App\Models\Company::query()->visibleTo($request->user())->where('status', 'active')->orderBy('name')->get();

        return view('companies.edit', ['company' => $company, 'countries' => $countries, 'companies' => $companies]);
    }

    public function update(UpdateCompanyRequest $request, Company $company)
    {
        $this->service->update($company, $request->validated(), $request->user());

        return redirect()->route('companies.show', $company)
            ->with('success', 'Company updated successfully.');
    }

    public function destroy(Company $company)
    {
        $this->authorize('delete', $company);

        $this->service->delete($company, request()->user());

        return redirect()->route('companies.index')
            ->with('success', 'Company removed successfully.');
    }
}
