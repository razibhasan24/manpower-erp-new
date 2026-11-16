<?php

namespace App\Services;

use App\Models\Account;
use App\Models\AccountType;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Chart of Accounts — read/build helpers (Phase 23).
 *
 * The chart is hierarchical: every account has a unique code and an
 * optional parent. Codes are hierarchical by convention, e.g.
 * 1000 Assets -> 1100 Cash & Bank -> 1110 Cash in Hand.
 * Normal balance comes from the account type (Assets/Expenses = debit,
 * Liabilities/Equity/Income = credit) unless overridden on the account.
 */
class ChartOfAccountsService
{
    /** @return Collection<int, Account> top-level accounts with children eager-loaded. */
    public function tree(): Collection
    {
        // Accounts carry no branch_id in the Group A schema — the chart is global.
        return Account::with(['children', 'type'])
            ->whereNull('parent_id')
            ->orderBy('code')
            ->get();
    }

    /** Full ordered flat list, for selects. */
    public function flatList(): Collection
    {
        return Account::with('type')->orderBy('code')->get();
    }

    /** Only leaf accounts that may receive postings (posting accounts have no children). */
    public function postingAccounts(): Collection
    {
        $accounts = $this->flatList();
        $parentIds = $accounts->pluck('parent_id')->filter()->unique()->all();

        return $accounts->reject(fn (Account $a) => in_array($a->id, $parentIds, true))
            ->where('status', 'active')
            ->values();
    }

    public function findByCode(string $code): ?Account
    {
        return Account::where('code', $code)->first();
    }

    /** Resolve an account by code, throwing a clear exception when the starter chart is missing it. */
    public function requireByCode(string $code): Account
    {
        $account = $this->findByCode($code);

        if (! $account) {
            throw new \RuntimeException("Chart of accounts is missing required account code {$code}. Run the ChartOfAccountsSeeder.");
        }

        return $account;
    }

    /** True when the account already has posted journal lines (it may then be deactivated, never deleted). */
    public function hasPostedEntries(Account $account): bool
    {
        return $account->lines()->exists()
            || $account->children()->whereHas('lines')->exists();
    }

    /** Balance of one account over posted lines only (debit-normal accounts: debit-credit; credit-normal: credit-debit). */
    public function balance(Account $account, ?string $from = null, ?string $to = null): float
    {
        $totals = $account->lines()
            ->whereHas('journalEntry.voucher', fn ($q) => $q->where('status', 'posted'))
            ->when($from, fn ($q) => $q->whereHas('journalEntry', fn ($e) => $e->whereDate('entry_date', '>=', $from)))
            ->when($to, fn ($q) => $q->whereHas('journalEntry', fn ($e) => $e->whereDate('entry_date', '<=', $to)))
            ->selectRaw('COALESCE(SUM(debit),0) as debit, COALESCE(SUM(credit),0) as credit')
            ->first();

        $debit = (float) ($totals->debit ?? 0);
        $credit = (float) ($totals->credit ?? 0);

        return $account->normal_balance === 'credit' ? round($credit - $debit, 2) : round($debit - $credit, 2);
    }

    /** All five account types, seeded — never user-created at runtime. */
    public function types(): Collection
    {
        return AccountType::orderBy('id')->get();
    }

    /** Deactivate an account (allowed even with postings); deletion is forbidden by policy/service. */
    public function deactivate(Account $account): Account
    {
        return DB::transaction(function () use ($account) {
            $account->update(['status' => 'inactive']);

            return $account->fresh();
        });
    }
}
