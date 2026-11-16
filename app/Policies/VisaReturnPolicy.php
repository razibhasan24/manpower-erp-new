<?php

namespace App\Policies;

use App\Models\VisaReturn;
use App\Models\User;
use App\Policies\Concerns\RecruitmentIsolation;
use Illuminate\Auth\Access\Response;

class VisaReturnPolicy
{
    use RecruitmentIsolation;

    public function viewAny(User $user): bool
    {
        return $user->can('visa-return.view');
    }

    public function view(User $user, VisaReturn $visaReturn): bool
    {
        if (! $user->can('visa-return.view')) {
            return false;
        }
        $recruitment = $this->recruitmentOf($visaReturn);
        return $recruitment === null || $this->canAccessRecruitment($user, $recruitment);
    }

    public function create(User $user): bool
    {
        return $user->can('visa-return.create');
    }

    public function update(User $user, VisaReturn $visaReturn): bool
    {
        if (! $user->can('visa-return.edit')) {
            return false;
        }
        $recruitment = $this->recruitmentOf($visaReturn);
        return $recruitment === null || $this->canAccessRecruitment($user, $recruitment);
    }

    public function changeStatus(User $user, VisaReturn $visaReturn): bool
    {
        return $this->update($user, $visaReturn);
    }

    public function approve(User $user, VisaReturn $visaReturn): bool
    {
        if (! $user->can('visa-return.approve')) {
            return false;
        }
        $recruitment = $this->recruitmentOf($visaReturn);
        return $recruitment === null || $this->canAccessRecruitment($user, $recruitment);
    }

    /** "Delete" means cancel/archive for pipeline records — never a hard delete. */
    public function delete(User $user, VisaReturn $visaReturn): bool
    {
        if (! $user->can('visa-return.delete')) {
            return false;
        }
        $recruitment = $this->recruitmentOf($visaReturn);
        return $recruitment === null || $this->canAccessRecruitment($user, $recruitment);
    }

    public function download(User $user, VisaReturn $visaReturn): bool
    {
        return $this->view($user, $visaReturn) && $user->can('visa-return.download');
    }
}
