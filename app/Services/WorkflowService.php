<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\Recruitment;
use App\Models\RecruitmentStageHistory;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

/**
 * Phase 07 / 12 — the recruitment workflow engine (Phase 01 section 7).
 *
 * A controller NEVER changes a recruitment stage/status directly; it calls
 * WorkflowService::transition(). Every transition is validated (allowed
 * from-stage/status, permission for the target status, required data for
 * the target stage) and writes one append-only stage-history row plus one
 * audit-log row inside a database transaction.
 *
 * The 15 stages, in order (master prompt section 10):
 * registration, selection, delegation, medical, police_clearance,
 * training, fingerprint, manpower_approval, visa_processing,
 * mofa_processing, work_permit, appointment, flight_booking,
 * ticket_issued, post_arrival.
 */
class WorkflowService
{
    /** Ordered stages. */
    public const STAGES = [
        'registration',
        'selection',
        'delegation',
        'medical',
        'police_clearance',
        'training',
        'fingerprint',
        'manpower_approval',
        'visa_processing',
        'mofa_processing',
        'work_permit',
        'appointment',
        'flight_booking',
        'ticket_issued',
        'post_arrival',
    ];

    /** Statuses each stage may use (Phase 01 section 7). */
    public const STAGE_STATUSES = [
        'registration' => ['pending', 'approved', 'rejected', 'returned', 'cancelled', 'completed'],
        'selection' => ['pending', 'approved', 'rejected', 'returned', 'cancelled', 'completed'],
        'delegation' => ['pending', 'approved', 'rejected', 'returned', 'cancelled', 'completed'],
        'medical' => ['pending', 'fit', 'unfit', 'expired', 'cancelled'],
        'police_clearance' => ['pending', 'cleared', 'uncleared', 'returned', 'cancelled'],
        'training' => ['pending', 'approved', 'rejected', 'returned', 'cancelled', 'completed'],
        'fingerprint' => ['pending', 'approved', 'rejected', 'returned', 'cancelled', 'completed'],
        'manpower_approval' => ['pending', 'approved', 'rejected', 'returned', 'cancelled', 'completed'],
        'visa_processing' => ['pending', 'submitted', 'approved', 'rejected', 'returned', 'cancelled'],
        'mofa_processing' => ['pending', 'submitted', 'approved', 'rejected', 'returned', 'cancelled'],
        'work_permit' => ['pending', 'approved', 'rejected', 'returned', 'cancelled', 'completed'],
        'appointment' => ['pending', 'confirmed', 'completed', 'cancelled'],
        'flight_booking' => ['pending', 'approved', 'rejected', 'returned', 'cancelled', 'completed'],
        'ticket_issued' => ['pending', 'ticket_issued', 'cancelled', 'rescheduled'],
        'post_arrival' => ['pending', 'arrived', 'settled', 'issue_reported', 'completed'],
    ];

    /**
     * Permission required to move a recruitment TO a given status.
     * Approval-type statuses need the approve permission, and so on.
     */
    public const STATUS_PERMISSIONS = [
        'approved' => 'recruitment.approve',
        'completed' => 'recruitment.approve',
        'fit' => 'recruitment.approve',
        'cleared' => 'recruitment.approve',
        'submitted' => 'recruitment.edit',
        'confirmed' => 'recruitment.approve',
        'ticket_issued' => 'recruitment.approve',
        'arrived' => 'recruitment.edit',
        'settled' => 'recruitment.edit',
        'rejected' => 'recruitment.reject',
        'unfit' => 'recruitment.reject',
        'uncleared' => 'recruitment.reject',
        'returned' => 'recruitment.return',
        'cancelled' => 'recruitment.edit',
        'pending' => 'recruitment.edit',
        'expired' => 'recruitment.edit',
        'rescheduled' => 'recruitment.edit',
        'issue_reported' => 'recruitment.edit',
    ];

    /**
     * Required data before a recruitment may ENTER a stage. Each entry is a
     * closure receiving the Recruitment and returning true when satisfied.
     */
    public static function requiredData(): array
    {
        return [
            'selection' => fn (Recruitment $r) => $r->passport_holder_id !== null,
            'delegation' => fn (Recruitment $r) => $r->selection !== null && $r->selection->status === 'approved',
            'medical' => fn (Recruitment $r) => $r->delegation !== null && $r->delegation->status === 'approved',
            'police_clearance' => fn (Recruitment $r) => $r->medical !== null && $r->medical->result === 'fit',
            'training' => fn (Recruitment $r) => $r->policeClearance !== null && $r->policeClearance->status === 'cleared',
            'fingerprint' => fn (Recruitment $r) => $r->training !== null && $r->training->result === 'passed',
            'manpower_approval' => fn (Recruitment $r) => $r->fingerprint !== null && in_array($r->fingerprint->status, ['submitted', 'completed', 'approved'], true),
            'visa_processing' => fn (Recruitment $r) => $r->manpowerApproval !== null && $r->manpowerApproval->status === 'approved',
            'mofa_processing' => fn (Recruitment $r) => $r->visa !== null && in_array($r->visa->status, ['submitted', 'approved'], true),
            'work_permit' => fn (Recruitment $r) => $r->visa !== null && $r->visa->mofa_status === 'approved',
            'appointment' => fn (Recruitment $r) => $r->visa !== null && $r->visa->workPermit !== null,
            'flight_booking' => fn (Recruitment $r) => $r->visa !== null && $r->visa->status === 'approved',
            'ticket_issued' => fn (Recruitment $r) => $r->flight !== null,
            'post_arrival' => fn (Recruitment $r) => $r->flight !== null && $r->flight->status === 'ticket_issued',
        ];
    }

    /**
     * Move a recruitment to a new stage/status.
     *
     * @throws InvalidArgumentException   unknown stage/status or illegal move
     * @throws AuthorizationException     user lacks permission or visibility
     */
    public function transition(Recruitment $recruitment, string $stage, string $status, ?string $remarks = null, ?User $user = null): RecruitmentStageHistory
    {
        $user = $user ?? auth()->user();

        if ($user === null) {
            throw new AuthorizationException('A user is required to change a workflow stage.');
        }

        if (! in_array($stage, self::STAGES, true)) {
            throw new InvalidArgumentException("Unknown workflow stage: {$stage}.");
        }

        $validStatuses = self::STAGE_STATUSES[$stage] ?? [];
        if (! in_array($status, $validStatuses, true)) {
            throw new InvalidArgumentException("Status '{$status}' is not valid for stage '{$stage}'.");
        }

        $allowed = $this->allowedTransitions($recruitment->current_stage, $recruitment->current_status);
        $isAllowed = collect($allowed)->contains(
            fn ($t) => $t['stage'] === $stage && $t['status'] === $status
        );
        if (! $isAllowed) {
            throw new InvalidArgumentException(
                "Cannot move from {$recruitment->current_stage}/{$recruitment->current_status} to {$stage}/{$status}."
            );
        }

        // Visibility: the user must be able to see this recruitment at all.
        $visible = Recruitment::query()->visibleTo($user)->whereKey($recruitment->id)->exists();
        if (! $visible) {
            throw new AuthorizationException('You are not allowed to change this recruitment.');
        }

        $permission = self::STATUS_PERMISSIONS[$status] ?? 'recruitment.edit';
        if (! $user->can($permission)) {
            throw new AuthorizationException("You need the '{$permission}' permission for this transition.");
        }

        // Required data for the target stage (skipped for returns/cancels).
        if (! in_array($status, ['returned', 'cancelled', 'rejected'], true)) {
            $required = self::requiredData()[$stage] ?? null;
            if ($required !== null && ! $required($recruitment)) {
                throw new InvalidArgumentException(
                    "Required data for stage '{$stage}' is missing. Complete the previous stage first."
                );
            }
        }

        return DB::transaction(function () use ($recruitment, $stage, $status, $remarks, $user) {
            $history = RecruitmentStageHistory::create([
                'recruitment_id' => $recruitment->id,
                'stage' => $stage,
                'status' => $status,
                'changed_by' => $user->id,
                'changed_at' => now(),
                'remarks' => $remarks,
            ]);

            $recruitment->update([
                'current_stage' => $stage,
                'current_status' => $status,
            ]);

            AuditLog::create([
                'user_id' => $user->id,
                'action' => 'workflow.transition',
                'auditable_type' => Recruitment::class,
                'auditable_id' => $recruitment->id,
                'old_values' => ['stage' => $recruitment->getOriginal('current_stage'), 'status' => $recruitment->getOriginal('current_status')],
                'new_values' => ['stage' => $stage, 'status' => $status, 'remarks' => $remarks],
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            return $history;
        });
    }

    /**
     * Transitions allowed FROM the given stage/status.
     *
     * @return array<int, array{stage: string, status: string}>
     */
    public function allowedTransitions(string $stage, string $status): array
    {
        if (! in_array($stage, self::STAGES, true)) {
            return [];
        }

        $out = [];
        $index = array_search($stage, self::STAGES, true);
        $statuses = self::STAGE_STATUSES[$stage];

        // Any other status within the same stage.
        foreach ($statuses as $s) {
            if ($s !== $status) {
                $out[] = ['stage' => $stage, 'status' => $s];
            }
        }

        // Forward to the next stage (enters at its first status).
        if ($index !== false && $index < count(self::STAGES) - 1) {
            $next = self::STAGES[$index + 1];
            $out[] = ['stage' => $next, 'status' => self::STAGE_STATUSES[$next][0]];
        }

        // Send back to any earlier stage as "returned".
        for ($i = 0; $i < $index; $i++) {
            $prev = self::STAGES[$i];
            if (in_array('returned', self::STAGE_STATUSES[$prev], true)) {
                $out[] = ['stage' => $prev, 'status' => 'returned'];
            }
        }

        return $out;
    }

    /** @return string[] */
    public static function stages(): array
    {
        return self::STAGES;
    }

    /** @return string[] */
    public static function statusesFor(string $stage): array
    {
        return self::STAGE_STATUSES[$stage] ?? [];
    }
}
