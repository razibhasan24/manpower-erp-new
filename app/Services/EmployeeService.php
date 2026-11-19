<?php

namespace App\Services;

use App\Models\Employee;
use Illuminate\Support\Facades\DB;

/** Simple CRUD service for employees (Phase 26). Business rules live here, not in the controller. */
class EmployeeService extends ServiceBase
{
    public function create(array $data): Employee
    {
        return DB::transaction(fn () => Employee::create($data));
    }

    /** @param Employee $item */
    public function update($item, array $data): Employee
    {
        return DB::transaction(function () use ($item, $data) {
            $item->update($data);

            return $item->fresh();
        });
    }

    /** @param Employee $item */
    public function delete($item): bool
    {
        // Employees are never hard-deleted: payroll/ledger history depends on them.
        $item->update(['status' => 'inactive']);

        return false; // deactivated, not deleted
    }
}
