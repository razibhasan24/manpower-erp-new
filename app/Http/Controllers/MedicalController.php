<?php

namespace App\Http\Controllers;

use App\Http\Requests\Medical\ChangeMedicalStatusRequest;
use App\Http\Requests\Medical\StoreMedicalRequest;
use App\Http\Requests\Medical\UpdateMedicalRequest;
use App\Models\Medical;
use App\Services\MedicalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MedicalController extends Controller
{
    public function __construct(protected MedicalService $service) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', Medical::class);
        $records = $this->service->scopedQuery($request->user())
            ->when($request->filled('status'), fn ($q) => $q->where('result', $request->string('status')))
            ->when($request->filled('search'), fn ($q) => $q->whereHas('recruitment', fn ($r) => $r->where('tracking_number', 'like', '%' . $request->string('search') . '%')))
            ->paginate(15)->withQueryString();
        return view('medicals.index', ['records' => $records, 'statuses' => MedicalService::STATUSES]);
    }

    public function create(Request $request)
    {
        $this->authorize('create', Medical::class);
        $recruitments = \App\Models\Recruitment::visibleTo($request->user())->orderByDesc('id')->limit(200)->get();
        $medicalCentres = \App\Models\MedicalCentre::orderBy('name')->get();
        return view('medicals.create', array_merge(['recruitments' => $recruitments], ['medicalCentres' => $medicalCentres]));
    }

    public function store(StoreMedicalRequest $request)
    {
        $record = $this->service->create($request->validated(), $request->user());
        if ($request->wantsJson()) {
            return response()->json(new \App\Http\Resources\MedicalResource($record), 201);
        }
        return redirect()->route('medicals.show', $record)->with('success', __('Record created.'));
    }

    public function show(Medical $medical)
    {
        $this->authorize('view', $medical);
        $medical->load(['recruitment.passportHolder', 'recruitment.stageHistories']);
        return view('medicals.show', ['record' => $medical, 'recruitment' => $medical->recruitment, 'statuses' => MedicalService::STATUSES]);
    }

    public function edit(Medical $medical)
    {
        $this->authorize('update', $medical);
        $recruitments = \App\Models\Recruitment::visibleTo(auth()->user())->orderByDesc('id')->limit(200)->get();
        $medicalCentres = \App\Models\MedicalCentre::orderBy('name')->get();
        return view('medicals.edit', array_merge(['record' => $medical, 'recruitments' => $recruitments], ['medicalCentres' => $medicalCentres]));
    }

    public function update(UpdateMedicalRequest $request, Medical $medical)
    {
        $record = $this->service->update($medical, $request->validated(), $request->user());
        if ($request->wantsJson()) {
            return response()->json(new \App\Http\Resources\MedicalResource($record));
        }
        return redirect()->route('medicals.show', $medical)->with('success', __('Record updated.'));
    }

    public function changeStatus(ChangeMedicalStatusRequest $request, Medical $medical)
    {
        $this->authorize('changeStatus', $medical);
        $record = $this->service->changeStatus($medical, $request->validated()['status'], $request->validated()['remarks'] ?? null, $request->user());
        if ($request->wantsJson()) {
            return response()->json(new \App\Http\Resources\MedicalResource($record));
        }
        return redirect()->route('medicals.show', $medical)->with('success', __('Status updated.'));
    }

    public function download(Request $request, Medical $medical, string $field)
    {
        $this->authorize('download', $medical);
        $path = $this->service->downloadPath($medical, $field);
        abort_if($path === null, 404);
        return Storage::disk('private')->download($path);
    }

    /** Cancel/archive only — never a hard delete of a pipeline record. */
    public function destroy(Request $request, Medical $medical)
    {
        $this->authorize('delete', $medical);
        $this->service->cancel($medical, $request->string('remarks') ?: 'Cancelled', $request->user());
        return redirect()->route('medicals.index')->with('success', __('Record cancelled.'));
    }
}
