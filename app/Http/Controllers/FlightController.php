<?php

namespace App\Http\Controllers;

use App\Http\Requests\Flight\ChangeFlightStatusRequest;
use App\Http\Requests\Flight\StoreFlightRequest;
use App\Http\Requests\Flight\UpdateFlightRequest;
use App\Models\Flight;
use App\Services\FlightService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FlightController extends Controller
{
    public function __construct(protected FlightService $service) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', Flight::class);
        $records = $this->service->scopedQuery($request->user())
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->when($request->filled('search'), fn ($q) => $q->whereHas('recruitment', fn ($r) => $r->where('tracking_number', 'like', '%' . $request->string('search') . '%')))
            ->paginate(15)->withQueryString();
        return view('flights.index', ['records' => $records, 'statuses' => FlightService::STATUSES]);
    }

    public function create(Request $request)
    {
        $this->authorize('create', Flight::class);
        $recruitments = \App\Models\Recruitment::visibleTo($request->user())->when($request->filled('service_type'), fn ($query) => $query->where('service_type', $request->string('service_type')))->orderByDesc('id')->limit(200)->get();
        $airlines = \App\Models\Airline::orderBy('name')->get();
        $travelAgencies = \App\Models\TravelAgency::orderBy('name')->get();
        return view('flights.create', array_merge(['recruitments' => $recruitments], ['airlines' => $airlines, 'travelAgencies' => $travelAgencies]));
    }

    public function store(StoreFlightRequest $request)
    {
        $record = $this->service->create($request->validated(), $request->user());
        if ($request->wantsJson()) {
            return response()->json(new \App\Http\Resources\FlightResource($record), 201);
        }
        return redirect()->route('flights.show', $record)->with('success', __('Record created.'));
    }

    public function show(Flight $flight)
    {
        $this->authorize('view', $flight);
        $flight->load(['recruitment.passportHolder', 'recruitment.stageHistories']);
        return view('flights.show', ['record' => $flight, 'recruitment' => $flight->recruitment, 'statuses' => FlightService::STATUSES]);
    }

    public function edit(Flight $flight)
    {
        $this->authorize('update', $flight);
        $recruitments = \App\Models\Recruitment::visibleTo(auth()->user())->orderByDesc('id')->limit(200)->get();
        $airlines = \App\Models\Airline::orderBy('name')->get();
        $travelAgencies = \App\Models\TravelAgency::orderBy('name')->get();
        return view('flights.edit', array_merge(['record' => $flight, 'recruitments' => $recruitments], ['airlines' => $airlines, 'travelAgencies' => $travelAgencies]));
    }

    public function update(UpdateFlightRequest $request, Flight $flight)
    {
        $record = $this->service->update($flight, $request->validated(), $request->user());
        if ($request->wantsJson()) {
            return response()->json(new \App\Http\Resources\FlightResource($record));
        }
        return redirect()->route('flights.show', $flight)->with('success', __('Record updated.'));
    }

    public function changeStatus(ChangeFlightStatusRequest $request, Flight $flight)
    {
        $this->authorize('changeStatus', $flight);
        $record = $this->service->changeStatus($flight, $request->validated()['status'], $request->validated()['remarks'] ?? null, $request->user());
        if ($request->wantsJson()) {
            return response()->json(new \App\Http\Resources\FlightResource($record));
        }
        return redirect()->route('flights.show', $flight)->with('success', __('Status updated.'));
    }

    public function download(Request $request, Flight $flight, string $field)
    {
        $this->authorize('download', $flight);
        $path = $this->service->downloadPath($flight, $field);
        abort_if($path === null, 404);
        return Storage::disk('private')->download($path);
    }

    /** Cancel/archive only — never a hard delete of a pipeline record. */
    public function destroy(Request $request, Flight $flight)
    {
        $this->authorize('delete', $flight);
        $this->service->cancel($flight, $request->string('remarks') ?: 'Cancelled', $request->user());
        return redirect()->route('flights.index')->with('success', __('Record cancelled.'));
    }
}
