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

    public function update(Employee $item, array $data): Employee
    {
        return DB::transaction(function () use ($item, $data) {
            $item->update($data);

            return $item->fresh();
        });
    }

    public function delete(Employee $item): bool
    {
        // Employees are never hard-deleted: payroll/ledger history depends on them.
        $item->update(['status' => 'inactive']);

        return false; // deactivated, not deleted
    }
}
