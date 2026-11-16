<?php

namespace App\Http\Controllers\Hrm;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDesignationRequest;
use App\Http\Resources\DesignationResource;
use App\Models\Designation;
use App\Services\DesignationService;
use Illuminate\Http\Request;

class DesignationController extends Controller
{
    public function __construct(protected DesignationService $service) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', Designation::class);

        $items = Designation::query()
            
            ->when($request->filled('search'), fn ($q) => $q->where('name', 'like', '%' . $request->search . '%'))
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        if ($request->wantsJson()) {
            return DesignationResource::collection($items);
        }

        return view('hrm.designations.index', compact('items'));
    }

    public function create()
    {
        $this->authorize('create', Designation::class);

        return view('hrm.designations.create', $this->formData());
    }

    public function store(StoreDesignationRequest $request)
    {
        $this->authorize('create', Designation::class);

        $item = $this->service->create($request->validated());

        return redirect()->route('hrm.designations.show', $item)
            ->with('success', 'Designation created successfully.');
    }

    public function show(Designation $item)
    {
        $this->authorize('view', $item);

        if (request()->wantsJson()) {
            return new DesignationResource($item);
        }

        return view('hrm.designations.show', compact('item'));
    }

    public function edit(Designation $item)
    {
        $this->authorize('update', $item);

        return view('hrm.designations.edit', array_merge(['item' => $item], $this->formData($item)));
    }

    public function update(StoreDesignationRequest $request, Designation $item)
    {
        $this->authorize('update', $item);

        $this->service->update($item, $request->validated());

        return redirect()->route('hrm.designations.show', $item)
            ->with('success', 'Designation updated successfully.');
    }

    public function destroy(Designation $item)
    {
        $this->authorize('delete', $item);

        $deleted = $this->service->delete($item);

        return redirect()->route('hrm.designations.index')
            ->with('success', $deleted ? 'Designation deleted.' : 'Designation has posted records, so it was deactivated instead of deleted.');
    }

    protected function formData(?Designation $item = null): array
    {
        return [
            'departments' => \App\Models\Department::where('status', 'active')->orderBy('name')->get(),
        ];
    }
}
