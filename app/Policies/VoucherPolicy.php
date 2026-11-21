<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Voucher;

/**
 * Voucher policy (Phase 24, §8). Financial modules are Accounts Staff
 * only (plus Super Admin). Agents may view ONLY their own sub-ledger
 * (via viewSubLedger), never vouchers directly.
 */
class VoucherPolicy
{
    protected function finance(User $user): bool
    {
        return $user->hasRole('Super Admin') || $user->can('voucher.view');
    }

    protected function inBranch(User $user, ?int $branchId): bool
    {
        if ($user->hasRole('Super Admin') || $branchId === null) {
            return true;
        }

        return $user->branch_id === null || (int) $user->branch_id === (int) $branchId;
    }

    public function viewAny(User $user): bool
    {
        return $this->finance($user);
    }

    public function view(User $user, Voucher $voucher): bool
    {
        $canView = $this->finance($user)
            || ($voucher->status === 'draft' && (
                $user->can('voucher.approve')
                || $user->can('voucher.edit')
                || (int) $voucher->created_by === (int) $user->id
            ));

        return $canView && $this->inBranch($user, $voucher->branch_id);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('Super Admin') || $user->can('voucher.create');
    }

    public function update(User $user, Voucher $voucher): bool
    {
        // Posted vouchers are immutable — there is no update path at all.
        return ($user->hasRole('Super Admin') || $user->can('voucher.edit'))
            && $voucher->status === 'draft'
            && $this->inBranch($user, $voucher->branch_id);
    }

    public function delete(User $user, Voucher $voucher): bool
    {
        // Posted vouchers are immutable — there is no delete path at all.
        return false;
    }

    public function reverse(User $user, Voucher $voucher): bool
    {
        return ($user->hasRole('Super Admin') || $user->can('voucher.reverse'))
            && $voucher->status === 'posted'
            && $this->inBranch($user, $voucher->branch_id);
    }

    public function approve(User $user, Voucher $voucher): bool
    {
        return ($user->hasRole('Super Admin') || $user->can('voucher.approve'))
            && $voucher->status === 'draft'
            && $this->inBranch($user, $voucher->branch_id);
    }

    public function reject(User $user, Voucher $voucher): bool
    {
        return ($user->hasRole('Super Admin') || $user->can('voucher.reject'))
            && $voucher->status === 'draft'
            && $this->inBranch($user, $voucher->branch_id);
    }

    /**
     * Sub-ledger access: staff with voucher.view, or an Agent viewing
     * their OWN ledger only, or a Passport Holder viewing their own.
     */
    public function viewSubLedger(User $user, string $type, int $id): bool
    {
        if ($this->finance($user)) {
            return true;
        }

        if ($type === 'agent' && $user->hasRole('Agent')) {
            return (int) optional($user->agent)->id === $id;
        }

        if ($type === 'passport_holder' && $user->hasRole('Passport Holder')) {
            return (int) optional($user->passportHolder)->id === $id;
        }

        if ($type === 'employee') {
            return (int) optional($user->employee)->id === $id;
        }

        return false;
    }
}
