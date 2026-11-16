<?php

namespace App\Services;

use App\Models\Account;
use Illuminate\Support\Facades\DB;

/**
 * Account CRUD (Phase 23). Thin wrapper around the model with the
 * Phase 01 §10 rules: codes are unique, a parent must exist, an account
 * with posted entries is never deleted — it is deactivated instead.
 */
class AccountService
{
    public function __construct(protected ChartOfAccountsService $chart) {}

    public function create(array $data): Account
    {
        return DB::transaction(function () use ($data) {
            $data['normal_balance'] = $data['normal_balance']
                ?? $this->defaultNormalBalance((int) $data['account_type_id']);

            return Account::create($data);
        });
    }

    public function update(Account $account, array $data): Account
    {
        return DB::transaction(function () use ($account, $data) {
            // Code and type of an account with postings must not change:
            // history would stop matching the chart.
            if ($this->chart->hasPostedEntries($account)) {
                unset($data['code'], $data['account_type_id']);
            }

            $account->update($data);

            return $account->fresh();
        });
    }

    /** Delete is only a soft-delete of a never-posted account; posted accounts are deactivated. */
    public function delete(Account $account): bool
    {
        if ($this->chart->hasPostedEntries($account)) {
            $this->chart->deactivate($account);

            return false; // not deleted — deactivated instead
        }

        return (bool) $account->delete();
    }

    public function deactivate(Account $account): Account
    {
        return $this->chart->deactivate($account);
    }

    protected function defaultNormalBalance(int $accountTypeId): string
    {
        $type = \App\Models\AccountType::find($accountTypeId);

        if (! $type) {
            return 'debit';
        }

        return in_array(strtolower($type->name), ['liabilities', 'equity', 'income'], true)
            ? 'credit'
            : 'debit';
    }
}
