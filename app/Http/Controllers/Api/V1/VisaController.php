<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\VisaResource;
use App\Models\Visa;
use Illuminate\Http\Request;

/**
 * Visa & MOFA API (Phase 30) — read endpoints. Visa creation and
 * MOFA progression happen on the web pipeline (Phase 19) through
 * VisaService; the API exposes the current state for tracking.
 */
class VisaController extends ApiController
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Visa::class);

        $visas = Visa::query()
            ->with(['recruitment.passportHolder', 'recruitment.country', 'lot'])
            ->whereHas('recruitment', fn ($q) => $q->visibleTo($request->user()))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->when($request->filled('mofa_status'), fn ($q) => $q->where('mofa_status', $request->string('mofa_status')))
            ->when($request->filled('country_id'), fn ($q) => $q->whereHas('recruitment', fn ($r) => $r->where('country_id', $request->integer('country_id'))))
            ->orderByDesc('submission_date')
            ->paginate($request->integer('per_page', $this->perPage));

        return $this->paginated($visas, VisaResource::collection($visas));
    }

    public function show(Visa $visa)
    {
        $this->authorize('view', $visa);

        return $this->success(new VisaResource($visa->load(['recruitment.passportHolder', 'lot'])));
    }
}
