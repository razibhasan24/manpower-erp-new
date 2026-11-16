<?php

namespace App\Http\Controllers\Hrm;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLeaveRequest;
use App\Http\Resources\LeaveResource;
use App\Models\Leave;
use App\Services\LeaveService;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    public function __construct(protected LeaveService $service) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', Leave::class);

        $items = Leave::query()
            ->with(['employee'])
            ->when($request->filled('search'), fn ($q) => $q->where('type', 'like', '%' . $request->search . '%'))
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        if ($request->wantsJson()) {
            return LeaveResource::collection($items);
        }

        return view('hrm.leaves.index', compact('items'));
    }

    public function create()
    {
        $this->authorize('create', Leave::class);

        return view('hrm.leaves.create', $this->formData());
    }

    public function store(StoreLeaveRequest $request)
    {
        $this->authorize('create', Leave::class);

        $item = $this->service->create($request->validated());

        return redirect()->route('hrm.leaves.show', $item)
            ->with('success', 'Leave created successfully.');
    }

    public function show(Leave $item)
    {
        $this->authorize('view', $item);

        if (request()->wantsJson()) {
            return new LeaveResource($item);
        }

        return view('hrm.leaves.show', compact('item'));
    }

    public function edit(Leave $item)
    {
        $this->authorize('update', $item);

        return view('hrm.leaves.edit', array_merge(['item' => $item], $this->formData($item)));
    }

    public function update(StoreLeaveRequest $request, Leave $item)
    {
        $this->authorize('update', $item);

        $this->service->update($item, $request->validated());

        return redirect()->route('hrm.leaves.show', $item)
            ->with('success', 'Leave updated successfully.');
    }

    public function destroy(Leave $item)
    {
        $this->authorize('delete', $item);

        $deleted = $this->service->delete($item);

        return redirect()->route('hrm.leaves.index')
            ->with('success', $deleted ? 'Leave deleted.' : 'Leave has posted records, so it was deactivated instead of deleted.');
    }

    protected function formData(?Leave $item = null): array
    {
        return [
            'employees' => \App\Models\Employee::where('status', 'active')->orderBy('name')->get(),
        ];
    }
}
