<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Services\SubLedgerService;
use Illuminate\Http\Request;

/**
 * Sub-ledger views (Phase 25): Agent, Passport Holder, Vendor, Employee.
 * Derived from journal lines — no separate posting tables.
 * Agents see only their own ledger (policy); staff see the due lists.
 */
class SubLedgerController extends Controller
{
    public function __construct(protected SubLedgerService $ledgers) {}

    public function show(Request $request, string $type, int $id)
    {
        $this->authorize('viewSubLedger', [\App\Models\Voucher::class, $type, $id]);

        $ledger = $this->ledgers->ledger($type, $id, $request->only(['from', 'to', 'branch_id']));

        if ($request->wantsJson()) {
            return response()->json(['data' => $ledger]);
        }

        return view('reports.sub-ledgers.show', [
            'ledger' => $ledger, 'type' => $type, 'id' => $id,
            'filters' => $request->only(['from', 'to', 'branch_id']),
        ]);
    }

    public function dueList(Request $request, string $type)
    {
        $this->authorize('viewAny', \App\Models\Voucher::class);

        $rows = $this->ledgers->dueList($type, $request->integer('branch_id') ?: null);

        return view('reports.sub-ledgers.due-list', ['rows' => $rows, 'type' => $type]);
    }
}
