<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Http\Resources\JournalEntryResource;
use App\Models\JournalEntry;
use Illuminate\Http\Request;

/**
 * Journal entries are READ-ONLY (Phase 24): they are created only by
 * VoucherService when a voucher posts, and corrected only by reversal.
 * There is no create/edit/delete action here by design.
 */
class JournalEntryController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', JournalEntry::class);

        $items = JournalEntry::with(['voucher', 'lines.account'])
            ->when($request->filled('from'), fn ($q) => $q->whereDate('entry_date', '>=', $request->from))
            ->when($request->filled('to'), fn ($q) => $q->whereDate('entry_date', '<=', $request->to))
            ->when($request->filled('branch_id'), fn ($q) => $q->where('branch_id', $request->branch_id))
            ->orderByDesc('entry_date')->orderByDesc('id')
            ->paginate(20)->withQueryString();

        if ($request->wantsJson()) {
            return JournalEntryResource::collection($items);
        }

        return view('accounting.journal-entries.index', compact('items'));
    }

    public function show(JournalEntry $journalEntry)
    {
        $this->authorize('view', $journalEntry);
        $journalEntry->load(['voucher', 'lines.account']);

        if (request()->wantsJson()) {
            return new JournalEntryResource($journalEntry);
        }

        return view('accounting.journal-entries.show', ['item' => $journalEntry]);
    }
}
