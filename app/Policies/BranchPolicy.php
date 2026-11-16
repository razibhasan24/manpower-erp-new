<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Branch;
use App\Models\User;
use App\Policies\Concerns\IsolationChecks;

/**
 * Branch authorisation (Phase 01 section 8).
 *
 * Every check combines the Spatie module permission with branch scope,
 * country scope and ownership. An Agent sees only their own records and a
 * Passport Holder sees only their own record, even by direct URL or API id.
 */
class BranchPolicy
{
    use IsolationChecks;

    public function viewAny(User $user): bool
    {
        return $this->isSuperAdmin($user) || $user->can('branch.view');
    }

    public function view(User $user, Branch $branch): bool
    {
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        if (! $user->can('branch.view')) {
            return false;
        }

        return $this->inBranchScope($user, $branch->id)
            || $this->isBranchManagerOf($user, $branch->manager_id);
    }

    public function create(User $user): bool
    {
        return $this->isSuperAdmin($user) || $user->can('branch.create');
    }

    public function update(User $user, Branch $branch): bool
    {
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        if (! $user->can('branch.edit')) {
            return false;
        }

        return $this->inBranchScope($user, $branch->id)
            || $this->isBranchManagerOf($user, $branch->manager_id);
    }

    public function delete(User $user, Branch $branch): bool
    {
        return $this->isSuperAdmin($user) || $user->can('branch.delete');
    }
}
