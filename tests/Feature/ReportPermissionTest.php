<?php

// ---------------------------------------------------------------------------
// NOT EXECUTED HERE: this workspace has no PHP / Composer / MySQL, so this
// test file was written but NOT run. Run on the XAMPP machine with:
//   php artisan test --filter=ReportPermissionTest
// Do not claim it passes until that run succeeds.
// ---------------------------------------------------------------------------

namespace Tests\Feature;

use App\Models\Recruitment;
use App\Services\ReportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\CreatesUsers;

/**
 * Report permissions (prompt §25): the same permission check guards
 * the screen view and every export (PDF, Excel, print). Filters are
 * validated, and an unknown report key is a 404, not a data leak.
 */
class ReportPermissionTest extends TestCase
{
    use CreatesUsers, RefreshDatabase;

    public function test_every_registered_report_requires_its_module_permission(): void
    {
        $user = $this->makeUser('General Staff', [], ['report.view']);

        foreach (array_keys(ReportService::definitions()) as $key) {
            $this->actingAs($user)->get('/reports/'.$key)->assertForbidden();
        }
    }

    public function test_unknown_report_key_returns_404(): void
    {
        $user = $this->makeUser('General Staff', [], ['report.view', 'recruitment.view']);

        $this->actingAs($user)->get('/reports/does-not-exist')->assertNotFound();
        $this->actingAs($user)->getJson('/api/v1/reports/does-not-exist')->assertNotFound();
    }

    public function test_report_filters_are_validated(): void
    {
        $user = $this->makeUser('General Staff', [], ['report.view', 'recruitment.view']);

        $this->actingAs($user)->get('/reports/recruitment?date_from=not-a-date')
            ->assertSessionHasErrors('date_from');

        $this->actingAs($user)->get('/reports/recruitment?country_id=999999')
            ->assertSessionHasErrors('country_id');
    }

    public function test_export_is_audit_logged(): void
    {
        $user = $this->makeUser('General Staff', [], ['report.view', 'recruitment.view', 'report.export']);

        $this->actingAs($user)->get('/reports/recruitment/pdf')->assertOk();

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $user->id,
            'action' => 'report.exported',
        ]);
    }

    public function test_report_rows_match_between_screen_and_api(): void
    {
        $branch = $this->makeBranch('BR-RP');
        $country = $this->makeCountry('RPT');
        $agent = $this->makeAgent($this->makeUser('Agent'), $branch, [$country]);
        $holder = $this->makePassportHolder($this->makeUser('Passport Holder'), $agent, $branch, $country);

        Recruitment::create([
            'tracking_number' => '2026-BR-RP-000001',
            'passport_holder_id' => $holder->id,
            'agent_id' => $agent->id,
            'branch_id' => $branch->id,
            'country_id' => $country->id,
            'current_stage' => 'registration',
            'current_status' => 'pending',
        ]);

        $user = $this->makeUser('General Staff', [], ['report.view', 'recruitment.view']);

        $api = $this->actingAs($user)->getJson('/api/v1/reports/recruitment')->assertOk();
        $this->assertCount(1, $api->json('data.rows'));
        $this->assertSame('2026-BR-RP-000001', $api->json('data.rows.0.tracking_number'));

        $web = $this->actingAs($user)->get('/reports/recruitment')->assertOk();
        $web->assertSee('2026-BR-RP-000001');
    }

    public function test_passport_numbers_are_masked_without_sensitive_permission(): void
    {
        $branch = $this->makeBranch('BR-RP2');
        $country = $this->makeCountry('RP2');
        $agent = $this->makeAgent($this->makeUser('Agent'), $branch, [$country]);
        $this->makePassportHolder($this->makeUser('Passport Holder'), $agent, $branch, $country);

        $user = $this->makeUser('General Staff', [], ['report.view', 'passport-holder.view']);

        $api = $this->actingAs($user)->getJson('/api/v1/reports/passport-holder')->assertOk();
        $passport = $api->json('data.rows.0.passport_number');
        $this->assertStringContainsString('•', $passport);
    }
}
