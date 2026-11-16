<?php

namespace App\Http\Controllers;

use App\Http\Requests\VisaReturn\ChangeVisaReturnStatusRequest;
use App\Http\Requests\VisaReturn\StoreVisaReturnRequest;
use App\Http\Requests\VisaReturn\UpdateVisaReturnRequest;
use App\Models\VisaReturn;
use App\Services\VisaReturnService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VisaReturnController extends Controller
{
    public function __construct(protected VisaReturnService $service) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', VisaReturn::class);
        $records = $this->service->scopedQuery($request->user())
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->when($request->filled('search'), fn ($q) => $q->whereHas('recruitment', fn ($r) => $r->where('tracking_number', 'like', '%' . $request->string('search') . '%')))
            ->paginate(15)->withQueryString();
        return view('visa-returns.index', ['records' => $records, 'statuses' => VisaReturnService::STATUSES]);
    }

    public function create(Request $request)
    {
        $this->authorize('create', VisaReturn::class);
        $recruitments = \App\Models\Recruitment::visibleTo($request->user())->orderByDesc('id')->limit(200)->get();
        $visas = \App\Models\Visa::with('recruitment')->orderByDesc('id')->limit(200)->get();
        return view('visa-returns.create', array_merge(['recruitments' => $recruitments], ['visas' => $visas]));
    }

    public function store(StoreVisaReturnRequest $request)
    {
        $record = $this->service->create($request->validated(), $request->user());
        if ($request->wantsJson()) {
            return response()->json(new \App\Http\Resources\VisaReturnResource($record), 201);
        }
        return redirect()->route('visa-returns.show', $record)->with('success', __('Record created.'));
    }

    public function show(VisaReturn $visaReturn)
    {
        $this->authorize('view', $visaReturn);
        $visaReturn->load(['recruitment.passportHolder', 'recruitment.stageHistories']);
        return view('visa-returns.show', ['record' => $visaReturn, 'recruitment' => $visaReturn->recruitment, 'statuses' => VisaReturnService::STATUSES]);
    }

    public function edit(VisaReturn $visaReturn)
    {
        $this->authorize('update', $visaReturn);
        $recruitments = \App\Models\Recruitment::visibleTo(auth()->user())->orderByDesc('id')->limit(200)->get();
        $visas = \App\Models\Visa::with('recruitment')->orderByDesc('id')->limit(200)->get();
        return view('visa-returns.edit', array_merge(['record' => $visaReturn, 'recruitments' => $recruitments], ['visas' => $visas]));
    }

    public function update(UpdateVisaReturnRequest $request, VisaReturn $visaReturn)
    {
        $record = $this->service->update($visaReturn, $request->validated(), $request->user());
        if ($request->wantsJson()) {
            return response()->json(new \App\Http\Resources\VisaReturnResource($record));
        }
        return redirect()->route('visa-returns.show', $visaReturn)->with('success', __('Record updated.'));
    }

    public function changeStatus(ChangeVisaReturnStatusRequest $request, VisaReturn $visaReturn)
    {
        $this->authorize('changeStatus', $visaReturn);
        $record = $this->service->changeStatus($visaReturn, $request->validated()['status'], $request->validated()['remarks'] ?? null, $request->user());
        if ($request->wantsJson()) {
            return response()->json(new \App\Http\Resources\VisaReturnResource($record));
        }
        return redirect()->route('visa-returns.show', $visaReturn)->with('success', __('Status updated.'));
    }

    public function download(Request $request, VisaReturn $visaReturn, string $field)
    {
        $this->authorize('download', $visaReturn);
        $path = $this->service->downloadPath($visaReturn, $field);
        abort_if($path === null, 404);
        return Storage::disk('private')->download($path);
    }

    /** Cancel/archive only — never a hard delete of a pipeline record. */
    public function destroy(Request $request, VisaReturn $visaReturn)
    {
        $this->authorize('delete', $visaReturn);
        $this->service->cancel($visaReturn, $request->string('remarks') ?: 'Cancelled', $request->user());
        return redirect()->route('visa-returns.index')->with('success', __('Record cancelled.'));
    }
}
