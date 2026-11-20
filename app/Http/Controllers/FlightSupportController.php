<?php

namespace App\Http\Controllers;

use App\Models\Airline;
use App\Models\Flight;
use App\Models\PostArrival;
use App\Models\TravelAgency;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class FlightSupportController extends Controller
{
    public function airlines(Request $request): View
    {
        $this->authorize('viewAny', Flight::class);
        $records = Airline::visibleTo($request->user())
            ->when($request->filled('search'), fn ($q) => $q->where(function ($filter) use ($request) {
                $search = '%'.$request->string('search').'%';
                $filter->where('name', 'like', $search)->orWhere('code', 'like', $search);
            }))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->orderBy('name')->paginate(15)->withQueryString();

        return view('flight-support.airlines', compact('records'));
    }

    public function createAirline(): View
    {
        $this->authorize('create', Flight::class);

        return view('flight-support.airlines-create');
    }

    public function storeAirline(Request $request)
    {
        $this->authorize('create', Flight::class);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:10', Rule::unique('airlines', 'code')],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        Airline::create($data);

        return redirect()->route('airlines.index')->with('success', __('Airline created successfully.'));
    }

    public function travelAgencies(Request $request): View
    {
        $this->authorize('viewAny', Flight::class);
        $records = TravelAgency::visibleTo($request->user())
            ->when($request->filled('search'), fn ($q) => $q->where(function ($filter) use ($request) {
                $search = '%'.$request->string('search').'%';
                $filter->where('name', 'like', $search)
                    ->orWhere('phone', 'like', $search)
                    ->orWhere('email', 'like', $search);
            }))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->orderBy('name')->paginate(15)->withQueryString();

        return view('flight-support.travel-agencies', compact('records'));
    }

    public function createTravelAgency(): View
    {
        $this->authorize('create', Flight::class);

        return view('flight-support.travel-agencies-create');
    }

    public function storeTravelAgency(Request $request)
    {
        $this->authorize('create', Flight::class);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:5000'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        TravelAgency::create($data);

        return redirect()->route('travel-agencies.index')->with('success', __('Travel agency created successfully.'));
    }

    public function airportPickup(Request $request): View
    {
        $this->authorize('viewAny', PostArrival::class);
        $records = PostArrival::visibleTo($request->user())->where('airport_pickup', true)
            ->with('recruitment.passportHolder')
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->when($request->filled('search'), fn ($q) => $q->whereHas('recruitment', fn ($r) => $r->where('tracking_number', 'like', '%'.$request->string('search').'%')
                ->orWhereHas('passportHolder', fn ($h) => $h->where('full_name', 'like', '%'.$request->string('search').'%'))))
            ->orderByDesc('arrival_date')->orderByDesc('id')->paginate(15)->withQueryString();

        return view('flight-support.airport-pickup', compact('records'));
    }

    public function accommodation(Request $request): View
    {
        $this->authorize('viewAny', PostArrival::class);
        $records = PostArrival::visibleTo($request->user())->whereNotNull('accommodation')->where('accommodation', '<>', '')
            ->with('recruitment.passportHolder')
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->when($request->filled('search'), fn ($q) => $q->where(function ($filter) use ($request) {
                $search = '%'.$request->string('search').'%';
                $filter->where('accommodation', 'like', $search)
                    ->orWhereHas('recruitment', fn ($r) => $r->where('tracking_number', 'like', $search)
                        ->orWhereHas('passportHolder', fn ($h) => $h->where('full_name', 'like', $search)));
            }))
            ->orderByDesc('arrival_date')->orderByDesc('id')->paginate(15)->withQueryString();

        return view('flight-support.accommodation', compact('records'));
    }

    public function employerInformation(Request $request): View
    {
        $this->authorize('viewAny', PostArrival::class);
        $records = PostArrival::visibleTo($request->user())->whereNotNull('employer_contact')->where('employer_contact', '<>', '')
            ->with('recruitment.passportHolder')
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->when($request->filled('search'), fn ($q) => $q->where(function ($filter) use ($request) {
                $search = '%'.$request->string('search').'%';
                $filter->where('employer_contact', 'like', $search)
                    ->orWhereHas('recruitment', fn ($r) => $r->where('tracking_number', 'like', $search)
                        ->orWhereHas('passportHolder', fn ($h) => $h->where('full_name', 'like', $search)));
            }))
            ->orderByDesc('arrival_date')->orderByDesc('id')->paginate(15)->withQueryString();

        return view('flight-support.employer-information', compact('records'));
    }
}
