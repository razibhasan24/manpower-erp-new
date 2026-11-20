<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\PassportHolder;
use App\Models\PaymentMode;
use App\Models\Voucher;
use App\Services\ChartOfAccountsService;
use App\Services\SubLedgerService;
use App\Services\VoucherService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

/**
 * Sub-ledger views (Phase 25): Agent, Passport Holder, Vendor, Employee.
 * Derived from journal lines — no separate posting tables.
 * Agents see only their own ledger (policy); staff see the due lists.
 */
class SubLedgerController extends Controller
{
    public function __construct(
        protected SubLedgerService $ledgers,
        protected VoucherService $vouchers,
        protected ChartOfAccountsService $chart,
    ) {}

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
        $partyNames = match ($type) {
            'agent' => \App\Models\Agent::visibleTo($request->user())->whereIn('id', $rows->pluck('party_id'))->pluck('name', 'id'),
            'passport_holder' => \App\Models\PassportHolder::visibleTo($request->user())->whereIn('id', $rows->pluck('party_id'))->pluck('full_name', 'id'),
            default => collect(),
        };

        return view('reports.sub-ledgers.due-list', ['rows' => $rows, 'type' => $type, 'partyNames' => $partyNames]);
    }

    public function transactionForm(Request $request, string $type, int $id, string $action)
    {
        $this->authorize('create', Voucher::class);
        abort_unless($type === 'passport_holder' && in_array($action, ['receive', 'pay'], true), 404);

        $party = PassportHolder::visibleTo($request->user())->findOrFail($id);
        $balance = $this->ledgers->balance($type, $id);
        $amountAvailable = $action === 'receive' ? max($balance, 0) : max(-$balance, 0);
        $paymentModes = PaymentMode::with('account')
            ->where('status', 'active')
            ->whereHas('account', fn ($query) => $query->where('status', 'active'))
            ->orderBy('mode_type')->orderBy('name')->get();

        return view('reports.sub-ledgers.transaction', compact('party', 'type', 'id', 'action', 'balance', 'amountAvailable', 'paymentModes'));
    }

    public function storeTransaction(Request $request, string $type, int $id, string $action)
    {
        $this->authorize('create', Voucher::class);
        abort_unless($type === 'passport_holder' && in_array($action, ['receive', 'pay'], true), 404);

        $data = $request->validate([
            'amount' => ['required', 'numeric', 'gt:0'],
            'payment_mode_id' => ['required', 'integer', Rule::exists('payment_modes', 'id')->where('status', 'active')->whereNull('deleted_at')],
            'voucher_date' => ['required', 'date'],
            'narration' => ['nullable', 'string', 'max:500'],
        ]);

        $party = PassportHolder::visibleTo($request->user())->findOrFail($id);
        $balance = $this->ledgers->balance($type, $id);
        $amountAvailable = $action === 'receive' ? max($balance, 0) : max(-$balance, 0);
        if ((float) $data['amount'] > $amountAvailable) {
            throw ValidationException::withMessages([
                'amount' => 'Amount cannot be greater than the current ' . ($action === 'receive' ? 'due' : 'credit balance') . '.',
            ]);
        }
        $mode = PaymentMode::with('account')->where('status', 'active')->findOrFail($data['payment_mode_id']);
        abort_unless($mode->account && $mode->account->status === 'active', 422, 'The selected Cash/Bank account is inactive.');

        $receivable = $this->chart->requireByCode('1220');
        $amount = round((float) $data['amount'], 2);
        $narration = $data['narration'] ?: ucfirst($action) . " — Passport Holder: {$party->full_name}";
        $isReceipt = $action === 'receive';
        $lines = $isReceipt
            ? [
                ['account_id' => $mode->account_id, 'debit' => $amount, 'credit' => 0],
                ['account_id' => $receivable->id, 'debit' => 0, 'credit' => $amount, 'sub_ledger_type' => $type, 'sub_ledger_id' => $party->id],
            ]
            : [
                ['account_id' => $receivable->id, 'debit' => $amount, 'credit' => 0, 'sub_ledger_type' => $type, 'sub_ledger_id' => $party->id],
                ['account_id' => $mode->account_id, 'debit' => 0, 'credit' => $amount],
            ];

        $voucher = $this->vouchers->post([
            'voucher_type' => $isReceipt ? 'receipt' : 'payment',
            'voucher_date' => $data['voucher_date'],
            'branch_id' => $party->branch_id,
            'narration' => $narration,
            'lines' => $lines,
        ]);

        return redirect()->route('accounting.vouchers.show', $voucher)
            ->with('success', "{$voucher->voucher_number} posted successfully.");
    }
}
