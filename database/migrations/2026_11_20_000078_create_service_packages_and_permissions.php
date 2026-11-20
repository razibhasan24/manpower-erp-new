<?php

use Database\Seeders\RolePermissionSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_packages', function (Blueprint $table) {
            $table->id();
            $table->string('type', 32);
            $table->string('code', 40);
            $table->string('name');
            $table->unsignedSmallInteger('duration_days')->nullable();
            $table->string('currency', 8)->default('BDT');
            $table->decimal('price', 15, 2)->default(0);
            $table->text('description')->nullable();
            $table->text('inclusions')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique(['type', 'code']);
            $table->index(['type', 'is_active']);
        });

        if (! Schema::hasTable('permissions') || ! Schema::hasTable('roles')) {
            return;
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
        foreach (['student-package', 'hajj-umrah-package'] as $module) {
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
        Schema::dropIfExists('service_packages');
        // Permission records and role assignments are retained on rollback.
    }
};
