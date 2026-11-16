<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Designation;

class DesignationPolicy
{
    protected function isFinanceStaff(User $user): bool
    {
        return $user->hasRole('Super Admin') || $user->can('designation.view');
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

    public function view(User $user, Designation $item): bool
    {
        return $this->isFinanceStaff($user) && $this->inBranch($user, $item->branch_id ?? null);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('Super Admin') || $user->can('designation.create');
    }

    public function update(User $user, Designation $item): bool
    {
        return ($user->hasRole('Super Admin') || $user->can('designation.update'))
            && $this->inBranch($user, $item->branch_id ?? null);
    }

    public function delete(User $user, Designation $item): bool
    {
        return ($user->hasRole('Super Admin') || $user->can('designation.delete'))
            && $this->inBranch($user, $item->branch_id ?? null);
    }
}
