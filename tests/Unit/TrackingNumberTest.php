<?php

// ---------------------------------------------------------------------------
// NOT EXECUTED HERE: this workspace has no PHP / Composer / MySQL, so this
// test file was written but NOT run. Run on the XAMPP machine with:
//   php artisan test --filter=TrackingNumberTest
// Do not claim it passes until that run succeeds.
// ---------------------------------------------------------------------------

namespace Tests\Unit;

use App\Models\Recruitment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\CreatesUsers;

/**
 * Tracking-number rules (Phase 01 §6.3, decision D7 pending):
 * unique, never reused, and stable once issued. The exact format is
 * decided in Phase 12; these tests assert the invariants the format
 * must satisfy, not the format itself.
 */
class TrackingNumberTest extends TestCase
{
    use CreatesUsers, RefreshDatabase;

    protected function makeRecruitment(string $tracking): Recruitment
    {
        $branch = $this->makeBranch('BR-TN');
        $country = $this->makeCountry('TNT');
        $agent = $this->makeAgent($this->makeUser('Agent'), $branch, [$country]);
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
    }

    public function test_tracking_numbers_are_unique(): void
    {
        $this->makeRecruitment('2026-BR-TN-000001');

        $this->expectException(\Illuminate\Database\QueryException::class);

        $this->makeRecruitment('2026-BR-TN-000001');
    }

    public function test_tracking_number_never_changes_on_workflow_transition(): void
    {
        $recruitment = $this->makeRecruitment('2026-BR-TN-000002');

        app(\App\Services\WorkflowService::class)->transition(
            $recruitment, 'selection', 'approved', null, null
        );

        $this->assertSame('2026-BR-TN-000002', $recruitment->fresh()->tracking_number);
    }

    public function test_tracking_number_is_required(): void
    {
        $branch = $this->makeBranch('BR-TN2');
        $country = $this->makeCountry('TN2');
        $agent = $this->makeAgent($this->makeUser('Agent'), $branch, [$country]);
        $holder = $this->makePassportHolder($this->makeUser('Passport Holder'), $agent, $branch, $country);

        $this->expectException(\Illuminate\Database\QueryException::class);

        Recruitment::create([
            'tracking_number' => null,
            'passport_holder_id' => $holder->id,
            'agent_id' => $agent->id,
            'branch_id' => $branch->id,
            'country_id' => $country->id,
            'current_stage' => 'registration',
            'current_status' => 'pending',
        ]);
    }
}
