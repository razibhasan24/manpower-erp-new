<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\StoreCompanyRequest;
use App\Http\Requests\Company\UpdateCompanyRequest;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use App\Services\CompanyService;
use Illuminate\Http\Request;
use Illuminate\Http\Request as HttpRequest;

/**
 * Company API controller (Phases 08-12, /api/v1).
 * Same services and policies as the web controller; never a raw model.
 */
class CompanyController extends Controller
{
    public function __construct(protected CompanyService $service)
    {
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Company::class);

        $page = $this->service->list($request->only(['search', 'status', 'branch_id', 'country_id', 'company_id', 'agent_id', 'current_status']), $request->user());

        return response()->json([
            'data' => CompanyResource::collection($page),
            'meta' => [
                'current_page' => $page->currentPage(),
                'per_page' => $page->perPage(),
                'total' => $page->total(),
                'last_page' => $page->lastPage(),
            ],
            'message' => null,
        ]);
    }

    public function store(StoreCompanyRequest $request)
    {
        $company = $this->service->create($request->validated(), $request->user());

        return response()->json([
            'data' => new CompanyResource($company),
            'message' => 'Company created successfully.',
        ], 201);
    }

    public function show(Company $company)
    {
        $this->authorize('view', $company);

        return response()->json([
            'data' => new CompanyResource($company),
            'message' => null,
        ]);
    }

    public function update(UpdateCompanyRequest $request, Company $company)
    {
        $company = $this->service->update($company, $request->validated(), $request->user());

        return response()->json([
            'data' => new CompanyResource($company),
            'message' => 'Company updated successfully.',
        ]);
    }

    public function destroy(Company $company)
    {
        $this->authorize('delete', $company);

        $this->service->delete($company, request()->user());

        return response()->json([
            'data' => null,
            'message' => 'Company removed successfully.',
        ]);
    }
}
