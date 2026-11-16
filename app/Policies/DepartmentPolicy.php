<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Department;

class DepartmentPolicy
{
    protected function isFinanceStaff(User $user): bool
    {
        return $user->hasRole('Super Admin') || $user->can('department.view');
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

    public function view(User $user, Department $item): bool
    {
        return $this->isFinanceStaff($user) && $this->inBranch($user, $item->branch_id ?? null);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('Super Admin') || $user->can('department.create');
    }

    public function update(User $user, Department $item): bool
    {
        return ($user->hasRole('Super Admin') || $user->can('department.update'))
            && $this->inBranch($user, $item->branch_id ?? null);
    }

    public function delete(User $user, Department $item): bool
    {
        return ($user->hasRole('Super Admin') || $user->can('department.delete'))
            && $this->inBranch($user, $item->branch_id ?? null);
    }
}
