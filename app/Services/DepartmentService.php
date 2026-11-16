<?php

namespace App\Services;

use App\Models\Department;
use Illuminate\Support\Facades\DB;

/** Simple CRUD service for departments (Phase 26). Business rules live here, not in the controller. */
class DepartmentService extends ServiceBase
{
    public function create(array $data): Department
    {
        return DB::transaction(fn () => Department::create($data));
    }

    public function update(Department $item, array $data): Department
    {
        return DB::transaction(function () use ($item, $data) {
            $item->update($data);

            return $item->fresh();
        });
    }

    public function delete(Department $item): bool
    {
        if ($item->employees()->exists()) {
            $item->update(['status' => 'inactive']);

            return false; // in use — deactivated instead of deleted
        }

        return (bool) $item->delete();
    }
}
