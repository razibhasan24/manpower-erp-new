<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Country;
use Illuminate\Database\Seeder;

/**
 * Sample overseas employers (Phase 11, Decision D6).
 * Starter data only: company names are examples for demonstration and
 * must be reviewed and replaced by the Admin with the agency's real
 * overseas employers. Run CountrySeeder first.
 */
class CompanySeeder extends Seeder
{
    public function run(): void
    {
        $companies = [
            // [company name, country ISO code]
            ['Sample Construction Co.', 'SA'],
            ['Sample Cleaning Services LLC', 'SA'],
            ['Sample Hospitality Group', 'AE'],
            ['Sample Facilities Management', 'QA'],
            ['Sample Trading & Contracting', 'KW'],
            ['Sample Manpower Services', 'MY'],
            ['Sample Plantation Berhad', 'MY'],
            ['Sample Shipbuilding Pte Ltd', 'SG'],
            ['Sample Hotels Ltd', 'MT'],
            ['Sample Logistics SRL', 'RO'],
        ];

        foreach ($companies as [$name, $code]) {
            $country = Country::where('code', $code)->first();

            if ($country === null) {
                continue;
            }

            Company::updateOrCreate(
                ['name' => $name, 'country_id' => $country->id],
                ['status' => 'active']
            );
        }
    }
}
