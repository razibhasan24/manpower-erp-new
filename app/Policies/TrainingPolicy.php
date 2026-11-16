<?php

namespace App\Policies;

use App\Models\Training;
use App\Models\User;
use App\Policies\Concerns\RecruitmentIsolation;
use Illuminate\Auth\Access\Response;

class TrainingPolicy
{
    use RecruitmentIsolation;

    public function viewAny(User $user): bool
    {
        return $user->can('training.view');
    }

    public function view(User $user, Training $training): bool
    {
        if (! $user->can('training.view')) {
            return false;
        }
        $recruitment = $this->recruitmentOf($training);
        return $recruitment === null || $this->canAccessRecruitment($user, $recruitment);
    }

    public function create(User $user): bool
    {
        return $user->can('training.create');
    }

    public function update(User $user, Training $training): bool
    {
        if (! $user->can('training.edit')) {
            return false;
        }
        $recruitment = $this->recruitmentOf($training);
        return $recruitment === null || $this->canAccessRecruitment($user, $recruitment);
    }

    public function changeStatus(User $user, Training $training): bool
    {
        return $this->update($user, $training);
    }

    public function approve(User $user, Training $training): bool
    {
        if (! $user->can('training.approve')) {
            return false;
        }
        $recruitment = $this->recruitmentOf($training);
        return $recruitment === null || $this->canAccessRecruitment($user, $recruitment);
    }

    /** "Delete" means cancel/archive for pipeline records — never a hard delete. */
    public function delete(User $user, Training $training): bool
    {
        if (! $user->can('training.delete')) {
            return false;
        }
        $recruitment = $this->recruitmentOf($training);
        return $recruitment === null || $this->canAccessRecruitment($user, $recruitment);
    }

    public function download(User $user, Training $training): bool
    {
        return $this->view($user, $training) && $user->can('training.download');
    }
}
