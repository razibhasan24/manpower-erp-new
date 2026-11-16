<?php

namespace App\Contracts;

/**
 * Integration interface for future biometric / fingerprint devices
 * (prompt section 15; Phase 01 section 6.3).
 *
 * INTERFACE ONLY. No device is connected, no hardware is driven, and no
 * fingerprint image or template is captured or stored by this code.
 * A future vendor adapter must implement this interface and be registered
 * explicitly; until then FingerprintService records only the appointment,
 * submission date, status and reference number entered by staff.
 * Fingerprint templates, if ever collected, must never be stored in the
 * application database without a separate privacy/security review.
 */
interface BiometricDeviceAdapterInterface
{
    /** Check whether a device is reachable. Must not capture any biometric data. */
    public function isAvailable(): bool;

    /** Human-readable device identifier (model / serial as reported by the device). */
    public function deviceName(): string;

    /**
     * Enrol / verify against the device. Implementations return only a
     * reference string supplied by the device; they must not return or
     * persist raw biometric templates to the application.
     */
    public function captureReference(string $referenceNumber): ?string;
}
