<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Recruitment\StoreRecruitmentRequest;
use App\Http\Requests\Recruitment\UpdateRecruitmentRequest;
use App\Models\Recruitment;
use App\Services\RecruitmentService;
use Illuminate\Http\Request;
use App\Models\RecruitmentDocument;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Storage;

/**
 * Recruitment web controller (Phases 08-12). Thin: validate via Form Request,
 * authorise via Policy, delegate to RecruitmentService, then respond.
 */
class RecruitmentController extends Controller
{
    public function __construct(protected RecruitmentService $service)
    {
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Recruitment::class);

        $recruitments = $this->service->list($request->only(['search', 'status', 'branch_id', 'country_id', 'company_id', 'agent_id', 'current_status', 'current_stage', 'active']), $request->user());

        $filterBranches = \App\Models\Branch::query()->visibleTo($request->user())->orderBy('name')->get();
        $filterCountries = \App\Models\Country::query()->visibleTo($request->user())->orderBy('name')->get();
        $filterAgents = \App\Models\Agent::query()->visibleTo($request->user())->orderBy('name')->get();

        return view('recruitments.index', ['items' => $recruitments, 'filterBranches' => $filterBranches, 'filterCountries' => $filterCountries, 'filterAgents' => $filterAgents]);
    }

    /**
     * Read-only pipeline board. Shows the current stage for every visible
     * registration and the 15-stage progress indicator. No stage can be
     * changed from this screen — transitions stay in WorkflowService.
     */
    public function tracking(Request $request)
    {
        $this->authorize('viewAny', Recruitment::class);

        $items = Recruitment::query()
            ->visibleTo($request->user())
            ->with(['passportHolder', 'agent', 'country', 'company', 'position'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $term = '%'.$request->string('search').'%';
                $query->where('tracking_number', 'like', $term);
            })
            ->when($request->filled('current_stage'), fn ($query) => $query->where('current_stage', $request->string('current_stage')))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('recruitments.tracking', [
            'items' => $items,
            'stages' => \App\Services\WorkflowService::STAGES,
        ]);
    }

    public function create(Request $request)
    {
        $this->authorize('create', Recruitment::class);

        $passportHolders = \App\Models\PassportHolder::query()->visibleTo($request->user())->where('status', 'active')->orderBy('full_name')->get();
        $agents = \App\Models\Agent::query()->visibleTo($request->user())->where('status', 'active')->orderBy('name')->get();
        $branches = \App\Models\Branch::query()->visibleTo($request->user())->where('status', 'active')->orderBy('name')->get();
        $countries = \App\Models\Country::query()->visibleTo($request->user())->where('status', 'active')->orderBy('name')->get();
        $companies = \App\Models\Company::query()->visibleTo($request->user())->where('status', 'active')->orderBy('name')->get();
        $positions = \App\Models\Position::query()->visibleTo($request->user())->where('status', 'active')->orderBy('title')->get();

        return view('recruitments.create', ['passportHolders' => $passportHolders, 'agents' => $agents, 'branches' => $branches, 'countries' => $countries, 'companies' => $companies, 'positions' => $positions]);
    }

    public function store(StoreRecruitmentRequest $request)
    {
        $recruitment = $this->service->create($request->validated(), $request->user());

        return redirect()->route('recruitments.show', $recruitment)
            ->with('success', 'Recruitment created successfully.');
    }

    public function show(Recruitment $recruitment)
    {
        $this->authorize('view', $recruitment);

        return view('recruitments.show', ['recruitment' => $recruitment]);
    }

    public function edit(Request $request, Recruitment $recruitment)
    {
        $this->authorize('update', $recruitment);

        $passportHolders = \App\Models\PassportHolder::query()->visibleTo($request->user())->where('status', 'active')->orderBy('full_name')->get();
        $agents = \App\Models\Agent::query()->visibleTo($request->user())->where('status', 'active')->orderBy('name')->get();
        $branches = \App\Models\Branch::query()->visibleTo($request->user())->where('status', 'active')->orderBy('name')->get();
        $countries = \App\Models\Country::query()->visibleTo($request->user())->where('status', 'active')->orderBy('name')->get();
        $companies = \App\Models\Company::query()->visibleTo($request->user())->where('status', 'active')->orderBy('name')->get();
        $positions = \App\Models\Position::query()->visibleTo($request->user())->where('status', 'active')->orderBy('title')->get();

        return view('recruitments.edit', ['recruitment' => $recruitment, 'passportHolders' => $passportHolders, 'agents' => $agents, 'branches' => $branches, 'countries' => $countries, 'companies' => $companies, 'positions' => $positions]);
    }

    public function update(UpdateRecruitmentRequest $request, Recruitment $recruitment)
    {
        $this->service->update($recruitment, $request->validated(), $request->user());

        return redirect()->route('recruitments.show', $recruitment)
            ->with('success', 'Recruitment updated successfully.');
    }

    public function destroy(Recruitment $recruitment)
    {
        $this->authorize('delete', $recruitment);

        $this->service->delete($recruitment, request()->user());

        return redirect()->route('recruitments.index')
            ->with('success', 'Recruitment removed successfully.');
    }

    /**
     * Authorised download of a recruitment document. The file lives on
     * the private disk; there is no public URL for it.
     */
    public function downloadDocument(Recruitment $recruitment, RecruitmentDocument $document)
    {
        $this->authorize('downloadDocument', $recruitment);

        if ((int) $document->recruitment_id !== (int) $recruitment->id) {
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

    /**
     * Cancel a registration. The record is kept as history; only its
     * status changes to cancelled.
     */
    public function cancel(HttpRequest $request, Recruitment $recruitment)
    {
        $this->authorize('cancel', $recruitment);

        $validated = $request->validate(['remarks' => ['nullable', 'string']]);

        $this->service->cancel($recruitment, $validated['remarks'] ?? null, $request->user());

        return redirect()->route('recruitments.show', $recruitment)
            ->with('success', 'Registration cancelled. The record is kept as history.');
    }
}
