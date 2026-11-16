<?php

// ---------------------------------------------------------------------------
// NOT EXECUTED HERE: this workspace has no PHP / Composer / MySQL, so this
// test file was written but NOT run. Run on the XAMPP machine with:
//   php artisan test --filter=WorkflowServiceTest
// Do not claim it passes until that run succeeds.
// ---------------------------------------------------------------------------

namespace Tests\Unit;

use App\Models\Recruitment;
use App\Services\WorkflowService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\CreatesUsers;

/**
 * Unit tests for the workflow transition map (Phase 01 §7).
 * Contract: WorkflowService::transition(Recruitment, string, string,
 * ?string, ?User) returns a RecruitmentStageHistory. The 15 stages run
 * in order; a next stage is entered at its first status only, and
 * moving backwards is rejected unless the map allows it.
 */
class WorkflowServiceTest extends TestCase
{
    use CreatesUsers, RefreshDatabase;

    protected Recruitment $recruitment;

    protected function setUp(): void
    {
        parent::setUp();

        $branch = $this->makeBranch('BR-WU');
        $country = $this->makeCountry('WUT');
        $agent = $this->makeAgent($this->makeUser('Agent'), $branch, [$country]);
        $holder = $this->makePassportHolder($this->makeUser('Passport Holder'), $agent, $branch, $country);

        $this->recruitment = Recruitment::create([
            'tracking_number' => '2026-BR-WU-000001',
            'passport_holder_id' => $holder->id,
            'agent_id' => $agent->id,
            'branch_id' => $branch->id,
            'country_id' => $country->id,
            'current_stage' => 'registration',
            'current_status' => 'pending',
        ]);
    }

    protected function staff(array $permissions): \App\Models\User
    {
        return $this->makeUser('General Staff', [], $permissions);
    }

    public function test_registration_can_move_to_selection_at_first_status(): void
    {
        $history = app(WorkflowService::class)->transition(
            $this->recruitment, 'selection', 'pending', null, $this->staff(['recruitment.edit'])
        );

        $this->assertSame('selection', $history->stage);
        $this->assertSame('selection', $this->recruitment->fresh()->current_stage);
    }

    public function test_registration_cannot_jump_to_visa(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        app(WorkflowService::class)->transition(
            $this->recruitment, 'visa_processing', 'pending', null, $this->staff(['recruitment.edit'])
        );
    }

    public function test_unknown_stage_is_rejected(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        app(WorkflowService::class)->transition(
            $this->recruitment, 'teleportation', 'pending', null, $this->staff(['recruitment.edit'])
        );
    }

    public function test_unknown_status_for_stage_is_rejected(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        // "fit" is a medical result, not a selection status.
        app(WorkflowService::class)->transition(
            $this->recruitment, 'selection', 'fit', null, $this->staff(['recruitment.edit', 'recruitment.approve'])
        );
    }

    public function test_transition_records_remarks_and_actor(): void
    {
        $staff = $this->staff(['recruitment.edit', 'recruitment.reject']);

        app(WorkflowService::class)->transition(
            $this->recruitment, 'selection', 'pending', null, $staff
        );
        app(WorkflowService::class)->transition(
            $this->recruitment->fresh(), 'selection', 'rejected', 'Documents incomplete', $staff
        );

        $this->assertDatabaseHas('recruitment_stage_histories', [
            'recruitment_id' => $this->recruitment->id,
            'stage' => 'selection',
            'status' => 'rejected',
            'remarks' => 'Documents incomplete',
            'changed_by' => $staff->id,
        ]);
    }

    public function test_transition_is_atomic(): void
    {
        $service = app(WorkflowService::class);

        try {
            $service->transition($this->recruitment, 'flight_booking', 'pending', null, $this->staff(['recruitment.edit']));
        } catch (\InvalidArgumentException) {
            // Expected.
        }

        // Neither the recruitment nor its history may have changed.
        $this->assertSame('registration', $this->recruitment->fresh()->current_stage);
        $this->assertDatabaseCount('recruitment_stage_histories', 0);
    }

    public function test_transition_requires_a_user(): void
    {
        $this->expectException(\Illuminate\Auth\Access\AuthorizationException::class);

        app(WorkflowService::class)->transition(
            $this->recruitment, 'selection', 'pending', null, null
        );
    }
}
