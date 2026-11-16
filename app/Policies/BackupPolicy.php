<?php

namespace App\Policies;

use App\Models\Backup;
use App\Models\User;

/**
 * BackupPolicy — Phase 29 (prompt §29).
 *
 * Permission names are the seeded names from RolePermissionSeeder:
 * backup.view / create / download / delete / manage. There is NO
 * `backup.restore` permission in the seeder — restore is authorised
 * by `backup.manage` (the "highly authorised" permission: the Admin
 * role does not receive it, only Super Admin does).
 *
 * Download is Super Admin only, enforced here by role, not just by
 * the `backup.download` permission: the seeder grants Admin
 * `backup.download` too, but the task requires download to be
 * Super-Admin-only. If the seeder is ever aligned to that rule, this
 * role check stays correct.
 */
class BackupPolicy
{
    protected function isSuperAdmin(User $user): bool
    {
        return $user->hasRole('Super Admin');
    }

    public function viewAny(User $user): bool
    {
        return $this->isSuperAdmin($user) || $user->can('backup.view');
    }

    public function view(User $user, Backup $backup): bool
    {
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        return $user->can('backup.view') && $backup->created_by === $user->id;
    }

    public function create(User $user): bool
    {
        return $this->isSuperAdmin($user) || $user->can('backup.create');
    }

    public function download(User $user, Backup $backup): bool
    {
        return $this->isSuperAdmin($user);
    }

    public function restore(User $user, Backup $backup): bool
    {
        return $this->isSuperAdmin($user) || $user->can('backup.manage');
    }

    public function delete(User $user, Backup $backup): bool
    {
        return $this->isSuperAdmin($user) || $user->can('backup.delete');
    }
}
