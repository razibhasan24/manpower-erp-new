<?php

namespace App\Services;

use App\Models\Account;
use App\Models\JournalEntry;
use App\Models\JournalEntryLine;
use App\Models\Payroll;
use App\Models\PaymentMode;
use App\Models\Voucher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * VoucherService — THE accounting core (Phase 24, binding Phase 01 §10).
 *
 * Every financial event: Voucher -> one JournalEntry -> >= 2 JournalEntryLines.
 *   - Total Debit MUST equal Total Credit or nothing is saved (checked
 *     inside a DB transaction; a failure rolls everything back).
 *   - Duplicate posting is rejected via the unique voucher_number and the
 *     idempotency reference (reference_type + reference_id).
 *   - Posted vouchers are IMMUTABLE: no edit/delete. Correction is only
 *     via an authorised reversal voucher referencing the original.
 *   - All amounts are decimal(15,2); comparisons use bcmath at 2dp.
 *
 * Public API (contract — other groups depend on these exact signatures):
 *   post(array $data): Voucher
 *   reverse(Voucher $voucher, ?string $reason = null): Voucher
 *   balanceCheck(array $lines): bool
 *   postPayroll(Payroll $payroll): Voucher
 *   postOperation(string $operation, array $data): Voucher
 *
 * Voucher types: payment, receipt, journal, contra.
 *
 * Financial operations (§21.G) and their debit/credit patterns:
 *   daily_income        Dr Cash/Bank            Cr Income account
 *   daily_expense       Dr Expense account      Cr Cash/Bank
 *   vendor_payment      Dr Vendor Payable       Cr Cash/Bank
 *   agent_due_collection Dr Cash/Bank           Cr Agent Receivable
 *   candidate_payment   Dr Cash/Bank            Cr Candidate Receivable
 *   advance_payment     Dr Advance (Employee/Vendor) Cr Cash/Bank
 *   refund              Dr Income/Receivable    Cr Cash/Bank (money returned)
 *   adjustment          Dr/Cr as supplied       (journal — caller supplies both legs)
 *   bank_deposit        Dr Bank                 Cr Cash in Hand
 *   cash_transfer       Dr Cash (destination)   Cr Cash (source)  [contra]
 *   bank_transfer       Dr Bank (destination)   Cr Bank (source)  [contra]
 */
class VoucherService
{
    public const TYPES = ['payment', 'receipt', 'journal', 'contra'];

    public const OPERATIONS = [
        'daily_income', 'daily_expense', 'vendor_payment', 'agent_due_collection',
        'candidate_payment', 'advance_payment', 'refund', 'adjustment',
        'bank_deposit', 'cash_transfer', 'bank_transfer',
    ];

    /** Operation => voucher type. */
    public const OPERATION_VOUCHER_TYPE = [
        'daily_income' => 'receipt',
        'daily_expense' => 'payment',
        'vendor_payment' => 'payment',
        'agent_due_collection' => 'receipt',
        'candidate_payment' => 'receipt',
        'advance_payment' => 'payment',
        'refund' => 'payment',
        'adjustment' => 'journal',
        'bank_deposit' => 'contra',
        'cash_transfer' => 'contra',
        'bank_transfer' => 'contra',
    ];

    public function __construct(protected ChartOfAccountsService $chart) {}

    /**
     * Post a voucher. $data:
     *   voucher_type, voucher_date, branch_id, narration,
     *   reference_type, reference_id (optional idempotency reference),
     *   lines: [{account_id, debit, credit, sub_ledger_type?, sub_ledger_id?, narration?}]
     */
    public function post(array $data): Voucher
    {
        $lines = $data['lines'] ?? [];

        if (! in_array($data['voucher_type'] ?? null, self::TYPES, true)) {
            throw ValidationException::withMessages(['voucher_type' => 'Invalid voucher type.']);
        }

        if (! $this->balanceCheck($lines)) {
            throw ValidationException::withMessages([
                'lines' => 'Total debit must equal total credit and every line must have exactly one side greater than zero.',
            ]);
        }

        $this->assertReferenceNotPosted($data['reference_type'] ?? null, $data['reference_id'] ?? null);

        return DB::transaction(function () use ($data, $lines) {
            $totalDebit = $this->sumSide($lines, 'debit');
            $totalCredit = $this->sumSide($lines, 'credit');

            $voucher = Voucher::create([
                'voucher_number' => $this->nextVoucherNumber($data['voucher_type'], $data['voucher_date']),
                'voucher_type' => $data['voucher_type'],
                'voucher_date' => $data['voucher_date'],
                'branch_id' => $data['branch_id'] ?? Auth::user()?->branch_id,
                'reference_type' => $data['reference_type'] ?? null,
                'reference_id' => $data['reference_id'] ?? null,
                'total_debit' => $totalDebit,
                'total_credit' => $totalCredit,
                'narration' => $data['narration'] ?? null,
                'status' => 'posted',
                'posted_by' => Auth::id(),
                'posted_at' => now(),
            ]);

            $entry = JournalEntry::create([
                'voucher_id' => $voucher->id,
                'entry_date' => $data['voucher_date'],
                'narration' => $data['narration'] ?? null,
                'branch_id' => $voucher->branch_id,
            ]);

            foreach ($lines as $line) {
                JournalEntryLine::create([
                    'journal_entry_id' => $entry->id,
                    'account_id' => $line['account_id'],
                    'debit' => $this->money($line['debit'] ?? 0),
                    'credit' => $this->money($line['credit'] ?? 0),
                    'sub_ledger_type' => $line['sub_ledger_type'] ?? null,
                    'sub_ledger_id' => $line['sub_ledger_id'] ?? null,
                    'narration' => $line['narration'] ?? $data['narration'] ?? null,
                ]);
            }

            return $voucher->fresh(['journalEntry.lines']);
        });
    }

    /** Store a balanced voucher without creating journal entries or affecting ledgers. */
    public function createDraft(array $data): Voucher
    {
        $lines = $data['lines'] ?? [];
        if (! $this->balanceCheck($lines)) {
            throw ValidationException::withMessages(['lines' => 'Voucher lines must balance: debit total must equal credit total.']);
        }
        $this->assertPostingAccounts($lines);
        $this->assertVoucherFormat($data['voucher_type'], $lines);

        $voucher = Voucher::create([
            'voucher_number' => $this->nextVoucherNumber($data['voucher_type'], $data['voucher_date']),
            'voucher_type' => $data['voucher_type'],
            'voucher_date' => $data['voucher_date'],
            'branch_id' => $data['branch_id'] ?? Auth::user()?->branch_id,
            'payment_mode_id' => $data['payment_mode_id'] ?? null,
            'reference_type' => $data['reference_type'] ?? null,
            'reference_id' => $data['reference_id'] ?? null,
            'total_debit' => $this->sumSide($lines, 'debit'),
            'total_credit' => $this->sumSide($lines, 'credit'),
            'narration' => $data['narration'] ?? null,
            'payee' => $data['payee'] ?? null,
            'draft_lines' => array_values($lines),
            'status' => 'draft',
        ]);
        $voucher->forceFill(['created_by' => Auth::id(), 'updated_by' => Auth::id()])->save();

        return $voucher;
    }

    /** Update an unapproved voucher while keeping it out of the ledger. */
    public function updateDraft(Voucher $voucher, array $data): Voucher
    {
        return DB::transaction(function () use ($voucher, $data) {
            $voucher = Voucher::query()->lockForUpdate()->findOrFail($voucher->id);
            if ($voucher->status !== 'draft') {
                throw ValidationException::withMessages(['voucher' => 'Only draft vouchers can be edited.']);
            }
            $lines = $data['lines'] ?? [];
            if (! $this->balanceCheck($lines)) {
                throw ValidationException::withMessages(['lines' => 'Voucher lines must balance: debit total must equal credit total.']);
            }
            $this->assertPostingAccounts($lines);
            $this->assertVoucherFormat($data['voucher_type'], $lines);
            $voucher->update([
                'voucher_type' => $data['voucher_type'],
                'voucher_date' => $data['voucher_date'],
                'branch_id' => $data['branch_id'] ?? $voucher->branch_id,
                'payment_mode_id' => $data['payment_mode_id'] ?? null,
                'reference_type' => $data['reference_type'] ?? null,
                'reference_id' => $data['reference_id'] ?? null,
                'total_debit' => $this->sumSide($lines, 'debit'),
                'total_credit' => $this->sumSide($lines, 'credit'),
                'narration' => $data['narration'] ?? null,
                'payee' => $data['payee'] ?? null,
                'draft_lines' => array_values($lines),
            ]);
            $voucher->forceFill(['updated_by' => Auth::id()])->save();
            return $voucher->fresh();
        });
    }

    /** Approval is the only manual-voucher path that creates journal entries. */
    public function approveDraft(Voucher $voucher): Voucher
    {
        return DB::transaction(function () use ($voucher) {
            $voucher = Voucher::query()->lockForUpdate()->findOrFail($voucher->id);
            if ($voucher->status !== 'draft') {
                throw ValidationException::withMessages(['voucher' => 'Only draft vouchers can be approved.']);
            }
            $lines = $voucher->draft_lines ?? [];
            if (! $this->balanceCheck($lines)) {
                throw ValidationException::withMessages(['lines' => 'This draft is not balanced and cannot be approved.']);
            }
            $this->assertPostingAccounts($lines);
            $this->assertVoucherFormat($voucher->voucher_type, $lines);
            $this->assertReferenceNotPosted($voucher->reference_type, $voucher->reference_id);

            $entry = JournalEntry::create([
                'voucher_id' => $voucher->id,
                'entry_date' => $voucher->voucher_date,
                'narration' => $voucher->narration,
                'branch_id' => $voucher->branch_id,
                'created_by' => Auth::id(),
            ]);
            $entry->forceFill(['created_by' => Auth::id()])->save();
            foreach ($lines as $line) {
                JournalEntryLine::create([
                    'journal_entry_id' => $entry->id,
                    'account_id' => $line['account_id'],
                    'debit' => $this->money($line['debit'] ?? 0),
                    'credit' => $this->money($line['credit'] ?? 0),
                    'sub_ledger_type' => $line['sub_ledger_type'] ?? null,
                    'sub_ledger_id' => $line['sub_ledger_id'] ?? null,
                    'narration' => $line['narration'] ?? $voucher->narration,
                ]);
            }
            $voucher->update([
                'status' => 'posted',
                'posted_by' => Auth::id(),
                'posted_at' => now(),
                'draft_lines' => null,
            ]);
            $voucher->forceFill(['updated_by' => Auth::id()])->save();

            return $voucher->fresh(['journalEntry.lines.account']);
        });
    }

    public function rejectDraft(Voucher $voucher): Voucher
    {
        return DB::transaction(function () use ($voucher) {
            $voucher = Voucher::query()->lockForUpdate()->findOrFail($voucher->id);
            if ($voucher->status !== 'draft') {
                throw ValidationException::withMessages(['voucher' => 'Only draft vouchers can be rejected.']);
            }
            $voucher->update(['status' => 'cancelled']);
            $voucher->forceFill(['updated_by' => Auth::id()])->save();
            return $voucher->fresh();
        });
    }

    /** Financial operation form submits a draft; actual posting waits for approval. */
    public function createDraftOperation(string $operation, array $data): Voucher
    {
        if (! in_array($operation, self::OPERATIONS, true)) {
            throw ValidationException::withMessages(['operation' => 'Unknown financial operation.']);
        }
        if ($operation === 'adjustment') {
            return $this->createDraft(array_merge($data, ['voucher_type' => 'journal']));
        }
        $debitAccount = $this->resolveAccount($data, 'debit');
        $creditAccount = $this->resolveAccount($data, 'credit');
        $amount = $this->money($data['amount'] ?? 0);
        if ($amount <= 0) {
            throw ValidationException::withMessages(['amount' => 'Amount must be greater than zero.']);
        }
        $subType = $data['sub_ledger_type'] ?? null;
        $subId = $data['sub_ledger_id'] ?? null;
        return $this->createDraft([
            'voucher_type' => self::OPERATION_VOUCHER_TYPE[$operation],
            'voucher_date' => $data['voucher_date'],
            'branch_id' => $data['branch_id'] ?? Auth::user()?->branch_id,
            'narration' => $data['narration'] ?? $operation,
            'lines' => [
                ['account_id' => $debitAccount->id, 'debit' => $amount, 'credit' => 0, 'sub_ledger_type' => $subType, 'sub_ledger_id' => $subId],
                ['account_id' => $creditAccount->id, 'debit' => 0, 'credit' => $amount, 'sub_ledger_type' => $subType, 'sub_ledger_id' => $subId],
            ],
        ]);
    }

    protected function assertPostingAccounts(array $lines): void
    {
        foreach ($lines as $index => $line) {
            $account = Account::query()->where('status', 'active')->find($line['account_id'] ?? null);
            if (! $account) {
                throw ValidationException::withMessages(["lines.{$index}.account_id" => 'Select an active posting account.']);
            }
        }
    }

    protected function assertVoucherFormat(string $type, array $lines): void
    {
        $accounts = Account::whereIn('id', collect($lines)->pluck('account_id')->filter())->get()->keyBy('id');
        $cashBankIds = PaymentMode::where('status', 'active')->pluck('account_id')->map(fn ($id) => (int) $id)->all();
        $isCashBank = fn ($line) => in_array((int) ($line['account_id'] ?? 0), $cashBankIds, true);

        if ($type === 'contra') {
            foreach ($lines as $index => $line) {
                if (! $isCashBank($line)) {
                    throw ValidationException::withMessages(["lines.{$index}.account_id" => 'Contra vouchers can only transfer between Cash / Bank accounts.']);
                }
            }
        }
        if ($type === 'payment' && ! collect($lines)->contains(fn ($line) => (float) ($line['credit'] ?? 0) > 0 && $isCashBank($line))) {
            throw ValidationException::withMessages(['lines' => 'A payment voucher must credit a Cash / Bank account.']);
        }
        if ($type === 'receipt' && ! collect($lines)->contains(fn ($line) => (float) ($line['debit'] ?? 0) > 0 && $isCashBank($line))) {
            throw ValidationException::withMessages(['lines' => 'A receipt voucher must debit a Cash / Bank account.']);
        }
    }

    /**
     * Reverse a posted voucher. Creates a new posted Journal voucher whose
     * lines are the mirror image (debit <-> credit) of the original, marks
     * the original `reversed` and links both ways. The original is never
     * edited or deleted.
     */
    public function reverse(Voucher $voucher, ?string $reason = null): Voucher
    {
        if ($voucher->status !== 'posted') {
            throw ValidationException::withMessages([
                'voucher' => 'Only a posted voucher can be reversed.',
            ]);
        }

        if ($voucher->reversal_voucher_id) {
            throw ValidationException::withMessages([
                'voucher' => 'This voucher has already been reversed.',
            ]);
        }

        $entry = $voucher->journalEntry()->with('lines')->firstOrFail();

        $lines = $entry->lines->map(fn (JournalEntryLine $line) => [
            'account_id' => $line->account_id,
            'debit' => $line->credit,
            'credit' => $line->debit,
            'sub_ledger_type' => $line->sub_ledger_type,
            'sub_ledger_id' => $line->sub_ledger_id,
            'narration' => 'Reversal of ' . $voucher->voucher_number . ($reason ? ' — ' . $reason : ''),
        ])->all();

        $reversal = $this->post([
            'voucher_type' => 'journal',
            'voucher_date' => now()->toDateString(),
            'branch_id' => $voucher->branch_id,
            'reference_type' => Voucher::class,
            'reference_id' => $voucher->id,
            'narration' => 'Reversal of voucher ' . $voucher->voucher_number . ($reason ? ' — ' . $reason : ''),
            'lines' => $lines,
        ]);

        DB::transaction(function () use ($voucher, $reversal) {
            $voucher->update([
                'status' => 'reversed',
                'reversed_by' => Auth::id(),
                'reversal_voucher_id' => $reversal->id,
            ]);
        });

        return $reversal->fresh(['journalEntry.lines']);
    }

    /** Pure balance check: >= 2 lines, each line exactly one side > 0, totals equal at 2dp. */
    public function balanceCheck(array $lines): bool
    {
        if (count($lines) < 2) {
            return false;
        }

        foreach ($lines as $line) {
            $debit = $this->money($line['debit'] ?? 0);
            $credit = $this->money($line['credit'] ?? 0);

            if (($debit > 0 && $credit > 0) || ($debit == 0 && $credit == 0)) {
                return false;
            }

            if ($debit < 0 || $credit < 0) {
                return false;
            }

            if (empty($line['account_id'])) {
                return false;
            }
        }

        return bccomp((string) $this->sumSide($lines, 'debit'), (string) $this->sumSide($lines, 'credit'), 2) === 0;
    }

    /**
     * Post payroll as a Journal Voucher (Phase 26). Payroll and accounting
     * can never disagree because payroll IS a voucher.
     * Pattern: Dr Salary Expense (gross + bonus + overtime)
     *          Cr Salary Payable (net)
     *          Cr Deductions Payable (deductions, when > 0)
     * Idempotency reference: Payroll class + payroll id.
     */
    public function postPayroll(Payroll $payroll): Voucher
    {
        $this->assertReferenceNotPosted(Payroll::class, $payroll->id);

        $branchId = $payroll->employee->branch_id ?? Auth::user()?->branch_id;
        $gross = $this->money(($payroll->gross ?? 0) + ($payroll->bonus ?? 0) + ($payroll->overtime ?? 0));
        $net = $this->money($payroll->net ?? 0);
        $deductions = $this->money($payroll->deductions ?? 0);

        $lines = [
            [
                'account_id' => $this->chart->requireByCode('5100')->id,
                'debit' => $gross, 'credit' => 0,
                'sub_ledger_type' => 'employee', 'sub_ledger_id' => $payroll->employee_id,
                'narration' => "Salary expense — {$payroll->month}",
            ],
            [
                'account_id' => $this->chart->requireByCode('2120')->id,
                'debit' => 0, 'credit' => $net,
                'sub_ledger_type' => 'employee', 'sub_ledger_id' => $payroll->employee_id,
                'narration' => "Salary payable — {$payroll->month}",
            ],
        ];

        if ($deductions > 0) {
            $lines[] = [
                'account_id' => $this->chart->requireByCode('2130')->id,
                'debit' => 0, 'credit' => $deductions,
                'sub_ledger_type' => 'employee', 'sub_ledger_id' => $payroll->employee_id,
                'narration' => "Payroll deductions — {$payroll->month}",
            ];
        }

        return $this->post([
            'voucher_type' => 'journal',
            'voucher_date' => now()->toDateString(),
            'branch_id' => $branchId,
            'reference_type' => Payroll::class,
            'reference_id' => $payroll->id,
            'narration' => "Payroll {$payroll->month} — employee #{$payroll->employee_id}",
            'lines' => $lines,
        ]);
    }

    /**
     * Post one of the §21.G financial operations. $data:
     *   amount, voucher_date, branch_id, narration,
     *   debit_account_id | debit_account_code, credit_account_id | credit_account_code,
     *   sub_ledger_type?, sub_ledger_id?, reference_type?, reference_id?
     * For `adjustment` pass `lines` directly instead of the two accounts.
     */
    public function postOperation(string $operation, array $data): Voucher
    {
        if (! in_array($operation, self::OPERATIONS, true)) {
            throw ValidationException::withMessages(['operation' => 'Unknown financial operation.']);
        }

        if ($operation === 'adjustment') {
            return $this->post(array_merge($data, ['voucher_type' => 'journal']));
        }

        $debitAccount = $this->resolveAccount($data, 'debit');
        $creditAccount = $this->resolveAccount($data, 'credit');
        $amount = $this->money($data['amount'] ?? 0);

        if ($amount <= 0) {
            throw ValidationException::withMessages(['amount' => 'Amount must be greater than zero.']);
        }

        $subType = $data['sub_ledger_type'] ?? null;
        $subId = $data['sub_ledger_id'] ?? null;

        return $this->post([
            'voucher_type' => self::OPERATION_VOUCHER_TYPE[$operation],
            'voucher_date' => $data['voucher_date'] ?? now()->toDateString(),
            'branch_id' => $data['branch_id'] ?? Auth::user()?->branch_id,
            'reference_type' => $data['reference_type'] ?? null,
            'reference_id' => $data['reference_id'] ?? null,
            'narration' => $data['narration'] ?? $operation,
            'lines' => [
                [
                    'account_id' => $debitAccount->id, 'debit' => $amount, 'credit' => 0,
                    'sub_ledger_type' => $subType, 'sub_ledger_id' => $subId,
                    'narration' => $data['narration'] ?? $operation,
                ],
                [
                    'account_id' => $creditAccount->id, 'debit' => 0, 'credit' => $amount,
                    'sub_ledger_type' => $subType, 'sub_ledger_id' => $subId,
                    'narration' => $data['narration'] ?? $operation,
                ],
            ],
        ]);
    }

    /** Pay a posted payroll's net salary: Dr Salary Payable, Cr Cash/Bank. */
    public function paySalary(Payroll $payroll, int $cashOrBankAccountId, ?string $date = null): Voucher
    {
        return $this->post([
            'voucher_type' => 'payment',
            'voucher_date' => $date ?? now()->toDateString(),
            'branch_id' => $payroll->employee->branch_id ?? Auth::user()?->branch_id,
            'reference_type' => Payroll::class,
            'reference_id' => $payroll->id,
            'narration' => "Salary payment {$payroll->month} — employee #{$payroll->employee_id}",
            'lines' => [
                [
                    'account_id' => $this->chart->requireByCode('2120')->id,
                    'debit' => $this->money($payroll->net ?? 0), 'credit' => 0,
                    'sub_ledger_type' => 'employee', 'sub_ledger_id' => $payroll->employee_id,
                ],
                [
                    'account_id' => $cashOrBankAccountId,
                    'debit' => 0, 'credit' => $this->money($payroll->net ?? 0),
                    'sub_ledger_type' => 'employee', 'sub_ledger_id' => $payroll->employee_id,
                ],
            ],
        ]);
    }

    // ---- helpers ------------------------------------------------------

    protected function resolveAccount(array $data, string $side): Account
    {
        if (! empty($data["{$side}_account_id"])) {
            return Account::findOrFail($data["{$side}_account_id"]);
        }

        if (! empty($data["{$side}_account_code"])) {
            return $this->chart->requireByCode($data["{$side}_account_code"]);
        }

        throw ValidationException::withMessages(["{$side}_account" => "A {$side} account (id or code) is required."]);
    }

    protected function assertReferenceNotPosted(?string $type, mixed $id): void
    {
        if (! $type || ! $id) {
            return;
        }

        $exists = Voucher::where('reference_type', $type)
            ->where('reference_id', $id)
            ->where('status', 'posted')
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'reference' => 'This reference has already been posted. Duplicate posting is not allowed.',
            ]);
        }
    }

    protected function nextVoucherNumber(string $type, string $date): string
    {
        $prefix = ['payment' => 'PV', 'receipt' => 'RV', 'journal' => 'JV', 'contra' => 'CV'][$type] ?? 'JV';
        $day = date('Ymd', strtotime($date));

        $count = Voucher::where('voucher_type', $type)
            ->whereDate('voucher_date', $date)
            ->count();

        return sprintf('%s-%s-%04d', $prefix, $day, $count + 1);
    }

    protected function sumSide(array $lines, string $side): float
    {
        $total = '0.00';

        foreach ($lines as $line) {
            $total = bcadd($total, (string) $this->money($line[$side] ?? 0), 2);
        }

        return (float) $total;
    }

    protected function money(mixed $value): float
    {
        return round((float) $value, 2);
    }
}
