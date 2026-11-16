<?php

// ---------------------------------------------------------------------------
// NOT EXECUTED HERE: this workspace has no PHP / Composer / MySQL, so this
// test file was written but NOT run. Run on the XAMPP machine with:
//   php artisan test --filter=AgentIsolationTest
// Do not claim it passes until that run succeeds.
// ---------------------------------------------------------------------------

namespace Tests\Feature;

use App\Models\Agent;
use App\Models\PassportHolder;
use App\Models\Recruitment;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\CreatesUsers;

/**
 * The 6 mandatory isolation tests (Phase 01 §8.3). These are acceptance
 * criteria: if any one fails, agent isolation is broken and the release
 * must not ship.
 */
class AgentIsolationTest extends TestCase
{
    use CreatesUsers, RefreshDatabase;

    protected User $agentAUser;
    protected User $agentBUser;
    protected Agent $agentA;
    protected Agent $agentB;
    protected Recruitment $recruitmentA;
    protected Recruitment $recruitmentB;

    protected function setUp(): void
    {
        parent::setUp();

        $branch = $this->makeBranch('BR-ISO');
        $country = $this->makeCountry('ISO');

        $this->agentAUser = $this->makeUser('Agent', ['name' => 'Agent A'], ['recruitment.view', 'agent.view']);
        $this->agentBUser = $this->makeUser('Agent', ['name' => 'Agent B'], ['recruitment.view', 'agent.view']);

        $this->agentA = $this->makeAgent($this->agentAUser, $branch, [$country]);
        $this->agentB = $this->makeAgent($this->agentBUser, $branch, [$country]);

        $holderA = $this->makePassportHolder($this->makeUser('Passport Holder'), $this->agentA, $branch, $country);
        $holderB = $this->makePassportHolder($this->makeUser('Passport Holder'), $this->agentB, $branch, $country);

        $this->recruitmentA = Recruitment::create([
            'tracking_number' => '2026-BR-ISO-000001',
            'passport_holder_id' => $holderA->id,
            'agent_id' => $this->agentA->id,
            'branch_id' => $branch->id,
            'country_id' => $country->id,
            'current_stage' => 'registration',
            'current_status' => 'pending',
        ]);

        $this->recruitmentB = Recruitment::create([
            'tracking_number' => '2026-BR-ISO-000002',
            'passport_holder_id' => $holderB->id,
            'agent_id' => $this->agentB->id,
            'branch_id' => $branch->id,
            'country_id' => $country->id,
            'current_stage' => 'registration',
            'current_status' => 'pending',
        ]);
    }

    /** Mandatory test 1: Agent A cannot access Agent B's records. */
    public function test_agent_a_cannot_access_agent_b_records(): void
    {
        $this->actingAs($this->agentAUser)
            ->getJson('/api/v1/recruitments/'.$this->recruitmentB->id)
            ->assertForbidden();

        $this->actingAs($this->agentAUser)
            ->getJson('/api/v1/agents/'.$this->agentB->id)
            ->assertForbidden();

        $list = $this->actingAs($this->agentAUser)
            ->getJson('/api/v1/recruitments')
            ->assertOk();

        $ids = collect($list->json('data'))->pluck('id');
        $this->assertContains($this->recruitmentA->id, $ids);
        $this->assertNotContains($this->recruitmentB->id, $ids);
    }

    /** Mandatory test 2: Agent A cannot access Agent B's payments. */
    public function test_agent_a_cannot_access_agent_b_payments(): void
    {
        $voucherB = Voucher::create([
            'voucher_number' => 'PV-0001',
            'voucher_type' => 'payment',
            'voucher_date' => now()->toDateString(),
            'branch_id' => $this->recruitmentB->branch_id,
            'reference_type' => Recruitment::class,
            'reference_id' => $this->recruitmentB->id,
            'total_debit' => 1000,
            'total_credit' => 1000,
            'status' => 'posted',
        ]);

        // Group D's voucher API: /api/v1/accounting/vouchers/{id}.
        // KNOWN GAP (12 Nov 2026): the shipped VoucherPolicy::view()
        // allows any user with `voucher.view` in the same branch (or
        // with no branch assigned) to view ANY voucher, so Agent A can
        // currently view Agent B's payment. The policy must be fixed
        // by Group D (or `voucher.view` removed from the Agent role);
        // this test is the acceptance criterion for that fix.
        $this->actingAs($this->agentAUser)
            ->getJson('/api/v1/accounting/vouchers/'.$voucherB->id)
            ->assertForbidden();

        $this->actingAs($this->agentBUser)
            ->getJson('/api/v1/accounting/vouchers/'.$voucherB->id)
            ->assertOk();
    }

    /** Mandatory test 3: Agent A cannot access Agent B's ledger. */
    public function test_agent_a_cannot_access_agent_b_ledger(): void
    {
        // Give Agent B a real due balance first, so the test is not
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
            'branch_id' => $this->recruitmentB->branch_id,
            'reference_type' => 'test',
            'reference_id' => 9501,
            'narration' => 'Agent B charge',
            'lines' => [
                ['account_id' => $cash->id, 'debit' => 30000, 'credit' => 0, 'sub_ledger_type' => 'agent', 'sub_ledger_id' => $this->agentB->id],
                ['account_id' => $income->id, 'debit' => 0, 'credit' => 30000],
            ],
        ]);

        // Agent B's own report shows the due; Agent A's must not.
        $reportB = $this->actingAs($this->agentBUser)
            ->getJson('/api/v1/reports/agent-due')
            ->assertOk();
        $this->assertContains($this->agentB->name, collect($reportB->json('data.rows'))->pluck('agent'));

        $report = $this->actingAs($this->agentAUser)
            ->getJson('/api/v1/reports/agent-due')
            ->assertOk();

        $agents = collect($report->json('data.rows'))->pluck('agent');
        $this->assertNotContains($this->agentB->name, $agents);
    }

    /** Mandatory test 4 is in PassportHolderIsolationTest. */

    /** Mandatory test 5: unauthorised users cannot download private files. */
    public function test_unauthorised_users_cannot_download_private_files(): void
    {
        // The download route requires the owning module's download
        // permission plus the Policy check; an Agent with no document
        // permission and no ownership gets a 403, a guest is redirected.
        $this->get('/passport-holders/1/documents/1/download')->assertRedirect('/login');

        $this->actingAs($this->agentAUser)
            ->get('/passport-holders/'.$this->recruitmentB->passport_holder_id.'/documents/1/download')
            ->assertForbidden();
    }

    /** Mandatory test 6: URL manipulation cannot bypass authorisation. */
    public function test_url_manipulation_cannot_bypass_authorisation(): void
    {
        // Guessing another recruitment's id in the URL must fail even
        // when the id is valid and the user is logged in.
        $this->actingAs($this->agentAUser)
            ->get('/recruitments/'.$this->recruitmentB->id)
            ->assertForbidden();

        $this->actingAs($this->agentAUser)
            ->getJson('/api/v1/passport-holders/'.$this->recruitmentB->passport_holder_id)
            ->assertForbidden();

        // Tampering with a query filter must not widen the scope either.
        $list = $this->actingAs($this->agentAUser)
            ->getJson('/api/v1/recruitments?agent_id='.$this->agentB->id)
            ->assertOk();

        $this->assertEmpty($list->json('data'));
    }

    public function test_agent_a_can_access_own_records(): void
    {
        $this->actingAs($this->agentAUser)
            ->getJson('/api/v1/recruitments/'.$this->recruitmentA->id)
            ->assertOk()
            ->assertJsonPath('data.tracking_number', '2026-BR-ISO-000001');
    }
}
