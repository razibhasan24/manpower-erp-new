<?php

namespace App\Services\Biometric;

use App\Services\Contracts\BiometricAttendanceAdapterInterface;

/**
 * Null adapter: the shipped default. It reports "not connected" and
 * returns no punches, so attendance stays fully manual until a real
 * biometric device adapter is configured. It is NOT a fake device —
 * it honestly reports that no device is attached.
 */
class NullBiometricAdapter implements BiometricAttendanceAdapterInterface
{
    public function fetchPunches(string $fromDate, string $toDate): array
    {
        return [];
    }

    public function isConnected(): bool
    {
        return false;
    }

    public function deviceName(): string
    {
        return 'No biometric device configured (manual attendance)';
    }
}
