<?php

namespace App\Policies;

use App\Models\Fingerprint;
use App\Models\User;
use App\Policies\Concerns\RecruitmentIsolation;
use Illuminate\Auth\Access\Response;

class FingerprintPolicy
{
    use RecruitmentIsolation;

    public function viewAny(User $user): bool
    {
        return $user->can('fingerprint.view');
    }

    public function view(User $user, Fingerprint $fingerprint): bool
    {
        if (! $user->can('fingerprint.view')) {
            return false;
        }
        $recruitment = $this->recruitmentOf($fingerprint);
        return $recruitment === null || $this->canAccessRecruitment($user, $recruitment);
    }

    public function create(User $user): bool
    {
        return $user->can('fingerprint.create');
    }

    public function update(User $user, Fingerprint $fingerprint): bool
    {
        if (! $user->can('fingerprint.edit')) {
            return false;
        }
        $recruitment = $this->recruitmentOf($fingerprint);
        return $recruitment === null || $this->canAccessRecruitment($user, $recruitment);
    }

    public function changeStatus(User $user, Fingerprint $fingerprint): bool
    {
        return $this->update($user, $fingerprint);
    }

    public function approve(User $user, Fingerprint $fingerprint): bool
    {
        if (! $user->can('fingerprint.approve')) {
            return false;
        }
        $recruitment = $this->recruitmentOf($fingerprint);
        return $recruitment === null || $this->canAccessRecruitment($user, $recruitment);
    }

    /** "Delete" means cancel/archive for pipeline records — never a hard delete. */
    public function delete(User $user, Fingerprint $fingerprint): bool
    {
        if (! $user->can('fingerprint.delete')) {
            return false;
        }
        $recruitment = $this->recruitmentOf($fingerprint);
        return $recruitment === null || $this->canAccessRecruitment($user, $recruitment);
    }

    public function download(User $user, Fingerprint $fingerprint): bool
    {
        return $this->view($user, $fingerprint) && $user->can('fingerprint.download');
    }
}
