<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Generic branch/country/ownership guard (Phase 01 section 8.3).
 * Runs on routes with implicit model binding: if a bound model carries
 * branch_id, country_id, agent_id or user_id, the current user must be
 * inside that scope or the request is rejected with 403. This is a
 * second line of defence behind Policies and query scopes.
 */
class EnsureBranchScope
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user instanceof User && ! $user->hasRole('Super Admin')) {
            foreach ($request->route()?->parameters() ?? [] as $parameter) {
                if (! $parameter instanceof Model) {
                    continue;
                }

                $this->assertInScope($user, $parameter);
            }
        }

        return $next($request);
    }

    private function assertInScope(User $user, Model $model): void
    {
        $attributes = $model->getAttributes();

        if (array_key_exists('branch_id', $attributes) && $model->branch_id !== null) {
            $branchIds = $user->branches()->pluck('branches.id')->all();
            if ($branchIds !== [] && ! in_array($model->branch_id, $branchIds, true)) {
                abort(403, 'This record belongs to another branch.');
            }
        }

        if (array_key_exists('country_id', $attributes) && $model->country_id !== null) {
            $countryIds = $user->countries()->pluck('countries.id')->all();
            if ($countryIds !== [] && ! in_array($model->country_id, $countryIds, true)) {
                abort(403, 'This record belongs to a country you are not authorised for.');
            }
        }

        if (array_key_exists('agent_id', $attributes) && $user->hasRole('Agent')) {
            if ($user->agent === null || $model->agent_id !== $user->agent->id) {
                abort(403, 'This record belongs to another agent.');
            }
        }

        if (array_key_exists('user_id', $attributes) && $user->hasRole('Passport Holder')) {
            if ($model->user_id !== $user->id) {
                abort(403, 'This record belongs to another passport holder.');
            }
        }
    }
}
