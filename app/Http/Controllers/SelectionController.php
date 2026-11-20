<?php

namespace App\Http\Controllers;

use App\Http\Requests\Selection\ChangeSelectionStatusRequest;
use App\Http\Requests\Selection\StoreSelectionRequest;
use App\Http\Requests\Selection\UpdateSelectionRequest;
use App\Models\Selection;
use App\Models\Recruitment;
use App\Models\GradingCriterion;
use App\Models\Account;
use App\Services\SelectionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SelectionController extends Controller
{
    public function __construct(protected SelectionService $service) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', Selection::class);

        $listMap = [
            'selected' => 'approved',
            'waiting' => 'returned',
        ];

        $list = $request->string('list')->toString();
        $status = $request->string('status')->toString();
        $search = $request->string('search')->toString();

        $records = $this->service->scopedQuery($request->user())
            ->when($list !== '', function ($query) use ($list, $listMap) {
                $query->where('status', $listMap[$list] ?? $list);
            })
            ->when($status !== '', function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when($search !== '', function ($query) use ($search) {
                $query->whereHas('recruitment', function ($recruitmentQuery) use ($search) {
                    $recruitmentQuery->where(
                        'tracking_number',
                        'like',
                        '%' . $search . '%'
                    );
                });
            })
            ->paginate(15)
            ->withQueryString();

        return view('selections.index', [
            'records' => $records,
            'statuses' => SelectionService::STATUSES,
        ]);
    }

    public function create(Request $request)
    {
        $this->authorize('create', Selection::class);

        $recruitments = Recruitment::visibleTo($request->user())
            ->with(['agent', 'passportHolder'])
            ->orderByDesc('id')
            ->limit(200)
            ->get();

        $cashAccounts = Account::whereIn('code', ['1110', '1120'])
            ->where('status', 'active')->orderBy('code')->get();

        return view('selections.create', compact('recruitments', 'cashAccounts'));
    }

    public function store(StoreSelectionRequest $request)
    {
        $record = $this->service->create(
            $request->validated(),
            $request->user()
        );

        if ($request->wantsJson()) {
            return response()->json(
                new \App\Http\Resources\SelectionResource($record),
                201
            );
        }

        return redirect()
            ->route('selections.show', $record)
            ->with('success', __('Record created.'));
    }

    public function show(Selection $selection)
    {
        $this->authorize('view', $selection);

        $selection->load([
            'recruitment.passportHolder',
            'recruitment.stageHistories',
        ]);

        return view('selections.show', [
            'record' => $selection,
            'recruitment' => $selection->recruitment,
            'statuses' => SelectionService::STATUSES,
        ]);
    }

    public function edit(Selection $selection)
    {
        $this->authorize('update', $selection);

        $recruitments = Recruitment::visibleTo(auth()->user())
            ->with(['agent', 'passportHolder'])
            ->orderByDesc('id')
            ->limit(200)
            ->get();

        $cashAccounts = Account::whereIn('code', ['1110', '1120'])
            ->where('status', 'active')->orderBy('code')->get();

        return view('selections.edit', [
            'record' => $selection,
            'recruitments' => $recruitments,
            'cashAccounts' => $cashAccounts,
        ]);
    }

    public function update(UpdateSelectionRequest $request, Selection $selection)
    {
        $record = $this->service->update(
            $selection,
            $request->validated(),
            $request->user()
        );

        if ($request->wantsJson()) {
            return response()->json(
                new \App\Http\Resources\SelectionResource($record)
            );
        }

        return redirect()
            ->route('selections.show', $selection)
            ->with('success', __('Record updated.'));
    }

    public function changeStatus(
        ChangeSelectionStatusRequest $request,
        Selection $selection
    ) {
        $this->authorize('changeStatus', $selection);

        $data = $request->validated();

        $record = $this->service->changeStatus(
            $selection,
            $data['status'],
            $data['remarks'] ?? null,
            $request->user()
        );

        if ($request->wantsJson()) {
            return response()->json(
                new \App\Http\Resources\SelectionResource($record)
            );
        }

        return redirect()
            ->route('selections.show', $selection)
            ->with('success', __('Status updated.'));
    }

    public function download(
        Request $request,
        Selection $selection,
        string $field
    ) {
        $this->authorize('download', $selection);

        $path = $this->service->downloadPath($selection, $field);

        abort_if($path === null, 404);

        return Storage::disk('private')->download($path);
    }

    /**
     * Cancel/archive only — never a hard delete of a pipeline record.
     */
    public function destroy(Request $request, Selection $selection)
    {
        $this->authorize('delete', $selection);

        $remarks = $request->string('remarks')->toString();

        $this->service->cancel(
            $selection,
            $remarks ?: 'Cancelled',
            $request->user()
        );

        return redirect()
            ->route('selections.index')
            ->with('success', __('Record cancelled.'));
    }

    public function decide(
        StoreSelectionRequest $request,
        Selection $selection
    ) {
        $this->authorize('approve', $selection);

        $this->service->decide(
            $selection,
            $request->validated(),
            $request->user()
        );

        return redirect()
            ->route('selections.show', $selection)
            ->with('success', __('Selection decision saved.'));
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

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'max_score' => ['required', 'numeric', 'min:1'],
            'weight' => ['nullable', 'numeric', 'min:0'],
            'status' => ['nullable', 'in:active,inactive'],
        ]);

        $this->service->createGradingCriterion($data);

        return redirect()
            ->route('selections.criteria')
            ->with('success', __('Grading criterion saved.'));
    }
}
