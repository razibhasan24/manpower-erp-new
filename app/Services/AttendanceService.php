<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Holiday;
use App\Services\Contracts\BiometricAttendanceAdapterInterface;
use Illuminate\Support\Facades\DB;

/**
 * Attendance (Phase 26): check in/out, late/absent/leave derivation.
 * A day's status: leave (approved leave) > holiday > present/late/absent.
 * Late threshold comes from settings (default 09:15); the value is read
 * from the settings table, never hard-coded in a view.
 */
class AttendanceService
{
    public function __construct(
        protected BiometricAttendanceAdapterInterface $biometric
    ) {}

    public function checkIn(Employee $employee, ?string $time = null, string $source = 'manual'): Attendance
    {
        $date = now()->toDateString();

        return DB::transaction(function () use ($employee, $date, $time, $source) {
            $attendance = Attendance::firstOrNew([
                'employee_id' => $employee->id,
                'date' => $date,
            ]);

            if ($attendance->exists && $attendance->check_in) {
                return $attendance; // already checked in — idempotent
            }

            // check_in is a DATETIME column: compose the date with the supplied time (or now).
            $timeString = $time ?? now()->toTimeString();
            $attendance->check_in = $date . ' ' . $timeString;
            $attendance->source = $source;
            $attendance->status = $this->deriveStatus($timeString, $date, $employee);
            $attendance->save();

            return $attendance->fresh();
        });
    }

    public function checkOut(Employee $employee, ?string $time = null): Attendance
    {
        $date = now()->toDateString();

        return DB::transaction(function () use ($employee, $date, $time) {
            $attendance = Attendance::where('employee_id', $employee->id)
                ->where('date', $date)->firstOrFail();

            $attendance->check_out = $time ? $date . ' ' . $time : now();
            $attendance->save();

            return $attendance->fresh();
        });
    }

    /** Monthly summary for one employee: present / late / absent / leave counts. */
    public function monthlySummary(Employee $employee, string $month): array
    {
        $rows = Attendance::where('employee_id', $employee->id)
            ->where('date', 'like', $month . '%')
            ->get();

        return [
            'month' => $month,
            'present' => $rows->where('status', 'present')->count(),
            'late' => $rows->where('status', 'late')->count(),
            'absent' => $rows->where('status', 'absent')->count(),
            'leave' => $rows->where('status', 'leave')->count(),
            'total_recorded' => $rows->count(),
        ];
    }

    /** Import punches from the configured biometric adapter (interface-only until a device is added). */
    public function syncFromBiometric(string $fromDate, string $toDate): array
    {
        if (! $this->biometric->isConnected()) {
            return ['connected' => false, 'imported' => 0, 'device' => $this->biometric->deviceName()];
        }

        $imported = 0;
        foreach ($this->biometric->fetchPunches($fromDate, $toDate) as $punch) {
            $employee = Employee::where('employee_code', $punch['employee_code'])->first();
            if (! $employee) {
                continue;
            }

            $date = date('Y-m-d', strtotime($punch['punched_at']));
            $time = date('H:i:s', strtotime($punch['punched_at']));

            if ($punch['type'] === 'in') {
                $this->checkIn($employee, $time, 'biometric');
            } else {
                $attendance = Attendance::where('employee_id', $employee->id)->where('date', $date)->first();
                if ($attendance) {
                    $this->checkOut($employee, $time);
                }
            }
            $imported++;
        }

        return ['connected' => true, 'imported' => $imported, 'device' => $this->biometric->deviceName()];
    }

    protected function deriveStatus(string $checkIn, string $date, Employee $employee): string
    {
        if (Holiday::where('date', $date)->exists()) {
            return 'leave';
        }

        if ($employee->leaves()->where('status', 'approved')
            ->whereDate('start_date', '<=', $date)->whereDate('end_date', '>=', $date)->exists()) {
            return 'leave';
        }

        $lateAfter = config('hrm.late_after', '09:15:00');

        return $checkIn > $lateAfter ? 'late' : 'present';
    }
}
