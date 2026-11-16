<?php

namespace App\Http\Controllers\Hrm;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDepartmentRequest;
use App\Http\Resources\DepartmentResource;
use App\Models\Department;
use App\Services\DepartmentService;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function __construct(protected DepartmentService $service) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', Department::class);

        $items = Department::query()
            
            ->when($request->filled('search'), fn ($q) => $q->where('name', 'like', '%' . $request->search . '%'))
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        if ($request->wantsJson()) {
            return DepartmentResource::collection($items);
        }

        return view('hrm.departments.index', compact('items'));
    }

    public function create()
    {
        $this->authorize('create', Department::class);

        return view('hrm.departments.create', $this->formData());
    }

    public function store(StoreDepartmentRequest $request)
    {
        $this->authorize('create', Department::class);

        $item = $this->service->create($request->validated());

        return redirect()->route('hrm.departments.show', $item)
            ->with('success', 'Department created successfully.');
    }

    public function show(Department $item)
    {
        $this->authorize('view', $item);

        if (request()->wantsJson()) {
            return new DepartmentResource($item);
        }

        return view('hrm.departments.show', compact('item'));
    }

    public function edit(Department $item)
    {
        $this->authorize('update', $item);

        return view('hrm.departments.edit', array_merge(['item' => $item], $this->formData($item)));
    }

    public function update(StoreDepartmentRequest $request, Department $item)
    {
        $this->authorize('update', $item);

        $this->service->update($item, $request->validated());

        return redirect()->route('hrm.departments.show', $item)
            ->with('success', 'Department updated successfully.');
    }

    public function destroy(Department $item)
    {
        $this->authorize('delete', $item);

        $deleted = $this->service->delete($item);

        return redirect()->route('hrm.departments.index')
            ->with('success', $deleted ? 'Department deleted.' : 'Department has posted records, so it was deactivated instead of deleted.');
    }

    protected function formData(?Department $item = null): array
    {
        return [];
    }
}
