<?php

namespace App\Policies;

use App\Models\PostArrival;
use App\Models\User;
use App\Policies\Concerns\RecruitmentIsolation;
use Illuminate\Auth\Access\Response;

class PostArrivalPolicy
{
    use RecruitmentIsolation;

    public function viewAny(User $user): bool
    {
        return $user->can('post-arrival.view');
    }

    public function view(User $user, PostArrival $postArrival): bool
    {
        if (! $user->can('post-arrival.view')) {
            return false;
        }
        $recruitment = $this->recruitmentOf($postArrival);
        return $recruitment === null || $this->canAccessRecruitment($user, $recruitment);
    }

    public function create(User $user): bool
    {
        return $user->can('post-arrival.create');
    }

    public function update(User $user, PostArrival $postArrival): bool
    {
        if (! $user->can('post-arrival.edit')) {
            return false;
        }
        $recruitment = $this->recruitmentOf($postArrival);
        return $recruitment === null || $this->canAccessRecruitment($user, $recruitment);
    }

    public function changeStatus(User $user, PostArrival $postArrival): bool
    {
        return $this->update($user, $postArrival);
    }

    public function approve(User $user, PostArrival $postArrival): bool
    {
        if (! $user->can('post-arrival.approve')) {
            return false;
        }
        $recruitment = $this->recruitmentOf($postArrival);
        return $recruitment === null || $this->canAccessRecruitment($user, $recruitment);
    }

    /** "Delete" means cancel/archive for pipeline records — never a hard delete. */
    public function delete(User $user, PostArrival $postArrival): bool
    {
        if (! $user->can('post-arrival.delete')) {
            return false;
        }
        $recruitment = $this->recruitmentOf($postArrival);
        return $recruitment === null || $this->canAccessRecruitment($user, $recruitment);
    }

    public function download(User $user, PostArrival $postArrival): bool
    {
        return $this->view($user, $postArrival) && $user->can('post-arrival.download');
    }
}
