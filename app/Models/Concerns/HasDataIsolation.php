<?php

namespace App\Models\Concerns;

use App\Models\User;

/**
 * Shared helpers for the data-isolation query scopes (Phase 01 section 8).
 *
 * Rules implemented by every model's scopeVisibleTo():
 *  - Super Admin sees everything.
 *  - A user with assigned branches/countries is restricted to them.
 *  - An Agent sees only their own records (via their agents row).
 *  - A Passport Holder sees only their own record (via their passport_holders row).
 *  - Staff with no branch/country assignments are not silently restricted;
 *    module access is still gated by Spatie permissions and Policies.
 */
trait HasDataIsolation
{
    protected function isolationSuperAdmin(User $user): bool
    {
        return $user->hasRole('Super Admin');
    }

    /** @return int[] */
    protected function isolationBranchIds(User $user): array
    {
        return $user->branches()->pluck('branches.id')->all();
    }

    /** @return int[] */
    protected function isolationCountryIds(User $user): array
    {
        return $user->countries()->pluck('countries.id')->all();
    }

    protected function isolationAgentId(User $user): ?int
    {
        return $user->agent?->id;
    }

    protected function isolationHolderId(User $user): ?int
    {
        return $user->passportHolder?->id;
    }

    /**
     * Apply the staff branch/country restriction to a query that carries
     * branch_id and/or country_id columns. No-op when nothing is assigned.
     */
    protected function isolationApplyBranchCountry($query, User $user, bool $hasBranch, bool $hasCountry)
    {
        if ($hasBranch) {
            $branchIds = $this->isolationBranchIds($user);
            if ($branchIds !== []) {
                $query->whereIn('branch_id', $branchIds);
            }
        }

        if ($hasCountry) {
            $countryIds = $this->isolationCountryIds($user);
            if ($countryIds !== []) {
                $query->whereIn('country_id', $countryIds);
            }
        }

        return $query;
    }
}
