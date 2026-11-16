<?php

namespace App\Policies;

use App\Models\User;

/**
 * User management authorisation (Phase 01 section 8).
 * Super Admin: everything. Admin: whatever user.* permissions they hold.
 * Everyone else: only their own record.
 */
class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('user.view');
    }

    public function view(User $user, User $model): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        if ($user->id === $model->id) {
            return true;
        }

        if (! $user->can('user.view')) {
            return false;
        }

        // Staff see users in their assigned branches only.
        $branchIds = $user->branches()->pluck('branches.id')->all();

        return $branchIds === [] || in_array($model->branch_id, $branchIds, true);
    }

    public function create(User $user): bool
    {
        return $user->can('user.create');
    }

    public function update(User $user, User $model): bool
    {
        if ($user->id === $model->id) {
            return true; // own profile fields; password rules live in ProfileController
        }

        return $user->can('user.edit');
    }

    public function delete(User $user, User $model): bool
    {
        if ($user->id === $model->id) {
            return false; // nobody deletes their own account
        }

        return $user->can('user.delete');
    }

    public function restore(User $user, User $model): bool
    {
        return $user->can('user.edit');
    }

    public function forceDelete(User $user, User $model): bool
    {
        return false; // users are never hard-deleted
    }
}
