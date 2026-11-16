<?php

namespace App\Http\Controllers\Visitor;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVisitorRequest;
use App\Http\Resources\VisitorResource;
use App\Models\Visitor;
use App\Services\VisitorService;
use Illuminate\Http\Request;

/**
 * Visitor management (Phase 27): registration, entry/exit, visitor
 * pass (printable), daily/monthly reports.
 */
class VisitorController extends Controller
{
    public function __construct(protected VisitorService $visitors) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', Visitor::class);

        $items = Visitor::query()
            ->when($request->filled('date'), fn ($q) => $q->whereDate('entry_time', $request->date))
            ->when($request->filled('search'), fn ($q) => $q->where(function ($qq) use ($request) {
                $qq->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('phone', 'like', '%' . $request->search . '%')
                    ->orWhere('pass_number', 'like', '%' . $request->search . '%');
            }))
            ->orderByDesc('entry_time')
            ->paginate(20)->withQueryString();

        if ($request->wantsJson()) {
            return VisitorResource::collection($items);
        }

        return view('visitors.index', compact('items'));
    }

    public function create()
    {
        $this->authorize('create', Visitor::class);

        return view('visitors.create');
    }

    public function store(StoreVisitorRequest $request)
    {
        $this->authorize('create', Visitor::class);

        $visitor = $this->visitors->register($request->validated());

        return redirect()->route('visitors.show', $visitor)
            ->with('success', "Visitor registered. Pass {$visitor->pass_number} issued.");
    }

    public function show(Visitor $visitor)
    {
        $this->authorize('view', $visitor);

        if (request()->wantsJson()) {
            return new VisitorResource($visitor);
        }

        return view('visitors.show', ['item' => $visitor]);
    }

    public function checkOut(Visitor $visitor)
    {
        $this->authorize('update', $visitor);

        $this->visitors->checkOut($visitor);

        return redirect()->route('visitors.show', $visitor)
            ->with('success', 'Visitor checked out.');
    }

    public function pass(Visitor $visitor)
    {
        $this->authorize('view', $visitor);

        return view('visitors.pass', ['item' => $visitor]);
    }

    public function dailyReport(Request $request)
    {
        $this->authorize('viewAny', Visitor::class);
        $date = $request->query('date', now()->toDateString());

        return view('visitors.daily-report', [
            'items' => $this->visitors->dailyReport($date), 'date' => $date,
        ]);
    }

    public function monthlyReport(Request $request)
    {
        $this->authorize('viewAny', Visitor::class);
        $month = $request->query('month', now()->format('Y-m'));

        return view('visitors.monthly-report', [
            'rows' => $this->visitors->monthlyReport($month), 'month' => $month,
        ]);
    }
}
