<?php

namespace App\Services;

use App\Models\SalaryStructure;
use Illuminate\Support\Facades\DB;

/** Simple CRUD service for salary structures (Phase 26). Business rules live here, not in the controller. */
class SalaryStructureService extends ServiceBase
{
    public function create(array $data): SalaryStructure
    {
        return DB::transaction(fn () => SalaryStructure::create($data));
    }

    public function update(SalaryStructure $item, array $data): SalaryStructure
    {
        return DB::transaction(function () use ($item, $data) {
            $item->update($data);

            return $item->fresh();
        });
    }

    public function delete(SalaryStructure $item): bool
    {
        // Salary structures are history: a new effective_from replaces them, never a delete.
        return (bool) $item->delete();
    }
}
