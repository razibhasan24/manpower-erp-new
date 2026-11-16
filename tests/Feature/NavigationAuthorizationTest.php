<?php

namespace Tests\Feature;

use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\CreatesUsers;

class NavigationAuthorizationTest extends TestCase
{
    use CreatesUsers, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_agent_cannot_open_staff_accounting_routes(): void
    {
        $agent = $this->makeUser('Agent');

        $this->actingAs($agent)->get('/accounting/vouchers')->assertForbidden();
        $this->actingAs($agent)->get('/reports/financial/trial-balance')->assertForbidden();
    }

    public function test_passport_holder_cannot_open_the_agent_directory(): void
    {
        $holder = $this->makeUser('Passport Holder');

        $this->actingAs($holder)->get('/agents')->assertForbidden();
    }

    public function test_general_staff_cannot_open_user_management_status_page(): void
    {
        $staff = $this->makeUser('General Staff');

        $this->actingAs($staff)->get('/modules/users')->assertForbidden();
    }

    public function test_super_admin_can_open_a_development_status_page(): void
    {
        $admin = $this->makeUser('Super Admin');

        $this->actingAs($admin)
            ->get('/modules/users')
            ->assertOk()
            ->assertSee('In development')
            ->assertSee('User Management');
    }

    public function test_unknown_development_status_page_is_not_found(): void
    {
        $admin = $this->makeUser('Super Admin');

        $this->actingAs($admin)->get('/modules/not-a-real-module')->assertNotFound();
    }

    public function test_branch_manager_can_open_branch_list(): void
    {
        $manager = $this->makeUser('Branch Manager');

        $this->actingAs($manager)->get('/branches')->assertOk();
    }
}
