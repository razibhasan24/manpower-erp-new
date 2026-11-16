<?php

// ---------------------------------------------------------------------------
// NOT EXECUTED HERE: this workspace has no PHP / Composer / MySQL, so this
// test file was written but NOT run. Run on the XAMPP machine with:
//   php artisan test --filter=AuthorizationTest
// Do not claim it passes until that run succeeds.
// ---------------------------------------------------------------------------

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\CreatesUsers;

class AuthorizationTest extends TestCase
{
    use CreatesUsers, RefreshDatabase;

    public function test_user_without_permission_cannot_view_reports(): void
    {
        $user = $this->makeUser('General Staff');

        $this->actingAs($user)->get('/reports')->assertForbidden();
        $this->actingAs($user)->get('/reports/recruitment')->assertForbidden();
        $this->actingAs($user)->getJson('/api/v1/reports')->assertForbidden();
    }

    public function test_user_with_report_view_but_no_module_permission_cannot_open_that_report(): void
    {
        $user = $this->makeUser('General Staff', [], ['report.view']);

        $this->actingAs($user)->get('/reports/recruitment')->assertForbidden();
    }

    public function test_user_with_both_permissions_can_open_report(): void
    {
        $user = $this->makeUser('General Staff', [], ['report.view', 'recruitment.view']);

        $this->actingAs($user)->get('/reports/recruitment')->assertOk();
    }

    public function test_export_requires_report_export_permission(): void
    {
        $user = $this->makeUser('General Staff', [], ['report.view', 'recruitment.view']);

        $this->actingAs($user)->get('/reports/recruitment/pdf')->assertForbidden();
        $this->actingAs($user)->get('/reports/recruitment/excel')->assertForbidden();
    }

    public function test_super_admin_can_access_everything(): void
    {
        $admin = $this->makeSuperAdmin();

        $this->actingAs($admin)->get('/reports')->assertOk();
        $this->actingAs($admin)->get('/backups')->assertOk();
        $this->actingAs($admin)->getJson('/api/v1/reports')->assertOk();
    }

    public function test_role_with_no_permissions_gets_403_everywhere(): void
    {
        $user = $this->makeUser('General Staff');

        $this->actingAs($user)->getJson('/api/v1/agents')->assertForbidden();
        $this->actingAs($user)->getJson('/api/v1/vouchers')->assertForbidden();
        $this->actingAs($user)->getJson('/api/v1/backups')->assertForbidden();
    }

    public function test_admin_role_has_no_implicit_access(): void
    {
        // Phase 01 §8.1: Admin gets whatever permissions are assigned —
        // no implicit access.
        $admin = $this->makeUser('Admin');

        $this->actingAs($admin)->get('/reports')->assertForbidden();
        $this->actingAs($admin)->getJson('/api/v1/recruitments')->assertForbidden();
    }
}
