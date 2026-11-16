<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Country\StoreCountryRequest;
use App\Http\Requests\Country\UpdateCountryRequest;
use App\Http\Resources\CountryResource;
use App\Models\Country;
use App\Services\CountryService;
use Illuminate\Http\Request;
use Illuminate\Http\Request as HttpRequest;

/**
 * Country API controller (Phases 08-12, /api/v1).
 * Same services and policies as the web controller; never a raw model.
 */
class CountryController extends Controller
{
    public function __construct(protected CountryService $service)
    {
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Country::class);

        $page = $this->service->list($request->only(['search', 'status', 'branch_id', 'country_id', 'company_id', 'agent_id', 'current_status']), $request->user());

        return response()->json([
            'data' => CountryResource::collection($page),
            'meta' => [
                'current_page' => $page->currentPage(),
                'per_page' => $page->perPage(),
                'total' => $page->total(),
                'last_page' => $page->lastPage(),
            ],
            'message' => null,
        ]);
    }

    public function store(StoreCountryRequest $request)
    {
        $country = $this->service->create($request->validated(), $request->user());

        return response()->json([
            'data' => new CountryResource($country),
            'message' => 'Country created successfully.',
        ], 201);
    }

    public function show(Country $country)
    {
        $this->authorize('view', $country);

        return response()->json([
            'data' => new CountryResource($country),
            'message' => null,
        ]);
    }

    public function update(UpdateCountryRequest $request, Country $country)
    {
        $country = $this->service->update($country, $request->validated(), $request->user());

        return response()->json([
            'data' => new CountryResource($country),
            'message' => 'Country updated successfully.',
        ]);
    }

    public function destroy(Country $country)
    {
        $this->authorize('delete', $country);

        $this->service->delete($country, request()->user());

        return response()->json([
            'data' => null,
            'message' => 'Country removed successfully.',
        ]);
    }
}
