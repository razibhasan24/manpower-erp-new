<?php

namespace App\Policies;

use App\Models\Visa;
use App\Models\User;
use App\Policies\Concerns\RecruitmentIsolation;
use Illuminate\Auth\Access\Response;

class VisaPolicy
{
    use RecruitmentIsolation;

    public function viewAny(User $user): bool
    {
        return $user->can('visa.view');
    }

    public function view(User $user, Visa $visa): bool
    {
        if (! $user->can('visa.view')) {
            return false;
        }
        $recruitment = $this->recruitmentOf($visa);
        return $recruitment === null || $this->canAccessRecruitment($user, $recruitment);
    }

    public function create(User $user): bool
    {
        return $user->can('visa.create');
    }

    public function update(User $user, Visa $visa): bool
    {
        if (! $user->can('visa.edit')) {
            return false;
        }
        $recruitment = $this->recruitmentOf($visa);
        return $recruitment === null || $this->canAccessRecruitment($user, $recruitment);
    }

    public function changeStatus(User $user, Visa $visa): bool
    {
        return $this->update($user, $visa);
    }

    public function approve(User $user, Visa $visa): bool
    {
        if (! $user->can('visa.approve')) {
            return false;
        }
        $recruitment = $this->recruitmentOf($visa);
        return $recruitment === null || $this->canAccessRecruitment($user, $recruitment);
    }

    /** "Delete" means cancel/archive for pipeline records — never a hard delete. */
    public function delete(User $user, Visa $visa): bool
    {
        if (! $user->can('visa.delete')) {
            return false;
        }
        $recruitment = $this->recruitmentOf($visa);
        return $recruitment === null || $this->canAccessRecruitment($user, $recruitment);
    }

    public function download(User $user, Visa $visa): bool
    {
        return $this->view($user, $visa) && $user->can('visa.download');
    }
}
