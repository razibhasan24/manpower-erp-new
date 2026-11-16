<?php

// ---------------------------------------------------------------------------
// NOT EXECUTED HERE: this workspace has no PHP / Composer / MySQL, so this
// test file was written but NOT run. Run on the XAMPP machine with:
//   php artisan test --filter=PassportReturnTest
// Do not claim it passes until that run succeeds.
// ---------------------------------------------------------------------------

namespace Tests\Feature;

use App\Models\PassportReturn;
use App\Models\Recruitment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\CreatesUsers;

/**
 * Passport Return (Phase 20): a return is never recorded without the
 * receiving person's information (Phase 01 §7), and a return never
 * deletes the passport/visa history.
 */
class PassportReturnTest extends TestCase
{
    use CreatesUsers, RefreshDatabase;

    protected function makeRecruitment(): Recruitment
    {
        $branch = $this->makeBranch('BR-PR');
        $country = $this->makeCountry('PRT');
        $agent = $this->makeAgent($this->makeUser('Agent'), $branch, [$country]);
        $holder = $this->makePassportHolder($this->makeUser('Passport Holder'), $agent, $branch, $country);

        return Recruitment::create([
            'tracking_number' => '2026-BR-PR-000001',
            'passport_holder_id' => $holder->id,
            'agent_id' => $agent->id,
            'branch_id' => $branch->id,
            'country_id' => $country->id,
            'current_stage' => 'visa',
            'current_status' => 'approved',
        ]);
    }

    public function test_passport_return_requires_receiving_person_information(): void
    {
        $recruitment = $this->makeRecruitment();
        $staff = $this->makeUser('General Staff', [], ['passport-return.create']);

        // Missing delivered_to / received_by must fail validation.
        $this->actingAs($staff)->postJson('/passport-returns', [
            'recruitment_id' => $recruitment->id,
            'return_date' => now()->toDateString(),
            'return_reason' => 'Visa rejected',
        ])->assertStatus(422);
    }

    public function test_passport_return_can_be_recorded_with_receiver_details(): void
    {
        $recruitment = $this->makeRecruitment();
        $staff = $this->makeUser('General Staff', [], ['passport-return.create']);

        $response = $this->actingAs($staff)->postJson('/passport-returns', [
            'recruitment_id' => $recruitment->id,
            'return_date' => now()->toDateString(),
            'return_reason' => 'Visa rejected',
            'return_type' => 'handover',
            'responsible_person' => 'Office Manager',
            'delivered_to' => 'Candidate in person',
            'received_by' => 'Candidate',
        ]);

        $response->assertCreated();
        $this->assertDatabaseHas('passport_returns', [
            'recruitment_id' => $recruitment->id,
            'delivered_to' => 'Candidate in person',
        ]);
    }

    public function test_passport_return_never_deletes_history(): void
    {
        $recruitment = $this->makeRecruitment();

        PassportReturn::create([
            'recruitment_id' => $recruitment->id,
            'return_date' => now()->toDateString(),
            'return_reason' => 'Visa rejected',
            'return_type' => 'handover',
            'responsible_person' => 'Office Manager',
            'delivered_to' => 'Candidate in person',
            'received_by' => 'Candidate',
        ]);

        $this->assertDatabaseHas('recruitments', ['id' => $recruitment->id]);
        $this->assertDatabaseHas('recruitment_stage_histories', ['recruitment_id' => $recruitment->id]);
    }

    public function test_return_report_lists_the_return(): void
    {
        $recruitment = $this->makeRecruitment();

        PassportReturn::create([
            'recruitment_id' => $recruitment->id,
            'return_date' => now()->toDateString(),
            'return_reason' => 'Visa rejected',
            'return_type' => 'handover',
            'responsible_person' => 'Office Manager',
            'delivered_to' => 'Candidate in person',
            'received_by' => 'Candidate',
        ]);

        $staff = $this->makeUser('General Staff', [], ['report.view', 'passport-return.view']);

        $this->actingAs($staff)->getJson('/api/v1/reports/return')
            ->assertOk()
            ->assertJsonPath('data.summary.Passport Returns', 1);
    }
}
