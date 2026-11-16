<?php

namespace App\Policies;

use App\Models\ManpowerApproval;
use App\Models\User;
use App\Policies\Concerns\RecruitmentIsolation;
use Illuminate\Auth\Access\Response;

class ManpowerApprovalPolicy
{
    use RecruitmentIsolation;

    public function viewAny(User $user): bool
    {
        return $user->can('manpower-approval.view');
    }

    public function view(User $user, ManpowerApproval $manpowerApproval): bool
    {
        if (! $user->can('manpower-approval.view')) {
            return false;
        }
        $recruitment = $this->recruitmentOf($manpowerApproval);
        return $recruitment === null || $this->canAccessRecruitment($user, $recruitment);
    }

    public function create(User $user): bool
    {
        return $user->can('manpower-approval.create');
    }

    public function update(User $user, ManpowerApproval $manpowerApproval): bool
    {
        if (! $user->can('manpower-approval.edit')) {
            return false;
        }
        $recruitment = $this->recruitmentOf($manpowerApproval);
        return $recruitment === null || $this->canAccessRecruitment($user, $recruitment);
    }

    public function changeStatus(User $user, ManpowerApproval $manpowerApproval): bool
    {
        return $this->update($user, $manpowerApproval);
    }

    public function approve(User $user, ManpowerApproval $manpowerApproval): bool
    {
        if (! $user->can('manpower-approval.approve')) {
            return false;
        }
        $recruitment = $this->recruitmentOf($manpowerApproval);
        return $recruitment === null || $this->canAccessRecruitment($user, $recruitment);
    }

    /** "Delete" means cancel/archive for pipeline records — never a hard delete. */
    public function delete(User $user, ManpowerApproval $manpowerApproval): bool
    {
        if (! $user->can('manpower-approval.delete')) {
            return false;
        }
        $recruitment = $this->recruitmentOf($manpowerApproval);
        return $recruitment === null || $this->canAccessRecruitment($user, $recruitment);
    }

    public function download(User $user, ManpowerApproval $manpowerApproval): bool
    {
        return $this->view($user, $manpowerApproval) && $user->can('manpower-approval.download');
    }
}
