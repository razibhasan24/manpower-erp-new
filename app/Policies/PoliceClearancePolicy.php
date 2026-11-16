<?php

namespace App\Policies;

use App\Models\PoliceClearance;
use App\Models\User;
use App\Policies\Concerns\RecruitmentIsolation;
use Illuminate\Auth\Access\Response;

class PoliceClearancePolicy
{
    use RecruitmentIsolation;

    public function viewAny(User $user): bool
    {
        return $user->can('police-clearance.view');
    }

    public function view(User $user, PoliceClearance $policeClearance): bool
    {
        if (! $user->can('police-clearance.view')) {
            return false;
        }
        $recruitment = $this->recruitmentOf($policeClearance);
        return $recruitment === null || $this->canAccessRecruitment($user, $recruitment);
    }

    public function create(User $user): bool
    {
        return $user->can('police-clearance.create');
    }

    public function update(User $user, PoliceClearance $policeClearance): bool
    {
        if (! $user->can('police-clearance.edit')) {
            return false;
        }
        $recruitment = $this->recruitmentOf($policeClearance);
        return $recruitment === null || $this->canAccessRecruitment($user, $recruitment);
    }

    public function changeStatus(User $user, PoliceClearance $policeClearance): bool
    {
        return $this->update($user, $policeClearance);
    }

    public function approve(User $user, PoliceClearance $policeClearance): bool
    {
        if (! $user->can('police-clearance.approve')) {
            return false;
        }
        $recruitment = $this->recruitmentOf($policeClearance);
        return $recruitment === null || $this->canAccessRecruitment($user, $recruitment);
    }

    /** "Delete" means cancel/archive for pipeline records — never a hard delete. */
    public function delete(User $user, PoliceClearance $policeClearance): bool
    {
        if (! $user->can('police-clearance.delete')) {
            return false;
        }
        $recruitment = $this->recruitmentOf($policeClearance);
        return $recruitment === null || $this->canAccessRecruitment($user, $recruitment);
    }

    public function download(User $user, PoliceClearance $policeClearance): bool
    {
        return $this->view($user, $policeClearance) && $user->can('police-clearance.download');
    }
}
