<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\PassportHolder\StorePassportHolderRequest;
use App\Http\Requests\PassportHolder\UpdatePassportHolderRequest;
use App\Models\PassportHolder;
use App\Services\PassportHolderService;
use Illuminate\Http\Request;
use App\Models\PassportHolderDocument;
use Illuminate\Support\Facades\Storage;

/**
 * Passport Holder web controller (Phases 08-12). Thin: validate via Form Request,
 * authorise via Policy, delegate to PassportHolderService, then respond.
 */
class PassportHolderController extends Controller
{
    public function __construct(protected PassportHolderService $service)
    {
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', PassportHolder::class);

        $passport_holders = $this->service->list($request->only(['search', 'status', 'branch_id', 'country_id', 'company_id', 'agent_id', 'current_status']), $request->user());

        $filterBranches = \App\Models\Branch::query()->visibleTo($request->user())->orderBy('name')->get();
        $filterCountries = \App\Models\Country::query()->visibleTo($request->user())->orderBy('name')->get();
        $filterAgents = \App\Models\Agent::query()->visibleTo($request->user())->orderBy('name')->get();

        return view('passport-holders.index', ['items' => $passport_holders, 'filterBranches' => $filterBranches, 'filterCountries' => $filterCountries, 'filterAgents' => $filterAgents]);
    }

    public function create(Request $request)
    {
        $this->authorize('create', PassportHolder::class);

        $agents = \App\Models\Agent::query()->visibleTo($request->user())->where('status', 'active')->orderBy('name')->get();
        $branches = \App\Models\Branch::query()->visibleTo($request->user())->where('status', 'active')->orderBy('name')->get();
        $countries = \App\Models\Country::query()->visibleTo($request->user())->where('status', 'active')->orderBy('name')->get();
        $companies = \App\Models\Company::query()->visibleTo($request->user())->where('status', 'active')->orderBy('name')->get();
        $positions = \App\Models\Position::query()->visibleTo($request->user())->where('status', 'active')->orderBy('title')->get();

        return view('passport-holders.create', ['agents' => $agents, 'branches' => $branches, 'countries' => $countries, 'companies' => $companies, 'positions' => $positions]);
    }

    public function store(StorePassportHolderRequest $request)
    {
        $passport_holder = $this->service->create($request->validated(), $request->user());

        return redirect()->route('passport-holders.show', $passport_holder)
            ->with('success', 'Passport Holder created successfully.');
    }

    public function show(PassportHolder $passport_holder)
    {
        $this->authorize('view', $passport_holder);

        return view('passport-holders.show', ['passport_holder' => $passport_holder]);
    }

    public function edit(Request $request, PassportHolder $passport_holder)
    {
        $this->authorize('update', $passport_holder);

        $agents = \App\Models\Agent::query()->visibleTo($request->user())->where('status', 'active')->orderBy('name')->get();
        $branches = \App\Models\Branch::query()->visibleTo($request->user())->where('status', 'active')->orderBy('name')->get();
        $countries = \App\Models\Country::query()->visibleTo($request->user())->where('status', 'active')->orderBy('name')->get();
        $companies = \App\Models\Company::query()->visibleTo($request->user())->where('status', 'active')->orderBy('name')->get();
        $positions = \App\Models\Position::query()->visibleTo($request->user())->where('status', 'active')->orderBy('title')->get();

        return view('passport-holders.edit', ['passport_holder' => $passport_holder, 'agents' => $agents, 'branches' => $branches, 'countries' => $countries, 'companies' => $companies, 'positions' => $positions]);
    }

    public function update(UpdatePassportHolderRequest $request, PassportHolder $passport_holder)
    {
        $this->service->update($passport_holder, $request->validated(), $request->user());

        return redirect()->route('passport-holders.show', $passport_holder)
            ->with('success', 'Passport Holder updated successfully.');
    }

    public function destroy(PassportHolder $passport_holder)
    {
        $this->authorize('delete', $passport_holder);

        $this->service->delete($passport_holder, request()->user());

        return redirect()->route('passport-holders.index')
            ->with('success', 'Passport Holder removed successfully.');
    }

    /**
     * Authorised download of a passport holder document. The file lives
     * on the private disk; there is no public URL for it.
     */
    public function downloadDocument(PassportHolder $passport_holder, PassportHolderDocument $document)
    {
        $this->authorize('downloadDocument', $passport_holder);

        if ((int) $document->passport_holder_id !== (int) $passport_holder->id) {
            abort(404);
        }

        if (! Storage::disk('private')->exists($document->file_path)) {
            abort(404, 'The requested file is no longer available.');
        }

        return Storage::disk('private')->download(
            $document->file_path,
            $document->original_name ?? basename($document->file_path)
        );
    }
}
