<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Company;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * Company business logic (Phases 08-12).
 *
 * Controllers stay thin: they validate, authorise and delegate here.
 * List queries always start from the model's scopeVisibleTo() so an Agent
 * or Passport Holder can never select another party's rows.
 */
class CompanyService
{
    public function list(array $filters, User $user): LengthAwarePaginator
    {
        $query = Company::query()->visibleTo($user)->with(['country']);

        if (! empty($filters['search'])) {
            $term = '%'.($filters['search']).'%';
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', $term);
            });
        }
        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (! empty($filters['country_id'])) {
            $query->where('country_id', $filters['country_id']);
        }
        return $query->latest()->paginate(15)->withQueryString();
    }

    public function create(array $data, User $user): Company
    {
        return DB::transaction(function () use ($data, $user) {
            // created_by / updated_by exist as columns but are not in the
            // models' $fillable, so they are set via forceFill.
            $record = Company::create($data);
            $record->forceFill(['created_by' => $user->id, 'updated_by' => $user->id])->save();

            return $record;
        });
    }

    public function update(Company $company, array $data, User $user): Company
    {
        return DB::transaction(function () use ($company, $data, $user) {
            $company->update($data);
            $company->forceFill(['updated_by' => $user->id])->save();

            return $company->fresh();
        });
    }

    public function delete(Company $company, User $user): void
    {
        DB::transaction(function () use ($company) {
            if ($company->positions()->exists() || $company->recruitments()->exists()) {
                throw ValidationException::withMessages([
                    'company' => 'This company cannot be deleted because positions or recruitments are linked to it.',
                ]);
            }

            $company->delete();
        });
    }
}
