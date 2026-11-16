<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Recruitment;
use App\Models\User;
use App\Policies\Concerns\IsolationChecks;

/**
 * Recruitment authorisation (Phase 01 section 8).
 *
 * Every check combines the Spatie module permission with branch scope,
 * country scope and ownership. An Agent sees only their own records and a
 * Passport Holder sees only their own record, even by direct URL or API id.
 */
class RecruitmentPolicy
{
    use IsolationChecks;

    public function viewAny(User $user): bool
    {
        return $this->isSuperAdmin($user) || $user->can('recruitment.view');
    }

    public function view(User $user, Recruitment $recruitment): bool
    {
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        if ($this->isSelfPassportHolder($user, $recruitment->passport_holder_id)) {
            return true;
        }

        if ($this->ownsAgentRecord($user, $recruitment->agent_id)) {
            return true;
        }

        if (! $user->can('recruitment.view')) {
            return false;
        }

        return $this->inBranchScope($user, $recruitment->branch_id)
            && $this->inCountryScope($user, $recruitment->country_id);
    }

    public function create(User $user): bool
    {
        return $this->isSuperAdmin($user) || $user->can('recruitment.create');
    }

    public function update(User $user, Recruitment $recruitment): bool
    {
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        if ($this->ownsAgentRecord($user, $recruitment->agent_id)) {
            return true;
        }

        if (! $user->can('recruitment.edit')) {
            return false;
        }

        return $this->inBranchScope($user, $recruitment->branch_id)
            && $this->inCountryScope($user, $recruitment->country_id);
    }

    public function cancel(User $user, Recruitment $recruitment): bool
    {
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        if ($this->ownsAgentRecord($user, $recruitment->agent_id)) {
            return true;
        }

        return $user->can('recruitment.delete')
            && $this->inBranchScope($user, $recruitment->branch_id)
            && $this->inCountryScope($user, $recruitment->country_id);
    }

    /**
     * "Delete" on a recruitment is a cancellation (see RecruitmentService):
     * the same people who may cancel may delete.
     */
    public function delete(User $user, Recruitment $recruitment): bool
    {
        return $this->cancel($user, $recruitment);
    }

    public function downloadDocument(User $user, Recruitment $recruitment): bool
    {
        return $this->view($user, $recruitment);
    }
}
