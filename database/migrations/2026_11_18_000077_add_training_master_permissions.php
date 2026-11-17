<?php

use Database\Seeders\RolePermissionSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('permissions') || ! Schema::hasTable('roles')) {
            return;
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        foreach (['training-centre', 'training-course'] as $module) {
            foreach (RolePermissionSeeder::MODULE_ACTIONS[$module] as $action) {
                $permission = Permission::firstOrCreate([
                    'name' => "{$module}.{$action}",
                    'guard_name' => 'web',
                ]);

                foreach (RolePermissionSeeder::ROLE_PERMISSIONS as $roleName => $spec) {
                    $granted = $spec === ['all']
                        || (isset($spec['all-except']) && ! in_array($permission->name, $spec['all-except'], true))
                        || in_array($permission->name, $spec, true);

                    if ($granted && ($role = Role::where('name', $roleName)->first()) !== null) {
                        $role->givePermissionTo($permission);
                    }
                }
            }
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function down(): void
    {
        // Keep permissions and assignments so rollback does not revoke user access.
    }
};
