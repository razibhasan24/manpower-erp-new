<?php

namespace App\Policies;

use App\Models\PassportReturn;
use App\Models\User;
use App\Policies\Concerns\RecruitmentIsolation;
use Illuminate\Auth\Access\Response;

class PassportReturnPolicy
{
    use RecruitmentIsolation;

    public function viewAny(User $user): bool
    {
        return $user->can('passport-return.view');
    }

    public function view(User $user, PassportReturn $passportReturn): bool
    {
        if (! $user->can('passport-return.view')) {
            return false;
        }
        $recruitment = $this->recruitmentOf($passportReturn);
        return $recruitment === null || $this->canAccessRecruitment($user, $recruitment);
    }

    public function create(User $user): bool
    {
        return $user->can('passport-return.create');
    }

    public function update(User $user, PassportReturn $passportReturn): bool
    {
        if (! $user->can('passport-return.edit')) {
            return false;
        }
        $recruitment = $this->recruitmentOf($passportReturn);
        return $recruitment === null || $this->canAccessRecruitment($user, $recruitment);
    }

    public function changeStatus(User $user, PassportReturn $passportReturn): bool
    {
        return $this->update($user, $passportReturn);
    }

    public function approve(User $user, PassportReturn $passportReturn): bool
    {
        if (! $user->can('passport-return.approve')) {
            return false;
        }
        $recruitment = $this->recruitmentOf($passportReturn);
        return $recruitment === null || $this->canAccessRecruitment($user, $recruitment);
    }

    /** "Delete" means cancel/archive for pipeline records — never a hard delete. */
    public function delete(User $user, PassportReturn $passportReturn): bool
    {
        if (! $user->can('passport-return.delete')) {
            return false;
        }
        $recruitment = $this->recruitmentOf($passportReturn);
        return $recruitment === null || $this->canAccessRecruitment($user, $recruitment);
    }

    public function download(User $user, PassportReturn $passportReturn): bool
    {
        return $this->view($user, $passportReturn) && $user->can('passport-return.download');
    }
}
