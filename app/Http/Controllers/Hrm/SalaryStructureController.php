<?php

namespace App\Http\Controllers\Hrm;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSalaryStructureRequest;
use App\Http\Resources\SalaryStructureResource;
use App\Models\SalaryStructure;
use App\Services\SalaryStructureService;
use Illuminate\Http\Request;

class SalaryStructureController extends Controller
{
    public function __construct(protected SalaryStructureService $service) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', SalaryStructure::class);

        $items = SalaryStructure::query()
            ->with(['employee'])
            ->when($request->filled('search'), fn ($q) => $q->where('employee_id', 'like', '%' . $request->search . '%'))
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        if ($request->wantsJson()) {
            return SalaryStructureResource::collection($items);
        }

        return view('hrm.salary-structures.index', compact('items'));
    }

    public function create()
    {
        $this->authorize('create', SalaryStructure::class);

        return view('hrm.salary-structures.create', $this->formData());
    }

    public function store(StoreSalaryStructureRequest $request)
    {
        $this->authorize('create', SalaryStructure::class);

        $item = $this->service->create($request->validated());

        return redirect()->route('hrm.salary-structures.show', $item)
            ->with('success', 'Salary Structure created successfully.');
    }

    public function show(SalaryStructure $item)
    {
        $this->authorize('view', $item);

        if (request()->wantsJson()) {
            return new SalaryStructureResource($item);
        }

        return view('hrm.salary-structures.show', compact('item'));
    }

    public function edit(SalaryStructure $item)
    {
        $this->authorize('update', $item);

        return view('hrm.salary-structures.edit', array_merge(['item' => $item], $this->formData($item)));
    }

    public function update(StoreSalaryStructureRequest $request, SalaryStructure $item)
    {
        $this->authorize('update', $item);

        $this->service->update($item, $request->validated());

        return redirect()->route('hrm.salary-structures.show', $item)
            ->with('success', 'Salary Structure updated successfully.');
    }

    public function destroy(SalaryStructure $item)
    {
        $this->authorize('delete', $item);

        $deleted = $this->service->delete($item);

        return redirect()->route('hrm.salary-structures.index')
            ->with('success', $deleted ? 'Salary Structure deleted.' : 'Salary Structure has posted records, so it was deactivated instead of deleted.');
    }

    protected function formData(?SalaryStructure $item = null): array
    {
        return [
            'employees' => \App\Models\Employee::where('status', 'active')->orderBy('name')->get(),
        ];
    }
}
