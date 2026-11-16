<?php

namespace App\Policies;

use App\Models\Payroll;
use App\Models\User;

/** Payroll policy (Phase 26). Approve/pay are separate permissions from create. */
class PayrollPolicy
{
    protected function hr(User $user): bool
    {
        return $user->hasRole('Super Admin') || $user->can('payroll.view');
    }

    public function viewAny(User $user): bool { return $this->hr($user); }

    public function view(User $user, Payroll $payroll): bool
    {
        if (! $this->hr($user)) {
            return false;
        }

        $branchId = $payroll->employee->branch_id ?? null;

        return $user->branch_id === null || $branchId === null || (int) $user->branch_id === (int) $branchId;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('Super Admin') || $user->can('payroll.create');
    }

    public function update(User $user, Payroll $payroll): bool
    {
        return ($user->hasRole('Super Admin') || $user->can('payroll.update'))
            && $payroll->status === 'draft';
    }

    public function delete(User $user, Payroll $payroll): bool
    {
        return ($user->hasRole('Super Admin') || $user->can('payroll.delete'))
            && $payroll->status === 'draft';
    }

    public function approve(User $user, Payroll $payroll): bool
    {
        return ($user->hasRole('Super Admin') || $user->can('payroll.approve'))
            && $payroll->status === 'draft';
    }

    public function pay(User $user, Payroll $payroll): bool
    {
        return ($user->hasRole('Super Admin') || $user->can('payroll.pay'))
            && $payroll->status === 'approved';
    }
}
