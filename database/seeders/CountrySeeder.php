<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

/**
 * Starter country list (Phase 11, Decision D6).
 * Clearly marked starter data: an Admin can add, edit or deactivate any
 * country from the Countries screen. Codes are ISO 3166-1 alpha-2.
 */
class CountrySeeder extends Seeder
{
    public function run(): void
    {
        $countries = [
            ['name' => 'Saudi Arabia', 'code' => 'SA'],
            ['name' => 'Malaysia', 'code' => 'MY'],
            ['name' => 'Qatar', 'code' => 'QA'],
            ['name' => 'United Arab Emirates', 'code' => 'AE'],
            ['name' => 'Oman', 'code' => 'OM'],
            ['name' => 'Bahrain', 'code' => 'BH'],
            ['name' => 'Kuwait', 'code' => 'KW'],
            ['name' => 'Singapore', 'code' => 'SG'],
            ['name' => 'Malta', 'code' => 'MT'],
            ['name' => 'Romania', 'code' => 'RO'],
            ['name' => 'Poland', 'code' => 'PL'],
            ['name' => 'Croatia', 'code' => 'HR'],
            ['name' => 'Serbia', 'code' => 'RS'],
            ['name' => 'Bulgaria', 'code' => 'BG'],
            ['name' => 'Hungary', 'code' => 'HU'],
            ['name' => 'Jordan', 'code' => 'JO'],
            ['name' => 'Lebanon', 'code' => 'LB'],
            ['name' => 'Brunei', 'code' => 'BN'],
            ['name' => 'South Korea', 'code' => 'KR'],
            ['name' => 'Japan', 'code' => 'JP'],
        ];

        foreach ($countries as $country) {
            Country::updateOrCreate(
                ['code' => $country['code']],
                ['name' => $country['name'], 'status' => 'active']
            );
        }
    }
}
