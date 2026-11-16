<?php

// ---------------------------------------------------------------------------
// NOT EXECUTED HERE: this workspace has no PHP / Composer / MySQL, so this
// test file was written but NOT run. Run on the XAMPP machine with:
//   php artisan test --filter=RecruitmentWorkflowTest
// Do not claim it passes until that run succeeds.
// ---------------------------------------------------------------------------

namespace Tests\Feature;

use App\Models\Recruitment;
use App\Services\WorkflowService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\CreatesUsers;

/**
 * Workflow transitions (Phase 01 §7) — valid and invalid.
 * Contract under test: WorkflowService::transition(Recruitment, string,
 * string, ?string, ?User) returns a RecruitmentStageHistory and updates
 * the recruitment. Every transition writes a stage-history row and an
 * audit row; an invalid transition changes nothing.
 *
 * Transition map rule (shipped WorkflowService::allowedTransitions):
 * from a stage you may move to any other status IN THE SAME stage, or
 * forward to the NEXT stage at its FIRST status only. So
 * registration/pending -> selection/pending is valid, but
 * registration/pending -> selection/approved is not — approval is a
 * second, same-stage move.
 *
 * KNOWN BLOCKER (12 Nov 2026): Group C's Api\V1\RecruitmentController
 * ::transition() calls WorkflowService::transition() STATICALLY, but
 * the method is an instance method — the API endpoint will error
 * until Group C fixes that call. The service-level tests below cover
 * the engine; the API tests assert the intended endpoint behaviour.
 */
class RecruitmentWorkflowTest extends TestCase
{
    use CreatesUsers, RefreshDatabase;

    protected \App\Models\Branch $branch;
    protected \App\Models\Country $country;

    protected function makeRecruitment(): Recruitment
    {
        $this->branch = $this->makeBranch('BR-WF');
        $this->country = $this->makeCountry('WFL');
        $agent = $this->makeAgent($this->makeUser('Agent'), $this->branch, [$this->country]);
        $holder = $this->makePassportHolder($this->makeUser('Passport Holder'), $agent, $this->branch, $this->country);

        return Recruitment::create([
            'tracking_number' => '2026-BR-WF-000001',
            'passport_holder_id' => $holder->id,
            'agent_id' => $agent->id,
            'branch_id' => $this->branch->id,
            'country_id' => $this->country->id,
            'current_stage' => 'registration',
            'current_status' => 'pending',
        ]);
    }

    /** Give a staff user the branch/country scope the Policy requires. */
    protected function scopeStaff(\App\Models\User $user): void
    {
        $user->branches()->sync([$this->branch->id]);
        $user->countries()->sync([$this->country->id]);
    }

    public function test_valid_transition_moves_stage_and_writes_history(): void
    {
        $recruitment = $this->makeRecruitment();
        $staff = $this->makeUser('General Staff', [], ['recruitment.edit', 'recruitment.approve']);
        $service = app(WorkflowService::class);

        $history = $service->transition($recruitment, 'selection', 'pending', 'Moved to selection', $staff);

        $this->assertSame('selection', $history->stage);
        $this->assertSame('selection', $recruitment->fresh()->current_stage);
        $this->assertSame('pending', $recruitment->fresh()->current_status);
        $this->assertDatabaseHas('recruitment_stage_histories', [
            'recruitment_id' => $recruitment->id,
            'stage' => 'selection',
            'status' => 'pending',
            'changed_by' => $staff->id,
        ]);
        $this->assertDatabaseHas('audit_logs', [
            'auditable_id' => $recruitment->id,
            'action' => 'workflow.transition',
        ]);
    }

    public function test_same_stage_approval_is_a_second_move(): void
    {
        $recruitment = $this->makeRecruitment();
        $staff = $this->makeUser('General Staff', [], ['recruitment.edit', 'recruitment.approve']);
        $service = app(WorkflowService::class);

        $service->transition($recruitment, 'selection', 'pending', null, $staff);
        $service->transition($recruitment->fresh(), 'selection', 'approved', 'Selected', $staff);

        $this->assertSame('approved', $recruitment->fresh()->current_status);
    }

    public function test_invalid_transition_is_rejected_and_changes_nothing(): void
    {
        $recruitment = $this->makeRecruitment();
        $staff = $this->makeUser('General Staff', [], ['recruitment.edit', 'recruitment.approve']);
        $service = app(WorkflowService::class);

        // Skipping straight from registration to flight is not allowed.
        $this->expectException(\InvalidArgumentException::class);
        $service->transition($recruitment, 'flight_booking', 'pending', null, $staff);

        $this->assertSame('registration', $recruitment->fresh()->current_stage);
        $this->assertDatabaseMissing('recruitment_stage_histories', [
            'recruitment_id' => $recruitment->id,
            'stage' => 'flight_booking',
        ]);
    }

    public function test_entering_next_stage_at_non_first_status_is_rejected(): void
    {
        $recruitment = $this->makeRecruitment();
        $staff = $this->makeUser('General Staff', [], ['recruitment.edit', 'recruitment.approve']);

        $this->expectException(\InvalidArgumentException::class);
        app(WorkflowService::class)->transition($recruitment, 'selection', 'approved', null, $staff);
    }

    public function test_transition_without_status_permission_is_rejected(): void
    {
        $recruitment = $this->makeRecruitment();
        // Has recruitment.edit (needed for 'pending') but not approve.
        $staff = $this->makeUser('General Staff', [], ['recruitment.edit']);

        app(WorkflowService::class)->transition($recruitment, 'selection', 'pending', null, $staff);

        $this->expectException(\Illuminate\Auth\Access\AuthorizationException::class);
        app(WorkflowService::class)->transition($recruitment->fresh(), 'selection', 'approved', null, $staff);
    }

    public function test_api_transition_rejects_invalid_move_with_422(): void
    {
        $recruitment = $this->makeRecruitment();
        $staff = $this->makeUser('General Staff', [], ['recruitment.edit', 'recruitment.approve']);
        $this->scopeStaff($staff);

        $this->actingAs($staff)->postJson('/api/v1/recruitments/'.$recruitment->id.'/transition', [
            'stage' => 'flight_booking',
            'status' => 'pending',
        ])->assertStatus(422);
    }

    public function test_api_transition_applies_valid_move(): void
    {
        $recruitment = $this->makeRecruitment();
        $staff = $this->makeUser('General Staff', [], ['recruitment.edit', 'recruitment.approve']);
        $this->scopeStaff($staff);

        $this->actingAs($staff)->postJson('/api/v1/recruitments/'.$recruitment->id.'/transition', [
            'stage' => 'selection',
            'status' => 'pending',
            'remarks' => 'Moved to selection',
        ])->assertOk()
            ->assertJsonPath('data.current_stage', 'selection');
    }

    public function test_transition_requires_permission(): void
    {
        $recruitment = $this->makeRecruitment();
        $staff = $this->makeUser('General Staff'); // no recruitment.edit

        $this->actingAs($staff)->postJson('/api/v1/recruitments/'.$recruitment->id.'/transition', [
            'stage' => 'selection',
            'status' => 'pending',
        ])->assertForbidden();
    }
}
