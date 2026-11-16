<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * Branch business logic (Phases 08-12).
 *
 * Controllers stay thin: they validate, authorise and delegate here.
 * List queries always start from the model's scopeVisibleTo() so an Agent
 * or Passport Holder can never select another party's rows.
 */
class BranchService
{
    public function list(array $filters, User $user): LengthAwarePaginator
    {
        $query = Branch::query()->visibleTo($user)->with(['manager']);

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

    public function create(array $data, User $user): Branch
    {
        return DB::transaction(function () use ($data, $user) {
            // created_by / updated_by exist as columns but are not in the
            // models' $fillable, so they are set via forceFill.
            $record = Branch::create($data);
            $record->forceFill(['created_by' => $user->id, 'updated_by' => $user->id])->save();

            return $record;
        });
    }

    public function update(Branch $branch, array $data, User $user): Branch
    {
        return DB::transaction(function () use ($branch, $data, $user) {
            $branch->update($data);
            $branch->forceFill(['updated_by' => $user->id])->save();

            return $branch->fresh();
        });
    }

    public function delete(Branch $branch, User $user): void
    {
        DB::transaction(function () use ($branch) {
            if ($branch->agents()->exists()
                || $branch->recruitments()->exists()
                || $branch->users()->exists()
            ) {
                throw ValidationException::withMessages([
                    'branch' => 'This branch cannot be deleted because it still has agents, recruitments or users linked to it. Reassign them first.',
                ]);
            }

            $branch->delete();
        });
    }
}
