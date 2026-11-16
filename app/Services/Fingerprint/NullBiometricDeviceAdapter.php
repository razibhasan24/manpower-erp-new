<?php

namespace App\Services\Fingerprint;

use App\Contracts\BiometricDeviceAdapterInterface;

/**
 * Null adapter: explicitly reports that no biometric device is connected.
 * This is the only adapter shipped. It performs no capture and is not
 * presented in the UI as a working device.
 */
class NullBiometricDeviceAdapter implements BiometricDeviceAdapterInterface
{
    public function isAvailable(): bool { return false; }
    public function deviceName(): string { return 'No biometric device connected'; }
    public function captureReference(string $referenceNumber): ?string { return null; }
}
