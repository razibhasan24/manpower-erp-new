<?php

// ---------------------------------------------------------------------------
// NOT EXECUTED HERE: this workspace has no PHP / Composer / MySQL, so this
// test file was written but NOT run. Run on the XAMPP machine with:
//   php artisan test --filter=VisaReturnTest
// Do not claim it passes until that run succeeds.
// ---------------------------------------------------------------------------

namespace Tests\Feature;

use App\Models\Recruitment;
use App\Models\Visa;
use App\Models\VisaReturn;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\CreatesUsers;

/**
 * Visa Return (Phase 20): same rules as passport return — receiver
 * details are mandatory, history is preserved, and a returned visa
 * cannot be silently re-used.
 */
class VisaReturnTest extends TestCase
{
    use CreatesUsers, RefreshDatabase;

    protected function makeVisa(): Visa
    {
        $branch = $this->makeBranch('BR-VR');
        $country = $this->makeCountry('VRT');
        $agent = $this->makeAgent($this->makeUser('Agent'), $branch, [$country]);
        $holder = $this->makePassportHolder($this->makeUser('Passport Holder'), $agent, $branch, $country);

        $recruitment = Recruitment::create([
            'tracking_number' => '2026-BR-VR-000001',
            'passport_holder_id' => $holder->id,
            'agent_id' => $agent->id,
            'branch_id' => $branch->id,
            'country_id' => $country->id,
            'current_stage' => 'visa',
            'current_status' => 'approved',
        ]);

        return Visa::create([
            'recruitment_id' => $recruitment->id,
            'mofa_status' => 'approved',
            'submission_date' => now()->toDateString(),
            'status' => 'approved',
        ]);
    }

    public function test_visa_return_requires_receiver_details(): void
    {
        $visa = $this->makeVisa();
        $staff = $this->makeUser('General Staff', [], ['visa-return.create']);

        $this->actingAs($staff)->postJson('/visa-returns', [
            'visa_id' => $visa->id,
            'return_date' => now()->toDateString(),
            'return_reason' => 'Rejected by embassy',
        ])->assertStatus(422);
    }

    public function test_visa_return_can_be_recorded(): void
    {
        $visa = $this->makeVisa();
        $staff = $this->makeUser('General Staff', [], ['visa-return.create']);

        $response = $this->actingAs($staff)->postJson('/visa-returns', [
            'visa_id' => $visa->id,
            'return_date' => now()->toDateString(),
            'return_reason' => 'Rejected by embassy',
            'return_type' => 'handover',
            'responsible_person' => 'Office Manager',
            'received_by' => 'Agent',
        ]);

        $response->assertCreated();
        $this->assertDatabaseHas('visa_returns', ['visa_id' => $visa->id]);
        $this->assertDatabaseHas('visas', ['id' => $visa->id]);
    }

    public function test_returned_visa_appears_in_return_report(): void
    {
        $visa = $this->makeVisa();

        VisaReturn::create([
            'visa_id' => $visa->id,
            'return_date' => now()->toDateString(),
            'return_reason' => 'Rejected by embassy',
            'return_type' => 'handover',
            'responsible_person' => 'Office Manager',
            'received_by' => 'Agent',
        ]);

        $staff = $this->makeUser('General Staff', [], ['report.view', 'passport-return.view']);

        $this->actingAs($staff)->getJson('/api/v1/reports/return')
            ->assertOk()
            ->assertJsonPath('data.summary.Visa Returns', 1);
    }
}
