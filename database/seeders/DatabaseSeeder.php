<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Master seeder. Runs the foundation seeders in dependency order, then
 * any module seeders that already exist (guarded with class_exists so a
 * fresh checkout still seeds cleanly while module groups are in progress).
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            SettingsSeeder::class,
            UserSeeder::class,
        ]);

        foreach ([
            CountrySeeder::class,
            CompanySeeder::class,
            PositionSeeder::class,
            AccountTypeSeeder::class,
            ChartOfAccountsSeeder::class,
        ] as $seeder) {
            if (class_exists($seeder)) {
                $this->call($seeder);
            }
        }
    }
}
