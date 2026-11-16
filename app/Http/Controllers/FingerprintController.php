<?php

namespace App\Http\Controllers;

use App\Http\Requests\Fingerprint\ChangeFingerprintStatusRequest;
use App\Http\Requests\Fingerprint\StoreFingerprintRequest;
use App\Http\Requests\Fingerprint\UpdateFingerprintRequest;
use App\Models\Fingerprint;
use App\Services\FingerprintService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FingerprintController extends Controller
{
    public function __construct(protected FingerprintService $service) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', Fingerprint::class);
        $records = $this->service->scopedQuery($request->user())
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->when($request->filled('search'), fn ($q) => $q->whereHas('recruitment', fn ($r) => $r->where('tracking_number', 'like', '%' . $request->string('search') . '%')))
            ->paginate(15)->withQueryString();
        return view('fingerprints.index', ['records' => $records, 'statuses' => FingerprintService::STATUSES]);
    }

    public function create(Request $request)
    {
        $this->authorize('create', Fingerprint::class);
        $recruitments = \App\Models\Recruitment::visibleTo($request->user())->orderByDesc('id')->limit(200)->get();
        return view('fingerprints.create', compact('recruitments'));
    }

    public function store(StoreFingerprintRequest $request)
    {
        $record = $this->service->create($request->validated(), $request->user());
        if ($request->wantsJson()) {
            return response()->json(new \App\Http\Resources\FingerprintResource($record), 201);
        }
        return redirect()->route('fingerprints.show', $record)->with('success', __('Record created.'));
    }

    public function show(Fingerprint $fingerprint)
    {
        $this->authorize('view', $fingerprint);
        $fingerprint->load(['recruitment.passportHolder', 'recruitment.stageHistories']);
        return view('fingerprints.show', ['record' => $fingerprint, 'recruitment' => $fingerprint->recruitment, 'statuses' => FingerprintService::STATUSES]);
    }

    public function edit(Fingerprint $fingerprint)
    {
        $this->authorize('update', $fingerprint);
        $recruitments = \App\Models\Recruitment::visibleTo(auth()->user())->orderByDesc('id')->limit(200)->get();
        return view('fingerprints.edit', ['record' => $fingerprint, 'recruitments' => $recruitments]);
    }

    public function update(UpdateFingerprintRequest $request, Fingerprint $fingerprint)
    {
        $record = $this->service->update($fingerprint, $request->validated(), $request->user());
        if ($request->wantsJson()) {
            return response()->json(new \App\Http\Resources\FingerprintResource($record));
        }
        return redirect()->route('fingerprints.show', $fingerprint)->with('success', __('Record updated.'));
    }

    public function changeStatus(ChangeFingerprintStatusRequest $request, Fingerprint $fingerprint)
    {
        $this->authorize('changeStatus', $fingerprint);
        $record = $this->service->changeStatus($fingerprint, $request->validated()['status'], $request->validated()['remarks'] ?? null, $request->user());
        if ($request->wantsJson()) {
            return response()->json(new \App\Http\Resources\FingerprintResource($record));
        }
        return redirect()->route('fingerprints.show', $fingerprint)->with('success', __('Status updated.'));
    }

    public function download(Request $request, Fingerprint $fingerprint, string $field)
    {
        $this->authorize('download', $fingerprint);
        $path = $this->service->downloadPath($fingerprint, $field);
        abort_if($path === null, 404);
        return Storage::disk('private')->download($path);
    }

    /** Cancel/archive only — never a hard delete of a pipeline record. */
    public function destroy(Request $request, Fingerprint $fingerprint)
    {
        $this->authorize('delete', $fingerprint);
        $this->service->cancel($fingerprint, $request->string('remarks') ?: 'Cancelled', $request->user());
        return redirect()->route('fingerprints.index')->with('success', __('Record cancelled.'));
    }
}
