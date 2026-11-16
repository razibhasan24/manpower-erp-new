<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\FlightResource;
use App\Models\Flight;
use Illuminate\Http\Request;

/**
 * Flight API (Phase 30) — read endpoints for tracking. Flight booking
 * and ticket issue happen on the web pipeline (Phase 21) through
 * FlightService.
 */
class FlightController extends ApiController
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Flight::class);

        $flights = Flight::query()
            ->with(['recruitment.passportHolder', 'airline', 'travelAgency'])
            ->whereHas('recruitment', fn ($q) => $q->visibleTo($request->user()))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->when($request->filled('date_from'), fn ($q) => $q->whereDate('departure_date', '>=', $request->date('date_from')))
            ->when($request->filled('date_to'), fn ($q) => $q->whereDate('departure_date', '<=', $request->date('date_to')))
            ->orderByDesc('departure_date')
            ->paginate($request->integer('per_page', $this->perPage));

        return $this->paginated($flights, FlightResource::collection($flights));
    }

    public function show(Flight $flight)
    {
        $this->authorize('view', $flight);

        return $this->success(new FlightResource($flight->load(['recruitment.passportHolder', 'airline', 'travelAgency'])));
    }
}
