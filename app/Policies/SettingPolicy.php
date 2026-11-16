<?php

namespace App\Policies;

use App\Models\Setting;
use App\Models\User;

/**
 * Settings are Super Admin only for changes (Phase 01 sections 8 and 9);
 * Admins may view them when granted setting.view.
 */
class SettingPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('setting.view');
    }

    public function view(User $user, Setting $setting): bool
    {
        return $user->can('setting.view');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('Super Admin');
    }

    public function update(User $user, Setting $setting): bool
    {
        return $user->can('setting.edit');
    }

    public function delete(User $user, Setting $setting): bool
    {
        return $user->hasRole('Super Admin');
    }
}
