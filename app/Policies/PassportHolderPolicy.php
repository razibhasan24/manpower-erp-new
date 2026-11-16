<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\PassportHolder;
use App\Models\User;
use App\Policies\Concerns\IsolationChecks;

/**
 * Passport Holder authorisation (Phase 01 section 8).
 *
 * Every check combines the Spatie module permission with branch scope,
 * country scope and ownership. An Agent sees only their own records and a
 * Passport Holder sees only their own record, even by direct URL or API id.
 */
class PassportHolderPolicy
{
    use IsolationChecks;

    public function viewAny(User $user): bool
    {
        return $this->isSuperAdmin($user) || $user->can('passport-holder.view');
    }

    public function view(User $user, PassportHolder $passportHolder): bool
    {
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        if ($this->isSelfPassportHolder($user, $passportHolder->id)) {
            return true;
        }

        if ($this->ownsAgentRecord($user, $passportHolder->agent_id)) {
            return true;
        }

        if (! $user->can('passport-holder.view')) {
            return false;
        }

        return $this->inBranchScope($user, $passportHolder->branch_id)
            && $this->inCountryScope($user, $passportHolder->country_id);
    }

    public function create(User $user): bool
    {
        return $this->isSuperAdmin($user) || $user->can('passport-holder.create');
    }

    public function update(User $user, PassportHolder $passportHolder): bool
    {
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        if ($this->ownsAgentRecord($user, $passportHolder->agent_id)) {
            return true;
        }

        if (! $user->can('passport-holder.edit')) {
            return false;
        }

        return $this->inBranchScope($user, $passportHolder->branch_id)
            && $this->inCountryScope($user, $passportHolder->country_id);
    }

    public function delete(User $user, PassportHolder $passportHolder): bool
    {
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        return $user->can('passport-holder.delete')
            && $this->inBranchScope($user, $passportHolder->branch_id)
            && $this->inCountryScope($user, $passportHolder->country_id);
    }

    public function downloadDocument(User $user, PassportHolder $passportHolder): bool
    {
        return $this->view($user, $passportHolder);
    }
}
