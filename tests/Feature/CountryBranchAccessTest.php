<?php

// ---------------------------------------------------------------------------
// NOT EXECUTED HERE: this workspace has no PHP / Composer / MySQL, so this
// test file was written but NOT run. Run on the XAMPP machine with:
//   php artisan test --filter=CountryBranchAccessTest
// Do not claim it passes until that run succeeds.
// ---------------------------------------------------------------------------

namespace Tests\Feature;

use App\Models\Country;
use App\Models\Recruitment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\CreatesUsers;

/**
 * Country + branch scoping (Phase 01 §8.2): a user authorised for
 * Saudi Arabia + Malaysia sees zero Qatar records, even by URL id or
 * by API call. Branch scoping behaves the same way.
 */
class CountryBranchAccessTest extends TestCase
{
    use CreatesUsers, RefreshDatabase;

    public function test_user_sees_only_their_authorised_countries(): void
    {
        $saudi = $this->makeCountry('SAU');
        $malaysia = $this->makeCountry('MYS');
        $qatar = $this->makeCountry('QAT');
        $branch = $this->makeBranch('BR-CB');

        $user = $this->makeUser('General Staff', [], ['recruitment.view']);
        $user->countries()->sync([$saudi->id, $malaysia->id]);
        $user->branches()->sync([$branch->id]);

        $agent = $this->makeAgent($this->makeUser('Agent'), $branch, [$saudi, $malaysia, $qatar]);

        $makeRecruitment = function (Country $country, string $tracking) use ($agent, $branch) {
            $holder = $this->makePassportHolder($this->makeUser('Passport Holder'), $agent, $branch, $country);

            return Recruitment::create([
                'tracking_number' => $tracking,
                'passport_holder_id' => $holder->id,
                'agent_id' => $agent->id,
                'branch_id' => $branch->id,
                'country_id' => $country->id,
                'current_stage' => 'registration',
                'current_status' => 'pending',
            ]);
        };

        $recSaudi = $makeRecruitment($saudi, '2026-BR-CB-000001');
        $recMalaysia = $makeRecruitment($malaysia, '2026-BR-CB-000002');
        $recQatar = $makeRecruitment($qatar, '2026-BR-CB-000003');

        $list = $this->actingAs($user)->getJson('/api/v1/recruitments')->assertOk();
        $ids = collect($list->json('data'))->pluck('id');

        $this->assertContains($recSaudi->id, $ids);
        $this->assertContains($recMalaysia->id, $ids);
        $this->assertNotContains($recQatar->id, $ids);

        $this->actingAs($user)
            ->getJson('/api/v1/recruitments/'.$recQatar->id)
            ->assertForbidden();
    }

    public function test_user_sees_only_their_authorised_branches(): void
    {
        $branchA = $this->makeBranch('BR-A');
        $branchB = $this->makeBranch('BR-B');
        $country = $this->makeCountry('CBX');

        $user = $this->makeUser('Branch Manager', [], ['recruitment.view']);
        $user->branches()->sync([$branchA->id]);
        $user->countries()->sync([$country->id]);

        $agent = $this->makeAgent($this->makeUser('Agent'), $branchA, [$country]);

        $makeRecruitment = function ($branch, string $tracking) use ($agent, $country) {
            $holder = $this->makePassportHolder($this->makeUser('Passport Holder'), $agent, $branch, $country);

            return Recruitment::create([
                'tracking_number' => $tracking,
                'passport_holder_id' => $holder->id,
                'agent_id' => $agent->id,
                'branch_id' => $branch->id,
                'country_id' => $country->id,
                'current_stage' => 'registration',
                'current_status' => 'pending',
            ]);
        };

        $recA = $makeRecruitment($branchA, '2026-BR-A-000001');
        $recB = $makeRecruitment($branchB, '2026-BR-B-000001');

        $list = $this->actingAs($user)->getJson('/api/v1/recruitments')->assertOk();
        $ids = collect($list->json('data'))->pluck('id');

        $this->assertContains($recA->id, $ids);
        $this->assertNotContains($recB->id, $ids);

        $this->actingAs($user)
            ->getJson('/api/v1/recruitments/'.$recB->id)
            ->assertForbidden();
    }

    public function test_agent_authorised_for_one_country_sees_zero_records_of_another(): void
    {
        $saudi = $this->makeCountry('SA2');
        $qatar = $this->makeCountry('QA2');
        $branch = $this->makeBranch('BR-CB2');

        $agentUser = $this->makeUser('Agent', [], ['recruitment.view']);
        $agent = $this->makeAgent($agentUser, $branch, [$saudi]);

        $holderQatar = $this->makePassportHolder($this->makeUser('Passport Holder'), $agent, $branch, $qatar);
        $recQatar = Recruitment::create([
            'tracking_number' => '2026-BR-CB2-000001',
            'passport_holder_id' => $holderQatar->id,
            'agent_id' => $agent->id,
            'branch_id' => $branch->id,
            'country_id' => $qatar->id,
            'current_stage' => 'registration',
            'current_status' => 'pending',
        ]);

        $this->actingAs($agentUser)
            ->getJson('/api/v1/recruitments/'.$recQatar->id)
            ->assertForbidden();
    }
}
