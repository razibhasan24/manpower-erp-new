<?php

use Database\Seeders\RolePermissionSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

/**
 * Non-destructive permission sync for navigation/HRM/accounting screens.
 *
 * The original seeder created permission names for some route middleware
 * that it never actually defined (department.*, account.*, payroll.pay,
 * voucher.reverse, ...), so those screens returned 403 for every role
 * except Super Admin. This migration adds the missing permissions and
 * GRANTS them to the intended roles. It never removes a permission or a
 * role, and it never touches business data.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('permissions') || ! Schema::hasTable('roles')) {
            return;
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $allNames = [];

        foreach (RolePermissionSeeder::MODULE_ACTIONS as $module => $actions) {
            foreach ($actions as $action) {
                $permission = Permission::firstOrCreate([
                    'name' => "{$module}.{$action}",
                    'guard_name' => 'web',
                ]);
                $allNames[] = $permission->name;
            }
        }

        $allNames = array_values(array_unique($allNames));

        foreach (RolePermissionSeeder::ROLE_PERMISSIONS as $roleName => $spec) {
            // On a fresh install the roles are created by RolePermissionSeeder
            // (which runs after migrations). This migration only updates roles
            // that already exist, so it never creates a role with a partial
            // permission set.
            $role = Role::where('name', $roleName)->first();

            if ($role === null) {
                continue;
            }

            if ($spec === ['all']) {
                $role->givePermissionTo($allNames);
                continue;
            }

            if (isset($spec['all-except'])) {
                $role->givePermissionTo(array_values(array_diff($allNames, $spec['all-except'])));
                continue;
            }

            $role->givePermissionTo(array_values(array_intersect($allNames, $spec)));
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function down(): void
    {
        // Permissions and role assignments are intentionally retained.
    }
};
