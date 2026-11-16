<?php

namespace App\Http\Controllers;

use App\Http\Requests\ManpowerApproval\ChangeManpowerApprovalStatusRequest;
use App\Http\Requests\ManpowerApproval\StoreManpowerApprovalRequest;
use App\Http\Requests\ManpowerApproval\UpdateManpowerApprovalRequest;
use App\Models\ManpowerApproval;
use App\Services\ManpowerApprovalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ManpowerApprovalController extends Controller
{
    public function __construct(protected ManpowerApprovalService $service) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', ManpowerApproval::class);
        $records = $this->service->scopedQuery($request->user())
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->when($request->filled('search'), fn ($q) => $q->whereHas('recruitment', fn ($r) => $r->where('tracking_number', 'like', '%' . $request->string('search') . '%')))
            ->paginate(15)->withQueryString();
        return view('manpower-approvals.index', ['records' => $records, 'statuses' => ManpowerApprovalService::STATUSES]);
    }

    public function create(Request $request)
    {
        $this->authorize('create', ManpowerApproval::class);
        $recruitments = \App\Models\Recruitment::visibleTo($request->user())->orderByDesc('id')->limit(200)->get();
        return view('manpower-approvals.create', compact('recruitments'));
    }

    public function store(StoreManpowerApprovalRequest $request)
    {
        $record = $this->service->create($request->validated(), $request->user());
        if ($request->wantsJson()) {
            return response()->json(new \App\Http\Resources\ManpowerApprovalResource($record), 201);
        }
        return redirect()->route('manpower-approvals.show', $record)->with('success', __('Record created.'));
    }

    public function show(ManpowerApproval $manpowerApproval)
    {
        $this->authorize('view', $manpowerApproval);
        $manpowerApproval->load(['recruitment.passportHolder', 'recruitment.stageHistories']);
        return view('manpower-approvals.show', ['record' => $manpowerApproval, 'recruitment' => $manpowerApproval->recruitment, 'statuses' => ManpowerApprovalService::STATUSES]);
    }

    public function edit(ManpowerApproval $manpowerApproval)
    {
        $this->authorize('update', $manpowerApproval);
        $recruitments = \App\Models\Recruitment::visibleTo(auth()->user())->orderByDesc('id')->limit(200)->get();
        return view('manpower-approvals.edit', ['record' => $manpowerApproval, 'recruitments' => $recruitments]);
    }

    public function update(UpdateManpowerApprovalRequest $request, ManpowerApproval $manpowerApproval)
    {
        $record = $this->service->update($manpowerApproval, $request->validated(), $request->user());
        if ($request->wantsJson()) {
            return response()->json(new \App\Http\Resources\ManpowerApprovalResource($record));
        }
        return redirect()->route('manpower-approvals.show', $manpowerApproval)->with('success', __('Record updated.'));
    }

    public function changeStatus(ChangeManpowerApprovalStatusRequest $request, ManpowerApproval $manpowerApproval)
    {
        $this->authorize('changeStatus', $manpowerApproval);
        $record = $this->service->changeStatus($manpowerApproval, $request->validated()['status'], $request->validated()['remarks'] ?? null, $request->user());
        if ($request->wantsJson()) {
            return response()->json(new \App\Http\Resources\ManpowerApprovalResource($record));
        }
        return redirect()->route('manpower-approvals.show', $manpowerApproval)->with('success', __('Status updated.'));
    }

    public function download(Request $request, ManpowerApproval $manpowerApproval, string $field)
    {
        $this->authorize('download', $manpowerApproval);
        $path = $this->service->downloadPath($manpowerApproval, $field);
        abort_if($path === null, 404);
        return Storage::disk('private')->download($path);
    }

    /** Cancel/archive only — never a hard delete of a pipeline record. */
    public function destroy(Request $request, ManpowerApproval $manpowerApproval)
    {
        $this->authorize('delete', $manpowerApproval);
        $this->service->cancel($manpowerApproval, $request->string('remarks') ?: 'Cancelled', $request->user());
        return redirect()->route('manpower-approvals.index')->with('success', __('Record cancelled.'));
    }
}
