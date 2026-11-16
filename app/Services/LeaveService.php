<?php

namespace App\Services;

use App\Models\Leave;
use Illuminate\Support\Facades\DB;

/** Simple CRUD service for leaves (Phase 26). Business rules live here, not in the controller. */
class LeaveService extends ServiceBase
{
    public function create(array $data): Leave
    {
        return DB::transaction(fn () => Leave::create($data));
    }

    public function update(Leave $item, array $data): Leave
    {
        return DB::transaction(function () use ($item, $data) {
            $item->update($data);

            return $item->fresh();
        });
    }

    public function delete(Leave $item): bool
    {
        if ($item->status === 'approved') {
            // An approved leave is history; cancel it instead of deleting.
            $item->update(['status' => 'cancelled']);

            return false;
        }

        return (bool) $item->delete();
    }
}
