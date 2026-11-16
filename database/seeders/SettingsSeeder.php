<?php

namespace Database\Seeders;

use App\Models\CompanyProfile;
use App\Models\Setting;
use Illuminate\Database\Seeder;

/**
 * Phase 06 — white-label / system settings (Phase 01 section 9).
 *
 * No company name, logo or currency is hard-coded anywhere in the code;
 * every value below is a setting the Super Admin can change from the
 * Settings screen. Defaults follow Phase 01 decision D5 (BDT, Asia/Dhaka,
 * English) until the user confirms otherwise. The company profile name is
 * intentionally left empty: the Super Admin fills in the real agency name
 * on first setup; views fall back to the software display name.
 */
class SettingsSeeder extends Seeder
{
    public const DEFAULTS = [
        ['key' => 'currency', 'value' => 'BDT', 'group' => 'general'],
        ['key' => 'timezone', 'value' => 'Asia/Dhaka', 'group' => 'general'],
        ['key' => 'language', 'value' => 'en', 'group' => 'general'],
        ['key' => 'date_format', 'value' => 'd-m-Y', 'group' => 'general'],
        ['key' => 'theme', 'value' => 'light', 'group' => 'general'],
        ['key' => 'software_name', 'value' => 'Manpower Recruitment & Overseas Employment ERP', 'group' => 'branding'],
        ['key' => 'software_short_name', 'value' => 'Manpower ERP', 'group' => 'branding'],
        ['key' => 'primary_color', 'value' => '#0d6efd', 'group' => 'branding'],
        ['key' => 'secondary_color', 'value' => '#6c757d', 'group' => 'branding'],
        ['key' => 'invoice_prefix', 'value' => 'INV', 'group' => 'finance'],
        ['key' => 'voucher_prefix', 'value' => 'VCH', 'group' => 'finance'],
        ['key' => 'tracking_prefix', 'value' => 'TRK', 'group' => 'recruitment'],
        ['key' => 'login_attempts', 'value' => '5', 'group' => 'security'],
        ['key' => 'session_lifetime', 'value' => '120', 'group' => 'security'],
    ];

    public function run(): void
    {
        foreach (self::DEFAULTS as $row) {
            Setting::updateOrCreate(['key' => $row['key']], $row);
        }

        CompanyProfile::firstOrCreate(
            ['is_active' => true],
            [
                'name' => '',
                'software_name' => 'Manpower Recruitment & Overseas Employment ERP',
                'software_short_name' => 'Manpower ERP',
                'primary_color' => '#0d6efd',
                'secondary_color' => '#6c757d',
                'currency' => 'BDT',
                'language' => 'en',
                'timezone' => 'Asia/Dhaka',
                'date_format' => 'd-m-Y',
                'invoice_prefix' => 'INV',
            ]
        );
    }
}
