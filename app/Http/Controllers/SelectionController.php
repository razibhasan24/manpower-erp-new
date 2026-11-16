<?php

namespace App\Http\Controllers;

use App\Http\Requests\Selection\ChangeSelectionStatusRequest;
use App\Http\Requests\Selection\StoreSelectionRequest;
use App\Http\Requests\Selection\UpdateSelectionRequest;
use App\Models\Selection;
use App\Services\SelectionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\GradingCriterion;

class SelectionController extends Controller
{
    public function __construct(protected SelectionService $service) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', Selection::class);
        $listMap = ['selected' => 'approved', 'waiting' => 'returned'];
        $records = $this->service->scopedQuery($request->user())
            ->when($request->filled('list'), fn ($q) => $q->where('status', $listMap[$request->string('list')] ?? $request->string('list')))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->when($request->filled('search'), fn ($q) => $q->whereHas('recruitment', fn ($r) => $r->where('tracking_number', 'like', '%' . $request->string('search') . '%')))
            ->paginate(15)->withQueryString();
        return view('selections.index', ['records' => $records, 'statuses' => SelectionService::STATUSES]);
    }

    public function create(Request $request)
    {
        $this->authorize('create', Selection::class);
        $recruitments = \App\Models\Recruitment::visibleTo($request->user())->orderByDesc('id')->limit(200)->get();
        return view('selections.create', compact('recruitments'));
    }

    public function store(StoreSelectionRequest $request)
    {
        $record = $this->service->create($request->validated(), $request->user());
        if ($request->wantsJson()) {
            return response()->json(new \App\Http\Resources\SelectionResource($record), 201);
        }
        return redirect()->route('selections.show', $record)->with('success', __('Record created.'));
    }

    public function show(Selection $selection)
    {
        $this->authorize('view', $selection);
        $selection->load(['recruitment.passportHolder', 'recruitment.stageHistories']);
        return view('selections.show', ['record' => $selection, 'recruitment' => $selection->recruitment, 'statuses' => SelectionService::STATUSES]);
    }

    public function edit(Selection $selection)
    {
        $this->authorize('update', $selection);
        $recruitments = \App\Models\Recruitment::visibleTo(auth()->user())->orderByDesc('id')->limit(200)->get();
        return view('selections.edit', ['record' => $selection, 'recruitments' => $recruitments]);
    }

    public function update(UpdateSelectionRequest $request, Selection $selection)
    {
        $record = $this->service->update($selection, $request->validated(), $request->user());
        if ($request->wantsJson()) {
            return response()->json(new \App\Http\Resources\SelectionResource($record));
        }
        return redirect()->route('selections.show', $selection)->with('success', __('Record updated.'));
    }

    public function changeStatus(ChangeSelectionStatusRequest $request, Selection $selection)
    {
        $this->authorize('changeStatus', $selection);
        $record = $this->service->changeStatus($selection, $request->validated()['status'], $request->validated()['remarks'] ?? null, $request->user());
        if ($request->wantsJson()) {
            return response()->json(new \App\Http\Resources\SelectionResource($record));
        }
        return redirect()->route('selections.show', $selection)->with('success', __('Status updated.'));
    }

    public function download(Request $request, Selection $selection, string $field)
    {
        $this->authorize('download', $selection);
        $path = $this->service->downloadPath($selection, $field);
        abort_if($path === null, 404);
        return Storage::disk('private')->download($path);
    }

    /** Cancel/archive only — never a hard delete of a pipeline record. */
    public function destroy(Request $request, Selection $selection)
    {
        $this->authorize('delete', $selection);
        $this->service->cancel($selection, $request->string('remarks') ?: 'Cancelled', $request->user());
        return redirect()->route('selections.index')->with('success', __('Record cancelled.'));
    }

    public function decide(StoreSelectionRequest $request, Selection $selection)
    {
        $this->authorize('approve', $selection);
        $this->service->decide($selection, $request->validated(), $request->user());
        return redirect()->route('selections.show', $selection)->with('success', __('Selection decision saved.'));
    }

    public function criteria(Request $request)
    {
        $this->authorize('viewAny', Selection::class);
        $criteria = GradingCriterion::orderBy('id')->paginate(20);
        return view('selections.criteria', compact('criteria'));
    }

    public function storeCriterion(Request $request)
    {
        $this->authorize('create', Selection::class);
        $data = $request->validate(['name' => ['required', 'string', 'max:255'], 'description' => ['nullable', 'string'], 'max_score' => ['required', 'numeric', 'min:1'], 'weight' => ['nullable', 'numeric', 'min:0'], 'status' => ['nullable', 'in:active,inactive']]);
        $this->service->createGradingCriterion($data);
        return redirect()->route('selections.criteria')->with('success', __('Grading criterion saved.'));
    }
}
