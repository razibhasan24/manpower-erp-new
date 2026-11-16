<?php

namespace App\Policies;

use App\Models\Selection;
use App\Models\User;
use App\Policies\Concerns\RecruitmentIsolation;
use Illuminate\Auth\Access\Response;

class SelectionPolicy
{
    use RecruitmentIsolation;

    public function viewAny(User $user): bool
    {
        return $user->can('selection.view');
    }

    public function view(User $user, Selection $selection): bool
    {
        if (! $user->can('selection.view')) {
            return false;
        }
        $recruitment = $this->recruitmentOf($selection);
        return $recruitment === null || $this->canAccessRecruitment($user, $recruitment);
    }

    public function create(User $user): bool
    {
        return $user->can('selection.create');
    }

    public function update(User $user, Selection $selection): bool
    {
        if (! $user->can('selection.edit')) {
            return false;
        }
        $recruitment = $this->recruitmentOf($selection);
        return $recruitment === null || $this->canAccessRecruitment($user, $recruitment);
    }

    public function changeStatus(User $user, Selection $selection): bool
    {
        return $this->update($user, $selection);
    }

    public function approve(User $user, Selection $selection): bool
    {
        if (! $user->can('selection.approve')) {
            return false;
        }
        $recruitment = $this->recruitmentOf($selection);
        return $recruitment === null || $this->canAccessRecruitment($user, $recruitment);
    }

    /** "Delete" means cancel/archive for pipeline records — never a hard delete. */
    public function delete(User $user, Selection $selection): bool
    {
        if (! $user->can('selection.delete')) {
            return false;
        }
        $recruitment = $this->recruitmentOf($selection);
        return $recruitment === null || $this->canAccessRecruitment($user, $recruitment);
    }

    public function download(User $user, Selection $selection): bool
    {
        return $this->view($user, $selection) && $user->can('selection.download');
    }
}
