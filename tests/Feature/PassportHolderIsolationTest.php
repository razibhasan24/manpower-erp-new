<?php

// ---------------------------------------------------------------------------
// NOT EXECUTED HERE: this workspace has no PHP / Composer / MySQL, so this
// test file was written but NOT run. Run on the XAMPP machine with:
//   php artisan test --filter=PassportHolderIsolationTest
// Do not claim it passes until that run succeeds.
// ---------------------------------------------------------------------------

namespace Tests\Feature;

use App\Models\PassportHolder;
use App\Models\Recruitment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\CreatesUsers;

/**
 * Mandatory isolation test 4 (Phase 01 §8.3): Passport Holder A cannot
 * access Holder B's data — not via the API, not via the web, not by
 * guessing an id in the URL.
 */
class PassportHolderIsolationTest extends TestCase
{
    use CreatesUsers, RefreshDatabase;

    public function test_passport_holder_a_cannot_access_holder_b_data(): void
    {
        $branch = $this->makeBranch('BR-PH');
        $country = $this->makeCountry('PHL');
        $agent = $this->makeAgent($this->makeUser('Agent'), $branch, [$country]);

        $userA = $this->makeUser('Passport Holder', ['name' => 'Holder A'], ['passport-holder.view']);
        $userB = $this->makeUser('Passport Holder', ['name' => 'Holder B'], ['passport-holder.view']);

        $holderA = $this->makePassportHolder($userA, $agent, $branch, $country);
        $holderB = $this->makePassportHolder($userB, $agent, $branch, $country);

        $this->actingAs($userA)
            ->getJson('/api/v1/passport-holders/'.$holderB->id)
            ->assertForbidden();

        $this->actingAs($userA)
            ->getJson('/api/v1/passport-holders')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $holderA->id);
    }

    public function test_passport_holder_can_access_own_record_and_own_recruitment(): void
    {
        $branch = $this->makeBranch('BR-PH2');
        $country = $this->makeCountry('PH2');
        $agent = $this->makeAgent($this->makeUser('Agent'), $branch, [$country]);
        $user = $this->makeUser('Passport Holder', ['name' => 'Holder'], ['passport-holder.view', 'recruitment.view']);
        $holder = $this->makePassportHolder($user, $agent, $branch, $country);

        $recruitment = Recruitment::create([
            'tracking_number' => '2026-BR-PH2-000001',
            'passport_holder_id' => $holder->id,
            'agent_id' => $agent->id,
            'branch_id' => $branch->id,
            'country_id' => $country->id,
            'current_stage' => 'registration',
            'current_status' => 'pending',
        ]);

        $this->actingAs($user)
            ->getJson('/api/v1/passport-holders/'.$holder->id)
            ->assertOk();

        $this->actingAs($user)
            ->getJson('/api/v1/recruitments/'.$recruitment->id)
            ->assertOk();
    }

    public function test_passport_holder_cannot_see_other_holders_due_rows(): void
    {
        $branch = $this->makeBranch('BR-PH3');
        $country = $this->makeCountry('PH3');
        $agent = $this->makeAgent($this->makeUser('Agent'), $branch, [$country]);
        $userA = $this->makeUser('Passport Holder', ['name' => 'Holder A'], ['passport-holder.view', 'report.view', 'ledger.view']);
        $userB = $this->makeUser('Passport Holder', ['name' => 'Holder B'], ['passport-holder.view']);
        $holderA = $this->makePassportHolder($userA, $agent, $branch, $country);
        $holderB = $this->makePassportHolder($userB, $agent, $branch, $country);

        // Give Holder B a real due balance first, so the test is not
        // trivially true on an empty report.
        $assets = \App\Models\AccountType::create(['name' => 'Assets', 'normal_balance' => 'debit']);
        $incomeType = \App\Models\AccountType::create(['name' => 'Income', 'normal_balance' => 'credit']);
        $cash = \App\Models\Account::create([
            'code' => '1100', 'name' => 'Cash in Hand',
            'account_type_id' => $assets->id, 'normal_balance' => 'debit', 'status' => 'active',
        ]);
        $income = \App\Models\Account::create([
            'code' => '4100', 'name' => 'Service Charge Income',
            'account_type_id' => $incomeType->id, 'normal_balance' => 'credit', 'status' => 'active',
        ]);

        app(\App\Services\VoucherService::class)->post([
            'voucher_type' => 'journal',
            'voucher_date' => now()->toDateString(),
            'branch_id' => $branch->id,
            'reference_type' => 'test',
            'reference_id' => 9502,
            'narration' => 'Holder B charge',
            'lines' => [
                ['account_id' => $cash->id, 'debit' => 20000, 'credit' => 0, 'sub_ledger_type' => 'passport_holder', 'sub_ledger_id' => $holderB->id],
                ['account_id' => $income->id, 'debit' => 0, 'credit' => 20000],
            ],
        ]);

        $report = $this->actingAs($userA)
            ->getJson('/api/v1/reports/candidate-due')
            ->assertOk();

        $names = collect($report->json('data.rows'))->pluck('candidate');
        $this->assertNotContains($holderB->full_name, $names);
    }
}
