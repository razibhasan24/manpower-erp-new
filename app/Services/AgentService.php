<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Agent;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Models\Country;

/**
 * Agent business logic (Phases 08-12).
 *
 * Controllers stay thin: they validate, authorise and delegate here.
 * List queries always start from the model's scopeVisibleTo() so an Agent
 * or Passport Holder can never select another party's rows.
 */
class AgentService
{
    public function list(array $filters, User $user): LengthAwarePaginator
    {
        $query = Agent::query()->visibleTo($user)->with(['branch', 'countries', 'user']);

        if (! empty($filters['search'])) {
            $term = '%'.($filters['search']).'%';
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', $term);
                $q->orWhere('phone', 'like', $term);
                $q->orWhere('email', 'like', $term);
                $q->orWhere('nid', 'like', $term);
            });
        }
        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (! empty($filters['branch_id'])) {
            $query->where('branch_id', $filters['branch_id']);
        }
        return $query->latest()->paginate(15)->withQueryString();
    }

    public function create(array $data, User $user): Agent
    {
        return DB::transaction(function () use ($data, $user) {
            $countryIds = $data['country_ids'] ?? [];
            $password = $data['password'];
            unset($data['country_ids'], $data['password'], $data['user_id']);

            if (User::where('phone', $data['phone'])->exists()) {
                throw ValidationException::withMessages(['phone' => 'This mobile number already has a login account.']);
            }

            $accountUser = User::create([
                'name' => $data['name'], 'phone' => $data['phone'], 'email' => $data['email'],
                'password' => $password, 'branch_id' => $data['branch_id'], 'status' => $data['status'] === 'active' ? 'active' : 'inactive',
                'must_change_password' => false,
            ]);
            $accountUser->assignRole('Agent');

            $agent = Agent::create($data);
            $agent->user_id = $accountUser->id;
            $agent->save();
            $agent->forceFill(['created_by' => $user->id, 'updated_by' => $user->id])->save();

            if (! empty($countryIds)) {
                $this->syncCountries($agent, $countryIds, $user);
            }

            return $agent->fresh(['countries']);
        });
    }

    public function update(Agent $agent, array $data, User $user): Agent
    {
        return DB::transaction(function () use ($agent, $data, $user) {
            $countryIds = $data['country_ids'] ?? null;
            unset($data['country_ids']);

            $agent->update($data);
            $agent->forceFill(['updated_by' => $user->id])->save();

            if ($countryIds !== null) {
                $this->syncCountries($agent, $countryIds, $user);
            }

            return $agent->fresh(['countries']);
        });
    }


    /**
     * Authorised countries for the agent (agent_country pivot, Phase 01
     * section 6.2). Staff may only grant countries inside their own
     * country scope; Super Admin may grant any country.
     */
    public function syncCountries(Agent $agent, array $countryIds, User $user): Agent
    {
        return DB::transaction(function () use ($agent, $countryIds, $user) {
            // Country::visibleTo() is unscoped in the Group A models, so
            // the grantable set is the user's own country pivot.
            $allowed = $user->countries()->pluck('countries.id')->all();
            $isSuperAdmin = $user->hasRole('Super Admin');

            foreach ($countryIds as $countryId) {
                if (! $isSuperAdmin && ! in_array((int) $countryId, $allowed, true)) {
                    throw ValidationException::withMessages([
                        'country_ids' => 'You are not authorised to grant country ID '.$countryId.' to this agent.',
                    ]);
                }
            }

            $agent->countries()->sync($countryIds);

            return $agent->fresh(['countries']);
        });
    }

    /**
     * Archive instead of hard delete: an agent's history (passport holders,
     * recruitments, ledger links) must be preserved.
     */
    public function delete(Agent $agent, User $user): Agent
    {
        return DB::transaction(function () use ($agent, $user) {
            $agent->update(['status' => 'inactive']);
            $agent->forceFill(['updated_by' => $user->id])->save();

            return $agent->fresh();
        });
    }
}
