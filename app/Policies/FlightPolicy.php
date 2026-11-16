<?php

namespace App\Policies;

use App\Models\Flight;
use App\Models\User;
use App\Policies\Concerns\RecruitmentIsolation;
use Illuminate\Auth\Access\Response;

class FlightPolicy
{
    use RecruitmentIsolation;

    public function viewAny(User $user): bool
    {
        return $user->can('flight.view');
    }

    public function view(User $user, Flight $flight): bool
    {
        if (! $user->can('flight.view')) {
            return false;
        }
        $recruitment = $this->recruitmentOf($flight);
        return $recruitment === null || $this->canAccessRecruitment($user, $recruitment);
    }

    public function create(User $user): bool
    {
        return $user->can('flight.create');
    }

    public function update(User $user, Flight $flight): bool
    {
        if (! $user->can('flight.edit')) {
            return false;
        }
        $recruitment = $this->recruitmentOf($flight);
        return $recruitment === null || $this->canAccessRecruitment($user, $recruitment);
    }

    public function changeStatus(User $user, Flight $flight): bool
    {
        return $this->update($user, $flight);
    }

    public function approve(User $user, Flight $flight): bool
    {
        if (! $user->can('flight.approve')) {
            return false;
        }
        $recruitment = $this->recruitmentOf($flight);
        return $recruitment === null || $this->canAccessRecruitment($user, $recruitment);
    }

    /** "Delete" means cancel/archive for pipeline records — never a hard delete. */
    public function delete(User $user, Flight $flight): bool
    {
        if (! $user->can('flight.delete')) {
            return false;
        }
        $recruitment = $this->recruitmentOf($flight);
        return $recruitment === null || $this->canAccessRecruitment($user, $recruitment);
    }

    public function download(User $user, Flight $flight): bool
    {
        return $this->view($user, $flight) && $user->can('flight.download');
    }
}
