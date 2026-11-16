<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Country;
use App\Models\User;
use App\Policies\Concerns\IsolationChecks;

/**
 * Country authorisation (Phase 01 section 8).
 *
 * Every check combines the Spatie module permission with branch scope,
 * country scope and ownership. An Agent sees only their own records and a
 * Passport Holder sees only their own record, even by direct URL or API id.
 */
class CountryPolicy
{
    use IsolationChecks;

    public function viewAny(User $user): bool
    {
        return $this->isSuperAdmin($user) || $user->can('core.view');
    }

    public function view(User $user, Country $country): bool
    {
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        return $user->can('core.view') && $this->inCountryScope($user, $country->id);
    }

    public function create(User $user): bool
    {
        return $this->isSuperAdmin($user) || $user->can('core.manage');
    }

    public function update(User $user, Country $country): bool
    {
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        return $user->can('core.manage') && $this->inCountryScope($user, $country->id);
    }

    public function delete(User $user, Country $country): bool
    {
        return $this->isSuperAdmin($user) || $user->can('core.manage');
    }
}
