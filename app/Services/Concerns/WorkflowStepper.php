<?php

namespace App\Services\Concerns;

use App\Models\Recruitment;
use App\Models\User;
use App\Services\WorkflowService;
use InvalidArgumentException;

/**
 * Step a recruitment through WorkflowService towards a target stage/status.
 *
 * Group A's WorkflowService only allows moves that are directly permitted
 * from the recruitment's current stage/status (same-stage change, forward
 * to the next stage's first status, or back to an earlier stage as
 * "returned"). A module that creates a record already at a later stage
 * (e.g. a flight created with a ticket already issued) therefore cannot
 * call transition() once with the final stage/status — it must walk the
 * chain. This trait walks it, one permitted step at a time, inside the
 * caller's transaction. It never edits WorkflowService and never writes
 * stage history directly: every step goes through transition(), so the
 * append-only history and audit log stay intact.
 */
trait WorkflowStepper
{
    protected function stepWorkflow(Recruitment $recruitment, string $stage, string $status, ?string $remarks, ?User $user): void
    {
        $stages = WorkflowService::stagesFor($recruitment);
        for ($i = 0; $i < count($stages) + 2; $i++) {
            if ($recruitment->current_stage === $stage && $recruitment->current_status === $status) {
                return;
            }
            $allowed = $this->workflow->allowedTransitions(
                $recruitment->current_stage ?? 'registration',
                $recruitment->current_status ?? 'pending',
                $recruitment
            );
            foreach ($allowed as $target) {
                if ($target['stage'] === $stage && $target['status'] === $status) {
                    $this->workflow->transition($recruitment, $stage, $status, $remarks, $user);
                    return;
                }
            }
            $currentIndex = array_search($recruitment->current_stage, $stages, true);
            $targetIndex = array_search($stage, $stages, true);
            // Editing a record from a completed earlier stage must not push
            // the recruitment backward. Explicit returns are handled above
            // only when WorkflowService allows that returned transition.
            if ($currentIndex !== false && $targetIndex !== false && $targetIndex < $currentIndex) {
                return;
            }
            if ($currentIndex !== false && $targetIndex !== false && $targetIndex > $currentIndex && $currentIndex < count($stages) - 1) {
                $next = $stages[$currentIndex + 1];
                $this->workflow->transition($recruitment, $next, WorkflowService::statusesFor($next)[0], $remarks, $user);
                $recruitment->refresh();
                continue;
            }
            throw new InvalidArgumentException(
                "Cannot move recruitment from {$recruitment->current_stage}/{$recruitment->current_status} to {$stage}/{$status}."
            );
        }
        throw new InvalidArgumentException("Workflow path to {$stage}/{$status} did not converge.");
    }
}
