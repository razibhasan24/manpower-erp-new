<?php

namespace App\Http\Controllers;

use App\Http\Requests\PoliceClearance\ChangePoliceClearanceStatusRequest;
use App\Http\Requests\PoliceClearance\StorePoliceClearanceRequest;
use App\Http\Requests\PoliceClearance\UpdatePoliceClearanceRequest;
use App\Models\PoliceClearance;
use App\Services\PoliceClearanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PoliceClearanceController extends Controller
{
    public function __construct(protected PoliceClearanceService $service) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', PoliceClearance::class);
        $records = $this->service->scopedQuery($request->user())
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->when($request->filled('search'), fn ($q) => $q->whereHas('recruitment', fn ($r) => $r->where('tracking_number', 'like', '%' . $request->string('search') . '%')))
            ->paginate(15)->withQueryString();
        return view('police-clearances.index', ['records' => $records, 'statuses' => PoliceClearanceService::STATUSES]);
    }

    public function create(Request $request)
    {
        $this->authorize('create', PoliceClearance::class);
        $recruitments = \App\Models\Recruitment::visibleTo($request->user())->orderByDesc('id')->limit(200)->get();
        return view('police-clearances.create', compact('recruitments'));
    }

    public function store(StorePoliceClearanceRequest $request)
    {
        $record = $this->service->create($request->validated(), $request->user());
        if ($request->wantsJson()) {
            return response()->json(new \App\Http\Resources\PoliceClearanceResource($record), 201);
        }
        return redirect()->route('police-clearances.show', $record)->with('success', __('Record created.'));
    }

    public function show(PoliceClearance $policeClearance)
    {
        $this->authorize('view', $policeClearance);
        $policeClearance->load(['recruitment.passportHolder', 'recruitment.stageHistories']);
        return view('police-clearances.show', ['record' => $policeClearance, 'recruitment' => $policeClearance->recruitment, 'statuses' => PoliceClearanceService::STATUSES]);
    }

    public function edit(PoliceClearance $policeClearance)
    {
        $this->authorize('update', $policeClearance);
        $recruitments = \App\Models\Recruitment::visibleTo(auth()->user())->orderByDesc('id')->limit(200)->get();
        return view('police-clearances.edit', ['record' => $policeClearance, 'recruitments' => $recruitments]);
    }

    public function update(UpdatePoliceClearanceRequest $request, PoliceClearance $policeClearance)
    {
        $record = $this->service->update($policeClearance, $request->validated(), $request->user());
        if ($request->wantsJson()) {
            return response()->json(new \App\Http\Resources\PoliceClearanceResource($record));
        }
        return redirect()->route('police-clearances.show', $policeClearance)->with('success', __('Record updated.'));
    }

    public function changeStatus(ChangePoliceClearanceStatusRequest $request, PoliceClearance $policeClearance)
    {
        $this->authorize('changeStatus', $policeClearance);
        $record = $this->service->changeStatus($policeClearance, $request->validated()['status'], $request->validated()['remarks'] ?? null, $request->user());
        if ($request->wantsJson()) {
            return response()->json(new \App\Http\Resources\PoliceClearanceResource($record));
        }
        return redirect()->route('police-clearances.show', $policeClearance)->with('success', __('Status updated.'));
    }

    public function download(Request $request, PoliceClearance $policeClearance, string $field)
    {
        $this->authorize('download', $policeClearance);
        $path = $this->service->downloadPath($policeClearance, $field);
        abort_if($path === null, 404);
        return Storage::disk('private')->download($path);
    }

    /** Cancel/archive only — never a hard delete of a pipeline record. */
    public function destroy(Request $request, PoliceClearance $policeClearance)
    {
        $this->authorize('delete', $policeClearance);
        $this->service->cancel($policeClearance, $request->string('remarks') ?: 'Cancelled', $request->user());
        return redirect()->route('police-clearances.index')->with('success', __('Record cancelled.'));
    }
}
