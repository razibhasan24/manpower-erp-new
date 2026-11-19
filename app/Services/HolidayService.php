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

    /** @param Holiday $item */
    public function update($item, array $data): Holiday
    {
        return DB::transaction(function () use ($item, $data) {
            $item->update($data);

            return $item->fresh();
        });
    }

    /** @param Holiday $item */
    public function delete($item): bool
    {
        return (bool) $item->delete();
    }
}
