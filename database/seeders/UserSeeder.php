<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Phase 04/05 — creates the single Super Admin account.
 *
 * SECURITY: no real password is ever committed. The password comes from
 * the SEED_ADMIN_PASSWORD environment variable. If it is not set, a random
 * 16-character password is generated, printed ONCE to the console by the
 * seeder, and must be changed on first login (must_change_password is set).
 * Set SEED_ADMIN_EMAIL / SEED_ADMIN_PASSWORD in your local .env before
 * running `php artisan db:seed`; never put them in the repository.
 */
class UserSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('SEED_ADMIN_EMAIL', 'admin@example.com');
        $name = env('SEED_ADMIN_NAME', 'Super Admin');
        $password = env('SEED_ADMIN_PASSWORD');

        $generated = false;
        if (empty($password)) {
            $password = Str::random(16);
            $generated = true;
        }

        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => Hash::make($password),
                'status' => 'active',
                'must_change_password' => true,
            ]
        );

        $user->assignRole('Super Admin');

        $this->command->info("Super Admin account: {$email}");

        if ($generated) {
            $this->command->warn('No SEED_ADMIN_PASSWORD was set. A random password was generated:');
            $this->command->warn($password);
            $this->command->warn('Save it now — it is not stored anywhere else. Change it after first login.');
        } else {
            $this->command->info('Password was taken from SEED_ADMIN_PASSWORD. Change it after first login.');
        }
    }
}
