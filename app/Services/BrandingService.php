<?php

namespace App\Services;

use App\Models\CompanyProfile;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

/**
 * Phase 06 — white-label branding (Phase 01 section 9).
 *
 * No company name, logo, colour or currency is hard-coded in any view,
 * PDF or email: everything comes from the active CompanyProfile row and
 * the settings table through this service. Changing a setting takes
 * effect immediately because the cache is cleared on save.
 */
class BrandingService
{
    public static function profile(): ?CompanyProfile
    {
        if (! self::tablesReady()) {
            return null;
        }

        return Cache::remember('branding.company_profile', 3600, function () {
            return CompanyProfile::where('is_active', true)->first()
                ?? CompanyProfile::first();
        });
    }

    public static function setting(string $key, $default = null)
    {
        if (! self::tablesReady()) {
            return $default;
        }

        return Cache::remember("branding.setting.{$key}", 3600, function () use ($key, $default) {
            return Setting::where('key', $key)->value('value') ?? $default;
        });
    }

    public static function appName(): string
    {
        $profile = self::profile();

        return $profile?->name !== null && $profile->name !== ''
            ? $profile->name
            : (string) (self::setting('software_name', 'Manpower Recruitment & Overseas Employment ERP'));
    }

    public static function shortName(): string
    {
        return (string) (self::profile()?->software_short_name ?? self::setting('software_short_name', 'ERP'));
    }

    public static function currency(): string
    {
        return (string) (self::profile()?->currency ?? self::setting('currency', 'BDT'));
    }

    public static function primaryColor(): string
    {
        return (string) (self::profile()?->primary_color ?? self::setting('primary_color', '#0d6efd'));
    }

    public static function secondaryColor(): string
    {
        return (string) (self::profile()?->secondary_color ?? self::setting('secondary_color', '#6c757d'));
    }

    public static function timezone(): string
    {
        return (string) (self::profile()?->timezone ?? self::setting('timezone', 'Asia/Dhaka'));
    }

    public static function clearCache(): void
    {
        Cache::forget('branding.company_profile');
        foreach (['currency', 'timezone', 'language', 'date_format', 'software_name', 'software_short_name', 'primary_color', 'secondary_color', 'invoice_prefix'] as $key) {
            Cache::forget("branding.setting.{$key}");
        }
    }

    private static function tablesReady(): bool
    {
        try {
            return Schema::hasTable('company_profiles') && Schema::hasTable('settings');
        } catch (\Throwable) {
            return false;
        }
    }
}
