<?php

namespace App\Http\Controllers\Hrm;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use App\Services\EmployeeService;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function __construct(protected EmployeeService $service) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', Employee::class);

        $items = Employee::query()
            ->with(['department', 'designation', 'user'])
            ->when($request->filled('search'), fn ($q) => $q->where('employee_code', 'like', '%' . $request->search . '%'))
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        if ($request->wantsJson()) {
            return EmployeeResource::collection($items);
        }

        return view('hrm.employees.index', compact('items'));
    }

    public function create()
    {
        $this->authorize('create', Employee::class);

        return view('hrm.employees.create', $this->formData());
    }

    public function store(StoreEmployeeRequest $request)
    {
        $this->authorize('create', Employee::class);

        $item = $this->service->create($request->validated());

        return redirect()->route('hrm.employees.show', $item)
            ->with('success', 'Employee created successfully.');
    }

    public function show(Employee $item)
    {
        $this->authorize('view', $item);

        if (request()->wantsJson()) {
            return new EmployeeResource($item);
        }

        return view('hrm.employees.show', compact('item'));
    }

    public function edit(Employee $item)
    {
        $this->authorize('update', $item);

        return view('hrm.employees.edit', array_merge(['item' => $item], $this->formData($item)));
    }

    public function update(StoreEmployeeRequest $request, Employee $item)
    {
        $this->authorize('update', $item);

        $this->service->update($item, $request->validated());

        return redirect()->route('hrm.employees.show', $item)
            ->with('success', 'Employee updated successfully.');
    }

    public function destroy(Employee $item)
    {
        $this->authorize('delete', $item);

        $deleted = $this->service->delete($item);

        return redirect()->route('hrm.employees.index')
            ->with('success', $deleted ? 'Employee deleted.' : 'Employee has posted records, so it was deactivated instead of deleted.');
    }

    protected function formData(?Employee $item = null): array
    {
        return [
            'departments' => \App\Models\Department::where('status', 'active')->orderBy('name')->get(),
            'designations' => \App\Models\Designation::where('status', 'active')->orderBy('name')->get(),
        ];
    }
}
