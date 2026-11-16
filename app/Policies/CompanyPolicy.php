<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Company;
use App\Models\User;
use App\Policies\Concerns\IsolationChecks;

/**
 * Company authorisation (Phase 01 section 8).
 *
 * Every check combines the Spatie module permission with branch scope,
 * country scope and ownership. An Agent sees only their own records and a
 * Passport Holder sees only their own record, even by direct URL or API id.
 */
class CompanyPolicy
{
    use IsolationChecks;

    public function viewAny(User $user): bool
    {
        return $this->isSuperAdmin($user) || $user->can('core.view');
    }

    public function view(User $user, Company $company): bool
    {
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        return $user->can('core.view') && $this->inCountryScope($user, $company->country_id);
    }

    public function create(User $user): bool
    {
        return $this->isSuperAdmin($user) || $user->can('core.manage');
    }

    public function update(User $user, Company $company): bool
    {
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        return $user->can('core.manage') && $this->inCountryScope($user, $company->country_id);
    }

    public function delete(User $user, Company $company): bool
    {
        return $this->isSuperAdmin($user) || $user->can('core.manage');
    }
}
