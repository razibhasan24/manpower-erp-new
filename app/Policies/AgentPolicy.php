<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Agent;
use App\Models\User;
use App\Policies\Concerns\IsolationChecks;

/**
 * Agent authorisation (Phase 01 section 8).
 *
 * Every check combines the Spatie module permission with branch scope,
 * country scope and ownership. An Agent sees only their own records and a
 * Passport Holder sees only their own record, even by direct URL or API id.
 */
class AgentPolicy
{
    use IsolationChecks;

    public function viewAny(User $user): bool
    {
        return $this->isSuperAdmin($user) || $user->can('agent.view');
    }

    public function view(User $user, Agent $agent): bool
    {
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        if ($this->ownsAgentRecord($user, $agent->id)) {
            return true;
        }

        if (! $user->can('agent.view')) {
            return false;
        }

        return $this->inBranchScope($user, $agent->branch_id);
    }

    public function create(User $user): bool
    {
        return $this->isSuperAdmin($user) || $user->can('agent.create');
    }

    public function update(User $user, Agent $agent): bool
    {
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        if ($this->ownsAgentRecord($user, $agent->id)) {
            return true;
        }

        if (! $user->can('agent.edit')) {
            return false;
        }

        return $this->inBranchScope($user, $agent->branch_id);
    }

    public function delete(User $user, Agent $agent): bool
    {
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        return $user->can('agent.delete') && $this->inBranchScope($user, $agent->branch_id);
    }

    public function manageCountries(User $user, Agent $agent): bool
    {
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        return $user->can('agent.edit') && $this->inBranchScope($user, $agent->branch_id);
    }
}
