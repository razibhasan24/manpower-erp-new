<?php

namespace App\Services;

use App\Models\Holiday;
use Illuminate\Support\Facades\DB;

/** Simple CRUD service for holidays (Phase 26). Business rules live here, not in the controller. */
class HolidayService extends ServiceBase
{
    public function create(array $data): Holiday
    {
        return DB::transaction(fn () => Holiday::create($data));
    }

    public function update(Holiday $item, array $data): Holiday
    {
        return DB::transaction(function () use ($item, $data) {
            $item->update($data);

            return $item->fresh();
        });
    }

    public function delete(Holiday $item): bool
    {
        return (bool) $item->delete();
    }
}
