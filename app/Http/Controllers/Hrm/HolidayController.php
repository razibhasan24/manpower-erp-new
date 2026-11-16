<?php

namespace App\Http\Controllers\Hrm;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHolidayRequest;
use App\Http\Resources\HolidayResource;
use App\Models\Holiday;
use App\Services\HolidayService;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    public function __construct(protected HolidayService $service) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', Holiday::class);

        $items = Holiday::query()
            
            ->when($request->filled('search'), fn ($q) => $q->where('name', 'like', '%' . $request->search . '%'))
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        if ($request->wantsJson()) {
            return HolidayResource::collection($items);
        }

        return view('hrm.holidays.index', compact('items'));
    }

    public function create()
    {
        $this->authorize('create', Holiday::class);

        return view('hrm.holidays.create', $this->formData());
    }

    public function store(StoreHolidayRequest $request)
    {
        $this->authorize('create', Holiday::class);

        $item = $this->service->create($request->validated());

        return redirect()->route('hrm.holidays.show', $item)
            ->with('success', 'Holiday created successfully.');
    }

    public function show(Holiday $item)
    {
        $this->authorize('view', $item);

        if (request()->wantsJson()) {
            return new HolidayResource($item);
        }

        return view('hrm.holidays.show', compact('item'));
    }

    public function edit(Holiday $item)
    {
        $this->authorize('update', $item);

        return view('hrm.holidays.edit', array_merge(['item' => $item], $this->formData($item)));
    }

    public function update(StoreHolidayRequest $request, Holiday $item)
    {
        $this->authorize('update', $item);

        $this->service->update($item, $request->validated());

        return redirect()->route('hrm.holidays.show', $item)
            ->with('success', 'Holiday updated successfully.');
    }

    public function destroy(Holiday $item)
    {
        $this->authorize('delete', $item);

        $deleted = $this->service->delete($item);

        return redirect()->route('hrm.holidays.index')
            ->with('success', $deleted ? 'Holiday deleted.' : 'Holiday has posted records, so it was deactivated instead of deleted.');
    }

    protected function formData(?Holiday $item = null): array
    {
        return [];
    }
}
