<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Sub-ledgers (Phase 25, Phase 01 §6.4). Sub-ledgers are DERIVED from
 * journal_entry_lines filtered by sub_ledger_type + sub_ledger_id — there
 * are no separate posting tables, so a sub-ledger can never drift from
 * the journal. Types: agent, passport_holder, vendor, employee.
 * Only POSTED vouchers contribute.
 */
class SubLedgerService
{
    public const TYPES = ['agent', 'passport_holder', 'vendor', 'employee'];

    /** Chronological ledger lines for one party, with running balance. */
    public function ledger(string $type, int $id, array $filters = []): array
    {
        $this->assertType($type);

        $opening = $this->balanceBefore($type, $id, $filters['from'] ?? null);

        $rows = $this->baseQuery($type, $id)
            ->when($filters['from'] ?? null, fn ($q, $d) => $q->whereDate('journal_entries.entry_date', '>=', $d))
            ->when($filters['to'] ?? null, fn ($q, $d) => $q->whereDate('journal_entries.entry_date', '<=', $d))
            ->when($filters['branch_id'] ?? null, fn ($q, $b) => $q->where('journal_entries.branch_id', $b))
            ->orderBy('journal_entries.entry_date')
            ->orderBy('journal_entry_lines.id')
            ->get();

        $running = $opening;
        $lines = $rows->map(function ($row) use (&$running) {
            // Receivable-style ledgers: debit increases what the party owes us.
            $running = round($running + (float) $row->debit - (float) $row->credit, 2);

            return [
                'date' => $row->entry_date,
                'voucher_number' => $row->voucher_number,
                'voucher_type' => $row->voucher_type,
                'account' => $row->account_name,
                'narration' => $row->narration,
                'debit' => (float) $row->debit,
                'credit' => (float) $row->credit,
                'balance' => $running,
            ];
        })->all();

        return [
            'type' => $type,
            'id' => $id,
            'opening_balance' => $opening,
            'closing_balance' => $running,
            'total_debit' => round($rows->sum('debit'), 2),
            'total_credit' => round($rows->sum('credit'), 2),
            'lines' => $lines,
        ];
    }

    /** Current balance (debit - credit) for one party, all time. */
    public function balance(string $type, int $id): float
    {
        $this->assertType($type);

        $totals = $this->baseQuery($type, $id)
            ->selectRaw('COALESCE(SUM(journal_entry_lines.debit),0) as debit, COALESCE(SUM(journal_entry_lines.credit),0) as credit')
            ->first();

        return round((float) ($totals->debit ?? 0) - (float) ($totals->credit ?? 0), 2);
    }

    /** Everyone with an outstanding balance in one sub-ledger type (due report). */
    public function dueList(string $type, ?int $branchId = null): Collection
    {
        $this->assertType($type);

        return $this->baseQuery($type, null)
            ->when($branchId, fn ($q) => $q->where('journal_entries.branch_id', $branchId))
            ->selectRaw('journal_entry_lines.sub_ledger_id as party_id,
                COALESCE(SUM(journal_entry_lines.debit),0) as total_debit,
                COALESCE(SUM(journal_entry_lines.credit),0) as total_credit,
                COALESCE(SUM(journal_entry_lines.debit),0) - COALESCE(SUM(journal_entry_lines.credit),0) as balance')
            ->groupBy('journal_entry_lines.sub_ledger_id')
            ->havingRaw('balance <> 0')
            ->orderByDesc('balance')
            ->get();
    }

    protected function baseQuery(string $type, ?int $id)
    {
        return DB::table('journal_entry_lines')
            ->join('journal_entries', 'journal_entries.id', '=', 'journal_entry_lines.journal_entry_id')
            ->join('vouchers', 'vouchers.id', '=', 'journal_entries.voucher_id')
            ->join('accounts', 'accounts.id', '=', 'journal_entry_lines.account_id')
            ->where('vouchers.status', 'posted')
            ->where('journal_entry_lines.sub_ledger_type', $type)
            ->when($id !== null, fn ($q) => $q->where('journal_entry_lines.sub_ledger_id', $id))
            ->select(
                'journal_entry_lines.*',
                'journal_entries.entry_date',
                'journal_entries.branch_id',
                'vouchers.voucher_number',
                'vouchers.voucher_type',
                'accounts.name as account_name',
                'accounts.code as account_code'
            );
    }

    protected function balanceBefore(string $type, int $id, ?string $from): float
    {
        if (! $from) {
            return 0.0;
        }

        $totals = $this->baseQuery($type, $id)
            ->whereDate('journal_entries.entry_date', '<', $from)
            ->selectRaw('COALESCE(SUM(journal_entry_lines.debit),0) as debit, COALESCE(SUM(journal_entry_lines.credit),0) as credit')
            ->first();

        return round((float) ($totals->debit ?? 0) - (float) ($totals->credit ?? 0), 2);
    }

    protected function assertType(string $type): void
    {
        if (! in_array($type, self::TYPES, true)) {
            throw new \InvalidArgumentException("Unknown sub-ledger type: {$type}");
        }
    }
}
