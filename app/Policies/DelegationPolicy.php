<?php

namespace App\Policies;

use App\Models\Delegation;
use App\Models\User;
use App\Policies\Concerns\RecruitmentIsolation;
use Illuminate\Auth\Access\Response;

class DelegationPolicy
{
    use RecruitmentIsolation;

    public function viewAny(User $user): bool
    {
        return $user->can('delegation.view');
    }

    public function view(User $user, Delegation $delegation): bool
    {
        if (! $user->can('delegation.view')) {
            return false;
        }
        $recruitment = $this->recruitmentOf($delegation);
        return $recruitment === null || $this->canAccessRecruitment($user, $recruitment);
    }

    public function create(User $user): bool
    {
        return $user->can('delegation.create');
    }

    public function update(User $user, Delegation $delegation): bool
    {
        if (! $user->can('delegation.edit')) {
            return false;
        }
        $recruitment = $this->recruitmentOf($delegation);
        return $recruitment === null || $this->canAccessRecruitment($user, $recruitment);
    }

    public function changeStatus(User $user, Delegation $delegation): bool
    {
        return $this->update($user, $delegation);
    }

    public function approve(User $user, Delegation $delegation): bool
    {
        if (! $user->can('delegation.approve')) {
            return false;
        }
        $recruitment = $this->recruitmentOf($delegation);
        return $recruitment === null || $this->canAccessRecruitment($user, $recruitment);
    }

    /** "Delete" means cancel/archive for pipeline records — never a hard delete. */
    public function delete(User $user, Delegation $delegation): bool
    {
        if (! $user->can('delegation.delete')) {
            return false;
        }
        $recruitment = $this->recruitmentOf($delegation);
        return $recruitment === null || $this->canAccessRecruitment($user, $recruitment);
    }

    public function download(User $user, Delegation $delegation): bool
    {
        return $this->view($user, $delegation) && $user->can('delegation.download');
    }
}
