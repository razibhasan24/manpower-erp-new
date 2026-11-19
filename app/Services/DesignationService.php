<?php

namespace App\Services;

use App\Models\Designation;
use Illuminate\Support\Facades\DB;

/** Simple CRUD service for designations (Phase 26). Business rules live here, not in the controller. */
class DesignationService extends ServiceBase
{
    public function create(array $data): Designation
    {
        return DB::transaction(fn () => Designation::create($data));
    }

    /** @param Designation $item */
    public function update($item, array $data): Designation
    {
        return DB::transaction(function () use ($item, $data) {
            $item->update($data);

            return $item->fresh();
        });
    }

    /** @param Designation $item */
    public function delete($item): bool
    {
        if ($item->employees()->exists()) {
            $item->update(['status' => 'inactive']);

            return false; // in use — deactivated instead of deleted
        }

        return (bool) $item->delete();
    }
}
