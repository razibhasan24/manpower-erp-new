<?php

namespace App\Services\Contracts;

/**
 * Biometric attendance adapter (Phase 26, §23).
 *
 * INTERFACE ONLY — no device is connected in this phase. A future
 * implementation talks to the vendor's SDK/API and returns the same
 * shape this interface defines. AttendanceService depends on the
 * interface, never on a concrete device, so a device can be plugged in
 * without touching payroll or attendance logic.
 */
interface BiometricAttendanceAdapterInterface
{
    /**
     * Pull raw punches for a date range.
     * @return array<int, array{employee_code: string, punched_at: string, type: string}>  type: in|out
     */
    public function fetchPunches(string $fromDate, string $toDate): array;

    /** True when the device/service is reachable. */
    public function isConnected(): bool;

    /** Human-readable device/service name, for the admin screen. */
    public function deviceName(): string;
}
