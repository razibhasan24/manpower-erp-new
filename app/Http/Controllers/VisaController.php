<?php

namespace App\Http\Controllers;

use App\Http\Requests\Visa\ChangeVisaStatusRequest;
use App\Http\Requests\Visa\StoreVisaRequest;
use App\Http\Requests\Visa\UpdateVisaRequest;
use App\Models\Visa;
use App\Services\VisaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VisaController extends Controller
{
    public function __construct(protected VisaService $service) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', Visa::class);
        $records = $this->service->scopedQuery($request->user())
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->when($request->filled('mofa_status'), fn ($q) => $q->where('mofa_status', $request->string('mofa_status')))
            ->when($request->filled('search'), fn ($q) => $q->whereHas('recruitment', fn ($r) => $r->where('tracking_number', 'like', '%' . $request->string('search') . '%')))
            ->paginate(15)->withQueryString();
        return view('visas.index', ['records' => $records, 'statuses' => VisaService::STATUSES]);
    }

    public function create(Request $request)
    {
        $this->authorize('create', Visa::class);
        $recruitments = \App\Models\Recruitment::visibleTo($request->user())->when($request->filled('service_type'), fn ($query) => $query->where('service_type', $request->string('service_type')))->orderByDesc('id')->limit(200)->get();
        $visaLots = \App\Models\VisaLot::orderBy('lot_number')->get();
        return view('visas.create', array_merge(['recruitments' => $recruitments], ['visaLots' => $visaLots]));
    }

    public function store(StoreVisaRequest $request)
    {
        $record = $this->service->create($request->validated(), $request->user());
        if ($request->wantsJson()) {
            return response()->json(new \App\Http\Resources\VisaResource($record), 201);
        }
        return redirect()->route('visas.show', $record)->with('success', __('Record created.'));
    }

    public function show(Visa $visa)
    {
        $this->authorize('view', $visa);
        $visa->load(['recruitment.passportHolder', 'recruitment.stageHistories']);
        return view('visas.show', ['record' => $visa, 'recruitment' => $visa->recruitment, 'statuses' => VisaService::STATUSES]);
    }

    public function edit(Visa $visa)
    {
        $this->authorize('update', $visa);
        $recruitments = \App\Models\Recruitment::visibleTo(auth()->user())->orderByDesc('id')->limit(200)->get();
        $visaLots = \App\Models\VisaLot::orderBy('lot_number')->get();
        return view('visas.edit', array_merge(['record' => $visa, 'recruitments' => $recruitments], ['visaLots' => $visaLots]));
    }

    public function update(UpdateVisaRequest $request, Visa $visa)
    {
        $record = $this->service->update($visa, $request->validated(), $request->user());
        if ($request->wantsJson()) {
            return response()->json(new \App\Http\Resources\VisaResource($record));
        }
        return redirect()->route('visas.show', $visa)->with('success', __('Record updated.'));
    }

    public function changeStatus(ChangeVisaStatusRequest $request, Visa $visa)
    {
        $this->authorize('changeStatus', $visa);
        $record = $this->service->changeStatus($visa, $request->validated()['status'], $request->validated()['remarks'] ?? null, $request->user());
        if ($request->wantsJson()) {
            return response()->json(new \App\Http\Resources\VisaResource($record));
        }
        return redirect()->route('visas.show', $visa)->with('success', __('Status updated.'));
    }

    public function download(Request $request, Visa $visa, string $field)
    {
        $this->authorize('download', $visa);
        $path = $this->service->downloadPath($visa, $field);
        abort_if($path === null, 404);
        return Storage::disk('private')->download($path);
    }

    /** Cancel/archive only — never a hard delete of a pipeline record. */
    public function destroy(Request $request, Visa $visa)
    {
        $this->authorize('delete', $visa);
        $this->service->cancel($visa, $request->string('remarks') ?: 'Cancelled', $request->user());
        return redirect()->route('visas.index')->with('success', __('Record cancelled.'));
    }

    public function updateMofa(Request $request, Visa $visa)
    {
        $this->authorize('update', $visa);
        $data = $request->validate(['mofa_status' => ['required', 'in:pending,submitted,approved,rejected,returned'], 'reference_number' => ['nullable', 'string', 'max:255'], 'remarks' => ['nullable', 'string']]);
        $this->service->updateMofa($visa, $data, $request->user());
        return redirect()->route('visas.show', $visa)->with('success', __('MOFA status updated.'));
    }

    public function storeWorkPermit(Request $request, Visa $visa)
    {
        $this->authorize('update', $visa);
        $data = $request->validate(['permit_number' => ['required', 'string', 'max:255'], 'issue_date' => ['nullable', 'date'], 'expiry_date' => ['nullable', 'date'], 'remarks' => ['nullable', 'string']]);
        $this->service->storeWorkPermit($visa, $data, $request->user());
        return redirect()->route('visas.show', $visa)->with('success', __('Work permit saved.'));
    }

    public function storeAppointment(Request $request, Visa $visa)
    {
        $this->authorize('update', $visa);
        $data = $request->validate(['appointment_date' => ['required', 'date'], 'location' => ['nullable', 'string', 'max:255'], 'status' => ['nullable', 'in:pending,confirmed,completed,cancelled'], 'remarks' => ['nullable', 'string']]);
        $this->service->storeAppointment($visa, $data, $request->user());
        return redirect()->route('visas.show', $visa)->with('success', __('Appointment saved.'));
    }
}
