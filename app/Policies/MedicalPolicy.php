<?php

namespace App\Policies;

use App\Models\Medical;
use App\Models\User;
use App\Policies\Concerns\RecruitmentIsolation;
use Illuminate\Auth\Access\Response;

class MedicalPolicy
{
    use RecruitmentIsolation;

    public function viewAny(User $user): bool
    {
        return $user->can('medical.view');
    }

    public function view(User $user, Medical $medical): bool
    {
        if (! $user->can('medical.view')) {
            return false;
        }
        $recruitment = $this->recruitmentOf($medical);
        return $recruitment === null || $this->canAccessRecruitment($user, $recruitment);
    }

    public function create(User $user): bool
    {
        return $user->can('medical.create');
    }

    public function update(User $user, Medical $medical): bool
    {
        if (! $user->can('medical.edit')) {
            return false;
        }
        $recruitment = $this->recruitmentOf($medical);
        return $recruitment === null || $this->canAccessRecruitment($user, $recruitment);
    }

    public function changeStatus(User $user, Medical $medical): bool
    {
        return $this->update($user, $medical);
    }

    public function approve(User $user, Medical $medical): bool
    {
        if (! $user->can('medical.approve')) {
            return false;
        }
        $recruitment = $this->recruitmentOf($medical);
        return $recruitment === null || $this->canAccessRecruitment($user, $recruitment);
    }

    /** "Delete" means cancel/archive for pipeline records — never a hard delete. */
    public function delete(User $user, Medical $medical): bool
    {
        if (! $user->can('medical.delete')) {
            return false;
        }
        $recruitment = $this->recruitmentOf($medical);
        return $recruitment === null || $this->canAccessRecruitment($user, $recruitment);
    }

    public function download(User $user, Medical $medical): bool
    {
        return $this->view($user, $medical) && $user->can('medical.download');
    }
}
