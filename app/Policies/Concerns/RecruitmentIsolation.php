<?php

namespace App\Policies\Concerns;

use App\Models\Recruitment;
use App\Models\User;

/**
 * Shared recruitment-isolation check (Phase 01 section 8.3).
 * A record is visible only when the caller's branch AND country scopes
 * match the recruitment, and the caller is staff with permission, the
 * owning agent, or the passport holder themself. Super Admin bypasses.
 * Group A owns User relations (branches, countries, agent, passportHolder)
 * and Recruitment::visibleTo(); this trait uses only those contracts.
 */
trait RecruitmentIsolation
{
    protected function canAccessRecruitment(User $user, Recruitment $recruitment): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }
        $branchIds = $user->branches()->pluck('branches.id')->all();
        if ($branchIds !== [] && $recruitment->branch_id !== null && ! in_array($recruitment->branch_id, $branchIds, false)) {
            return false;
        }
        $countryIds = $user->countries()->pluck('countries.id')->all();
        if ($countryIds !== [] && $recruitment->country_id !== null && ! in_array($recruitment->country_id, $countryIds, false)) {
            return false;
        }
        if ($user->agent && (int) $user->agent->id === (int) $recruitment->agent_id) {
            return true;
        }
        if ($user->passportHolder && (int) $user->passportHolder->id === (int) $recruitment->passport_holder_id) {
            return true;
        }
        // Staff: module permission already checked by route middleware;
        // branch/country scope above is the remaining gate.
        return $user->hasRole(['Admin', 'Branch Manager', 'Accounts Staff', 'General Staff']);
    }

    protected function recruitmentOf(object $record): ?Recruitment
    {
        if ($record instanceof Recruitment) {
            return $record;
        }
        return $record->recruitment ?? null;
    }
}
