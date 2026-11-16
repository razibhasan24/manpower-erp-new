<?php

// ---------------------------------------------------------------------------
// NOT EXECUTED HERE: this workspace has no PHP / Composer / MySQL, so this
// test file was written but NOT run. Run on the XAMPP machine with:
//   php artisan test --filter=RegistrationTest
// Do not claim it passes until that run succeeds.
// ---------------------------------------------------------------------------

namespace Tests\Feature;

use App\Models\PassportHolder;
use App\Models\Recruitment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\CreatesUsers;

class RegistrationTest extends TestCase
{
    use CreatesUsers, RefreshDatabase;

    protected function makeRegistrationContext(): array
    {
        $branch = $this->makeBranch('BR-REG');
        $country = $this->makeCountry('REG');
        $agentUser = $this->makeUser('Agent', [], ['passport-holder.create', 'recruitment.create']);
        $agent = $this->makeAgent($agentUser, $branch, [$country]);

        return compact('branch', 'country', 'agentUser', 'agent');
    }

    public function test_agent_can_register_a_passport_holder(): void
    {
        ['branch' => $branch, 'country' => $country, 'agentUser' => $agentUser, 'agent' => $agent] = $this->makeRegistrationContext();

        $response = $this->actingAs($agentUser)->postJson('/api/v1/passport-holders', [
            'full_name' => 'Test Candidate',
            'phone' => '01711111111',
            'passport_number' => 'P1234567',
            'agent_id' => $agent->id,
            'branch_id' => $branch->id,
            'country_id' => $country->id,
            'status' => 'active',
        ]);

        $response->assertCreated()
            ->assertJsonPath('data.full_name', 'Test Candidate');

        $this->assertDatabaseHas('passport_holders', ['passport_number' => 'P1234567']);
    }

    public function test_duplicate_active_passport_number_is_rejected(): void
    {
        ['branch' => $branch, 'country' => $country, 'agentUser' => $agentUser, 'agent' => $agent] = $this->makeRegistrationContext();

        $this->makePassportHolder($this->makeUser('Passport Holder'), $agent, $branch, $country);

        $existing = PassportHolder::first();

        $this->actingAs($agentUser)->postJson('/api/v1/passport-holders', [
            'full_name' => 'Second Person',
            'phone' => '01722222222',
            'passport_number' => $existing->passport_number,
            'agent_id' => $agent->id,
            'branch_id' => $branch->id,
            'country_id' => $country->id,
            'status' => 'active',
        ])->assertStatus(422)
            ->assertJsonValidationErrors('passport_number');
    }

    public function test_recruitment_registration_requires_valid_references(): void
    {
        ['agentUser' => $agentUser] = $this->makeRegistrationContext();

        $this->actingAs($agentUser)->postJson('/api/v1/recruitments', [
            'passport_holder_id' => 999999,
            'agent_id' => 999999,
            'branch_id' => 999999,
            'country_id' => 999999,
            'company_id' => 999999,
            'position_id' => 999999,
        ])->assertStatus(422);
    }

    public function test_recruitment_registration_creates_tracking_number_and_stage_history(): void
    {
        ['branch' => $branch, 'country' => $country, 'agentUser' => $agentUser, 'agent' => $agent] = $this->makeRegistrationContext();

        $holder = $this->makePassportHolder($this->makeUser('Passport Holder'), $agent, $branch, $country);
        $company = \App\Models\Company::create(['name' => 'Test Co', 'country_id' => $country->id, 'status' => 'active']);
        $position = \App\Models\Position::create([
            'title' => 'Worker', 'company_id' => $company->id, 'country_id' => $country->id, 'status' => 'active',
        ]);

        $response = $this->actingAs($agentUser)->postJson('/api/v1/recruitments', [
            'passport_holder_id' => $holder->id,
            'agent_id' => $agent->id,
            'branch_id' => $branch->id,
            'country_id' => $country->id,
            'company_id' => $company->id,
            'position_id' => $position->id,
        ]);

        $response->assertCreated();
        $this->assertNotEmpty($response->json('data.tracking_number'));
        $this->assertSame('registration', $response->json('data.current_stage'));

        $recruitment = Recruitment::find($response->json('data.id'));
        $this->assertDatabaseHas('recruitment_stage_histories', [
            'recruitment_id' => $recruitment->id,
            'stage' => 'registration',
        ]);
    }
}
