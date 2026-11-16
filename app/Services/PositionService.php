<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Position;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * Position business logic (Phases 08-12).
 *
 * Controllers stay thin: they validate, authorise and delegate here.
 * List queries always start from the model's scopeVisibleTo() so an Agent
 * or Passport Holder can never select another party's rows.
 */
class PositionService
{
    public function list(array $filters, User $user): LengthAwarePaginator
    {
        $query = Position::query()->visibleTo($user)->with(['company', 'country']);

        if (! empty($filters['search'])) {
            $term = '%'.($filters['search']).'%';
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', $term);
            });
        }
        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (! empty($filters['country_id'])) {
            $query->where('country_id', $filters['country_id']);
        }
        if (! empty($filters['company_id'])) {
            $query->where('company_id', $filters['company_id']);
        }
        return $query->latest()->paginate(15)->withQueryString();
    }

    public function create(array $data, User $user): Position
    {
        return DB::transaction(function () use ($data, $user) {
            // created_by / updated_by exist as columns but are not in the
            // models' $fillable, so they are set via forceFill.
            $record = Position::create($data);
            $record->forceFill(['created_by' => $user->id, 'updated_by' => $user->id])->save();

            return $record;
        });
    }

    public function update(Position $position, array $data, User $user): Position
    {
        return DB::transaction(function () use ($position, $data, $user) {
            $position->update($data);
            $position->forceFill(['updated_by' => $user->id])->save();

            return $position->fresh();
        });
    }

    public function delete(Position $position, User $user): void
    {
        DB::transaction(function () use ($position) {
            if ($position->recruitments()->exists()) {
                throw ValidationException::withMessages([
                    'position' => 'This position cannot be deleted because recruitments are linked to it.',
                ]);
            }

            $position->delete();
        });
    }
}
