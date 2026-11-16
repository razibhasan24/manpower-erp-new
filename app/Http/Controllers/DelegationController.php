<?php

namespace App\Http\Controllers;

use App\Http\Requests\Delegation\ChangeDelegationStatusRequest;
use App\Http\Requests\Delegation\StoreDelegationRequest;
use App\Http\Requests\Delegation\UpdateDelegationRequest;
use App\Models\Delegation;
use App\Services\DelegationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DelegationController extends Controller
{
    public function __construct(protected DelegationService $service) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', Delegation::class);
        $records = $this->service->scopedQuery($request->user())
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->when($request->filled('search'), fn ($q) => $q->whereHas('recruitment', fn ($r) => $r->where('tracking_number', 'like', '%' . $request->string('search') . '%')))
            ->paginate(15)->withQueryString();
        return view('delegations.index', ['records' => $records, 'statuses' => DelegationService::STATUSES]);
    }

    public function create(Request $request)
    {
        $this->authorize('create', Delegation::class);
        $recruitments = \App\Models\Recruitment::visibleTo($request->user())->orderByDesc('id')->limit(200)->get();
        return view('delegations.create', compact('recruitments'));
    }

    public function store(StoreDelegationRequest $request)
    {
        $record = $this->service->create($request->validated(), $request->user());
        if ($request->wantsJson()) {
            return response()->json(new \App\Http\Resources\DelegationResource($record), 201);
        }
        return redirect()->route('delegations.show', $record)->with('success', __('Record created.'));
    }

    public function show(Delegation $delegation)
    {
        $this->authorize('view', $delegation);
        $delegation->load(['recruitment.passportHolder', 'recruitment.stageHistories']);
        return view('delegations.show', ['record' => $delegation, 'recruitment' => $delegation->recruitment, 'statuses' => DelegationService::STATUSES]);
    }

    public function edit(Delegation $delegation)
    {
        $this->authorize('update', $delegation);
        $recruitments = \App\Models\Recruitment::visibleTo(auth()->user())->orderByDesc('id')->limit(200)->get();
        return view('delegations.edit', ['record' => $delegation, 'recruitments' => $recruitments]);
    }

    public function update(UpdateDelegationRequest $request, Delegation $delegation)
    {
        $record = $this->service->update($delegation, $request->validated(), $request->user());
        if ($request->wantsJson()) {
            return response()->json(new \App\Http\Resources\DelegationResource($record));
        }
        return redirect()->route('delegations.show', $delegation)->with('success', __('Record updated.'));
    }

    public function changeStatus(ChangeDelegationStatusRequest $request, Delegation $delegation)
    {
        $this->authorize('changeStatus', $delegation);
        $record = $this->service->changeStatus($delegation, $request->validated()['status'], $request->validated()['remarks'] ?? null, $request->user());
        if ($request->wantsJson()) {
            return response()->json(new \App\Http\Resources\DelegationResource($record));
        }
        return redirect()->route('delegations.show', $delegation)->with('success', __('Status updated.'));
    }

    public function download(Request $request, Delegation $delegation, string $field)
    {
        $this->authorize('download', $delegation);
        $path = $this->service->downloadPath($delegation, $field);
        abort_if($path === null, 404);
        return Storage::disk('private')->download($path);
    }

    /** Cancel/archive only — never a hard delete of a pipeline record. */
    public function destroy(Request $request, Delegation $delegation)
    {
        $this->authorize('delete', $delegation);
        $this->service->cancel($delegation, $request->string('remarks') ?: 'Cancelled', $request->user());
        return redirect()->route('delegations.index')->with('success', __('Record cancelled.'));
    }
}
