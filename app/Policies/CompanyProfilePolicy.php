<?php

namespace App\Policies;

use App\Models\CompanyProfile;
use App\Models\User;

/**
 * White-label profile: viewing needs setting.view; changing branding is
 * a Super Admin action (Phase 01 section 9).
 */
class CompanyProfilePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('setting.view');
    }

    public function view(User $user, CompanyProfile $profile): bool
    {
        return $user->can('setting.view');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('Super Admin');
    }

    public function update(User $user, CompanyProfile $profile): bool
    {
        return $user->hasRole('Super Admin');
    }

    public function delete(User $user, CompanyProfile $profile): bool
    {
        return false; // the active profile is never deleted
    }
}
