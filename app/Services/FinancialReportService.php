<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

/**
 * Financial reports (Phase 25). Every report is computed from POSTED
 * journal entries only — there is no second balance anywhere to drift.
 * All amounts are decimal(15,2); currency/timezone come from settings,
 * never hard-coded here.
 */
class FinancialReportService
{
    /** Scope a journal-lines query to posted entries + filters. */
    protected function postedLines(array $filters = [])
    {
        return DB::table('journal_entry_lines')
            ->join('journal_entries', 'journal_entries.id', '=', 'journal_entry_lines.journal_entry_id')
            ->join('vouchers', 'vouchers.id', '=', 'journal_entries.voucher_id')
            ->join('accounts', 'accounts.id', '=', 'journal_entry_lines.account_id')
            ->where('vouchers.status', 'posted')
            ->when($filters['from'] ?? null, fn ($q, $d) => $q->whereDate('journal_entries.entry_date', '>=', $d))
            ->when($filters['to'] ?? null, fn ($q, $d) => $q->whereDate('journal_entries.entry_date', '<=', $d))
            ->when($filters['branch_id'] ?? null, fn ($q, $b) => $q->where('journal_entries.branch_id', $b))
            ->when($filters['account_id'] ?? null, fn ($q, $a) => $q->where('journal_entry_lines.account_id', $a));
    }

    public function trialBalance(array $filters = []): array
    {
        $rows = $this->postedLines($filters)
            ->selectRaw('accounts.id as account_id, accounts.code, accounts.name,
                COALESCE(SUM(journal_entry_lines.debit),0) as debit,
                COALESCE(SUM(journal_entry_lines.credit),0) as credit')
            ->groupBy('accounts.id', 'accounts.code', 'accounts.name')
            ->orderBy('accounts.code')
            ->get();

        $totalDebit = round($rows->sum('debit'), 2);
        $totalCredit = round($rows->sum('credit'), 2);

        return [
            'rows' => $rows->map(fn ($r) => [
                'account_id' => $r->account_id, 'code' => $r->code, 'name' => $r->name,
                'debit' => (float) $r->debit, 'credit' => (float) $r->credit,
                'balance' => round((float) $r->debit - (float) $r->credit, 2),
            ])->all(),
            'total_debit' => $totalDebit,
            'total_credit' => $totalCredit,
            'balanced' => bccomp((string) $totalDebit, (string) $totalCredit, 2) === 0,
        ];
    }

    public function generalLedger(array $filters = []): array
    {
        $accounts = $this->postedLines($filters)
            ->select('accounts.id', 'accounts.code', 'accounts.name', 'accounts.normal_balance')
            ->distinct()->orderBy('accounts.code')->get();

        $result = [];
        foreach ($accounts as $account) {
            $lines = $this->postedLines($filters)
                ->where('journal_entry_lines.account_id', $account->id)
                ->select(
                    'journal_entries.entry_date as date', 'vouchers.voucher_number',
                    'vouchers.voucher_type', 'journal_entry_lines.narration',
                    'journal_entry_lines.debit', 'journal_entry_lines.credit'
                )
                ->orderBy('journal_entries.entry_date')->orderBy('journal_entry_lines.id')->get();

            $running = 0.0;
            $entries = $lines->map(function ($l) use (&$running, $account) {
                $running = round($running + (float) $l->debit - (float) $l->credit, 2);

                return [
                    'date' => $l->date, 'voucher_number' => $l->voucher_number,
                    'voucher_type' => $l->voucher_type, 'narration' => $l->narration,
                    'debit' => (float) $l->debit, 'credit' => (float) $l->credit,
                    'balance' => $account->normal_balance === 'credit' ? -$running : $running,
                ];
            })->all();

            $result[] = [
                'account_id' => $account->id, 'code' => $account->code, 'name' => $account->name,
                'normal_balance' => $account->normal_balance,
                'total_debit' => round($lines->sum('debit'), 2),
                'total_credit' => round($lines->sum('credit'), 2),
                'closing_balance' => $running,
                'lines' => $entries,
            ];
        }

        return $result;
    }

    public function accountLedger(int $accountId, array $filters = []): array
    {
        $filters['account_id'] = $accountId;
        $ledgers = $this->generalLedger($filters);

        return $ledgers[0] ?? [
            'account_id' => $accountId, 'code' => null, 'name' => null,
            'total_debit' => 0.0, 'total_credit' => 0.0, 'closing_balance' => 0.0, 'lines' => [],
        ];
    }

    public function incomeStatement(array $filters = []): array
    {
        $income = $this->sumByType('Income', $filters);
        $expenses = $this->sumByType('Expenses', $filters);

        return [
            'income' => $income,
            'expenses' => $expenses,
            'total_income' => round($income->sum('amount'), 2),
            'total_expenses' => round($expenses->sum('amount'), 2),
            'net_profit' => round($income->sum('amount') - $expenses->sum('amount'), 2),
        ];
    }

    public function balanceSheet(array $filters = []): array
    {
        $assets = $this->sumByType('Assets', $filters);
        $liabilities = $this->sumByType('Liabilities', $filters);
        $equity = $this->sumByType('Equity', $filters);
        $netProfit = $this->incomeStatement($filters)['net_profit'];

        $totalAssets = round($assets->sum('amount'), 2);
        $totalLiabEquity = round($liabilities->sum('amount') + $equity->sum('amount') + $netProfit, 2);

        return [
            'assets' => $assets,
            'liabilities' => $liabilities,
            'equity' => $equity,
            'net_profit' => $netProfit,
            'total_assets' => $totalAssets,
            'total_liabilities_equity' => $totalLiabEquity,
            'balanced' => bccomp((string) $totalAssets, (string) $totalLiabEquity, 2) === 0,
        ];
    }

    /** Cash Book: posted lines on cash accounts (code 1110*). Bank Book: bank accounts (1120*). */
    public function cashBook(array $filters = []): array
    {
        return $this->bookByCodePrefix($filters, '111');
    }

    public function bankBook(array $filters = []): array
    {
        return $this->bookByCodePrefix($filters, '112');
    }

    /** Day Book: every posted voucher in the period, chronological. */
    public function dayBook(array $filters = []): array
    {
        $vouchers = DB::table('vouchers')
            ->where('status', 'posted')
            ->when($filters['from'] ?? null, fn ($q, $d) => $q->whereDate('voucher_date', '>=', $d))
            ->when($filters['to'] ?? null, fn ($q, $d) => $q->whereDate('voucher_date', '<=', $d))
            ->when($filters['branch_id'] ?? null, fn ($q, $b) => $q->where('branch_id', $b))
            ->orderBy('voucher_date')->orderBy('id')
            ->get();

        return [
            'vouchers' => $vouchers->map(fn ($v) => [
                'voucher_number' => $v->voucher_number, 'voucher_type' => $v->voucher_type,
                'voucher_date' => $v->voucher_date, 'narration' => $v->narration,
                'total_debit' => (float) $v->total_debit, 'total_credit' => (float) $v->total_credit,
            ])->all(),
            'total_debit' => round($vouchers->sum('total_debit'), 2),
            'total_credit' => round($vouchers->sum('total_credit'), 2),
            'count' => $vouchers->count(),
        ];
    }

    // ---- helpers ------------------------------------------------------

    /** Net amount per account under one account type. Credit-normal types report credit-debit. */
    protected function sumByType(string $typeName, array $filters = [])
    {
        $creditNormal = in_array($typeName, ['Liabilities', 'Equity', 'Income'], true);

        return $this->postedLines($filters)
            ->join('account_types', 'account_types.id', '=', 'accounts.account_type_id')
            ->where('account_types.name', $typeName)
            ->selectRaw('accounts.id as account_id, accounts.code, accounts.name,
                COALESCE(SUM(journal_entry_lines.debit),0) as debit,
                COALESCE(SUM(journal_entry_lines.credit),0) as credit')
            ->groupBy('accounts.id', 'accounts.code', 'accounts.name')
            ->orderBy('accounts.code')
            ->get()
            ->map(fn ($r) => [
                'account_id' => $r->account_id, 'code' => $r->code, 'name' => $r->name,
                'debit' => (float) $r->debit, 'credit' => (float) $r->credit,
                'amount' => $creditNormal
                    ? round((float) $r->credit - (float) $r->debit, 2)
                    : round((float) $r->debit - (float) $r->credit, 2),
            ]);
    }

    protected function bookByCodePrefix(array $filters, string $prefix): array
    {
        $rows = $this->postedLines($filters)
            ->where('accounts.code', 'like', $prefix . '%')
            ->select(
                'journal_entries.entry_date as date', 'vouchers.voucher_number',
                'vouchers.voucher_type', 'accounts.code as account_code', 'accounts.name as account_name',
                'journal_entry_lines.narration', 'journal_entry_lines.debit', 'journal_entry_lines.credit'
            )
            ->orderBy('journal_entries.entry_date')->orderBy('journal_entry_lines.id')
            ->get();

        $running = 0.0;
        $lines = $rows->map(function ($r) use (&$running) {
            $running = round($running + (float) $r->debit - (float) $r->credit, 2);

            return [
                'date' => $r->date, 'voucher_number' => $r->voucher_number,
                'voucher_type' => $r->voucher_type, 'account' => $r->account_code . ' — ' . $r->account_name,
                'narration' => $r->narration, 'debit' => (float) $r->debit,
                'credit' => (float) $r->credit, 'balance' => $running,
            ];
        })->all();

        return [
            'lines' => $lines,
            'total_debit' => round($rows->sum('debit'), 2),
            'total_credit' => round($rows->sum('credit'), 2),
            'closing_balance' => $running,
        ];
    }
}
