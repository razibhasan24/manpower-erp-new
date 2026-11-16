<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Country;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * Country business logic (Phases 08-12).
 *
 * Controllers stay thin: they validate, authorise and delegate here.
 * List queries always start from the model's scopeVisibleTo() so an Agent
 * or Passport Holder can never select another party's rows.
 */
class CountryService
{
    public function list(array $filters, User $user): LengthAwarePaginator
    {
        $query = Country::query()->visibleTo($user);

        if (! empty($filters['search'])) {
            $term = '%'.($filters['search']).'%';
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', $term);
                $q->orWhere('code', 'like', $term);
            });
        }
        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        return $query->latest()->paginate(15)->withQueryString();
    }

    public function create(array $data, User $user): Country
    {
        return DB::transaction(function () use ($data, $user) {
            // created_by / updated_by exist as columns but are not in the
            // models' $fillable, so they are set via forceFill.
            $record = Country::create($data);
            $record->forceFill(['created_by' => $user->id, 'updated_by' => $user->id])->save();

            return $record;
        });
    }

    public function update(Country $country, array $data, User $user): Country
    {
        return DB::transaction(function () use ($country, $data, $user) {
            $country->update($data);
            $country->forceFill(['updated_by' => $user->id])->save();

            return $country->fresh();
        });
    }

    public function delete(Country $country, User $user): void
    {
        DB::transaction(function () use ($country) {
            if ($country->companies()->exists()
                || $country->positions()->exists()
                || $country->recruitments()->exists()
            ) {
                throw ValidationException::withMessages([
                    'country' => 'This country cannot be deleted because companies, positions or recruitments are linked to it.',
                ]);
            }

            $country->delete();
        });
    }
}
