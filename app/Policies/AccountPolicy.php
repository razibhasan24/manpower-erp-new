<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Account;

class AccountPolicy
{
    protected function isFinanceStaff(User $user): bool
    {
        return $user->hasRole('Super Admin') || $user->can('account.view');
    }

    protected function inBranch(User $user, ?int $branchId): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        if ($branchId === null) {
            return true;
        }

        return $user->branch_id === null || (int) $user->branch_id === (int) $branchId;
    }

    public function viewAny(User $user): bool
    {
        return $this->isFinanceStaff($user);
    }

    public function view(User $user, Account $item): bool
    {
        return $this->isFinanceStaff($user) && $this->inBranch($user, $item->branch_id ?? null);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('Super Admin') || $user->can('account.create');
    }

    public function update(User $user, Account $item): bool
    {
        return ($user->hasRole('Super Admin') || $user->can('account.update'))
            && $this->inBranch($user, $item->branch_id ?? null);
    }

    public function delete(User $user, Account $item): bool
    {
        return ($user->hasRole('Super Admin') || $user->can('account.delete'))
            && $this->inBranch($user, $item->branch_id ?? null);
    }
}
