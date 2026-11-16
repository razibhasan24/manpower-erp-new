<?php

declare(strict_types=1);

namespace App\Policies\Concerns;

use App\Models\User;

/**
 * Shared isolation helpers for Group B policies (Phases 08-12).
 *
 * Implements Phase 01 section 8: Super Admin sees everything; everyone else
 * is limited by branch scope, country scope, agent ownership and
 * passport-holder self-ownership. These helpers never grant access on
 * their own - every policy method also requires the module permission.
 */
trait IsolationChecks
{
    protected function isSuperAdmin(User $user): bool
    {
        return $user->hasRole('Super Admin');
    }

    /** @return int[] */
    protected function userBranchIds(User $user): array
    {
        if ($this->isSuperAdmin($user)) {
            return [];
        }

        return $user->branches()->pluck('branches.id')->all();
    }

    /** @return int[] */
    protected function userCountryIds(User $user): array
    {
        if ($this->isSuperAdmin($user)) {
            return [];
        }

        return $user->countries()->pluck('countries.id')->all();
    }

    protected function inBranchScope(User $user, ?int $branchId): bool
    {
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        if ($branchId === null) {
            return false;
        }

        return in_array($branchId, $this->userBranchIds($user), true);
    }

    protected function inCountryScope(User $user, ?int $countryId): bool
    {
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        if ($countryId === null) {
            return false;
        }

        return in_array($countryId, $this->userCountryIds($user), true);
    }

    protected function ownsAgentRecord(User $user, ?int $agentId): bool
    {
        if ($agentId === null) {
            return false;
        }

        return $user->agent !== null && (int) $user->agent->id === (int) $agentId;
    }

    protected function isSelfPassportHolder(User $user, ?int $passportHolderId): bool
    {
        if ($passportHolderId === null) {
            return false;
        }

        return $user->passportHolder !== null && (int) $user->passportHolder->id === (int) $passportHolderId;
    }

    protected function isBranchManagerOf(User $user, ?int $managerId): bool
    {
        return $managerId !== null && (int) $managerId === (int) $user->id;
    }
}
