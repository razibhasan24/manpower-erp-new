<?php

namespace App\Http\Controllers\Hrm;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use App\Models\Branch;
use App\Models\Department;
use App\Models\Designation;
use App\Services\EmployeeService;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function __construct(protected EmployeeService $service) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', Employee::class);

        $items = Employee::visibleTo($request->user())
            ->with(['department', 'designation', 'user'])
            ->when($request->filled('search'), fn ($q) => $q->where(function ($filter) use ($request) {
                $search = '%'.$request->string('search').'%';
                $filter->where('employee_code', 'like', $search)
                    ->orWhere('name', 'like', $search)
                    ->orWhereHas('user', fn ($users) => $users->where('name', 'like', $search)->orWhere('email', 'like', $search));
            }))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        if ($request->wantsJson()) {
            return EmployeeResource::collection($items);
        }

        return view('hrm.employees.index', compact('items'));
    }

    public function create(Request $request)
    {
        $this->authorize('create', Employee::class);

        return view('hrm.employees.create', $this->formData($request));
    }

    public function store(StoreEmployeeRequest $request)
    {
        $this->authorize('create', Employee::class);

        $item = $this->service->create($request->validated());

        return redirect()->route('hrm.employees.show', $item)
            ->with('success', 'Employee created successfully.');
    }

    public function show(Employee $employee)
    {
        $this->authorize('view', $employee);
        $employee->load(['department', 'designation', 'branch', 'user', 'documents']);

        if (request()->wantsJson()) {
            return new EmployeeResource($employee);
        }

        return view('hrm.employees.show', ['item' => $employee]);
    }

    public function edit(Request $request, Employee $employee)
    {
        $this->authorize('update', $employee);

        return view('hrm.employees.edit', array_merge(['item' => $employee], $this->formData($request, $employee)));
    }

    public function update(UpdateEmployeeRequest $request, Employee $employee)
    {
        $this->authorize('update', $employee);

        $this->service->update($employee, $request->validated());

        return redirect()->route('hrm.employees.show', $employee)
            ->with('success', 'Employee updated successfully.');
    }

    public function destroy(Employee $employee)
    {
        $this->authorize('delete', $employee);

        $this->service->delete($employee);

        return redirect()->route('hrm.employees.index')
            ->with('success', 'Employee deactivated.');
    }

    protected function formData(Request $request, ?Employee $item = null): array
    {
        $departments = Department::query()->where(function ($query) use ($item) {
            $query->where('status', 'active')->when($item?->department_id, fn ($q, $id) => $q->orWhereKey($id));
        })->orderBy('name')->get();
        $designations = Designation::query()->where(function ($query) use ($item) {
            $query->where('status', 'active')->when($item?->designation_id, fn ($q, $id) => $q->orWhereKey($id));
        })->orderBy('name')->get();
        $branches = Branch::visibleTo($request->user())->where(function ($query) use ($item) {
            $query->where('status', 'active')->when($item?->branch_id, fn ($q, $id) => $q->orWhereKey($id));
        })->orderBy('name')->get();
        return [
            'departments' => $departments,
            'designations' => $designations,
            'branches' => $branches,
        ];
    }
}
