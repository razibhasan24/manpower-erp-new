<?php

namespace Tests\Traits;

use App\Models\Agent;
use App\Models\Branch;
use App\Models\Country;
use App\Models\PassportHolder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * Shared test setup for Group E tests (Phases 31–32).
 *
 * Creates the 7 roles from Phase 01 §8.1 and the permissions the
 * tested features need. Tests are self-contained: they do not rely on
 * seeders from other groups.
 *
 * NOTE: there is no database/factories/ directory in this project
 * (Group A has not delivered factories), so users are created
 * directly with User::create(). The default password for every test
 * user is "password" unless the caller passes another one (hashed).
 */
trait CreatesUsers
{
    protected function makeRole(string $name, array $permissions = []): Role
    {
        $role = Role::firstOrCreate(['name' => $name]);

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        if ($permissions !== []) {
            $role->syncPermissions($permissions);
        }

        return $role;
    }

    protected function makeUser(string $role, array $attributes = [], array $permissions = []): User
    {
        $this->makeRole($role, $permissions);

        $user = User::create(array_merge([
            'name' => $role.' '.uniqid(),
            'email' => strtolower(str_replace(' ', '.', $role)).'.'.uniqid().'@test.example',
            'password' => Hash::make('password'),
            'status' => 'active',
        ], $attributes));
        $user->assignRole($role);

        return $user;
    }

    protected function makeSuperAdmin(array $attributes = []): User
    {
        return $this->makeUser('Super Admin', $attributes);
    }

    protected function makeBranch(?string $code = null): Branch
    {
        return Branch::create([
            'name' => 'Branch '.($code ?? uniqid()),
            'code' => $code ?? 'BR-'.uniqid(),
            'status' => 'active',
        ]);
    }

    protected function makeCountry(?string $code = null): Country
    {
        return Country::create([
            'name' => 'Country '.($code ?? uniqid()),
            'code' => $code ?? strtoupper(substr(uniqid(), 0, 3)),
            'status' => 'active',
        ]);
    }

    protected function makeAgent(User $user, ?Branch $branch = null, array $countries = []): Agent
    {
        $agent = Agent::create([
            'user_id' => $user->id,
            'name' => $user->name,
            'phone' => '01700000000',
            'branch_id' => ($branch ?? $this->makeBranch())->id,
            'status' => 'active',
        ]);

        if ($countries !== []) {
            $agent->countries()->sync(collect($countries)->map->id);
        }

        return $agent;
    }

    protected function makePassportHolder(User $user, ?Agent $agent = null, ?Branch $branch = null, ?Country $country = null): PassportHolder
    {
        return PassportHolder::create([
            'user_id' => $user->id,
            'agent_id' => $agent?->id,
            'branch_id' => ($branch ?? $this->makeBranch())->id,
            'country_id' => ($country ?? $this->makeCountry())->id,
            'full_name' => $user->name,
            'phone' => '01800000000',
            'passport_number' => 'P'.uniqid(),
            'status' => 'active',
        ]);
    }
}
