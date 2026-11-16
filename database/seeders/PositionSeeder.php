<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Country;
use App\Models\Position;
use Illuminate\Database\Seeder;

/**
 * Sample job positions (Phase 11, Decision D6).
 * Starter data only: salaries are indicative ranges in the destination
 * country's usual currency and must be reviewed by the Admin. Run
 * CountrySeeder and CompanySeeder first.
 */
class PositionSeeder extends Seeder
{
    public function run(): void
    {
        $positions = [
            // [title, company name, country ISO code, salary min, salary max, currency]
            ['Construction Worker', 'Sample Construction Co.', 'SA', 1200, 1800, 'SAR'],
            ['Cleaner', 'Sample Cleaning Services LLC', 'SA', 1000, 1400, 'SAR'],
            ['House Driver', 'Sample Construction Co.', 'SA', 1500, 2000, 'SAR'],
            ['Waiter', 'Sample Hospitality Group', 'AE', 1200, 1800, 'AED'],
            ['Security Guard', 'Sample Facilities Management', 'QA', 1300, 1800, 'QAR'],
            ['General Worker', 'Sample Trading & Contracting', 'KW', 150, 220, 'KWD'],
            ['Factory Worker', 'Sample Manpower Services', 'MY', 1500, 2200, 'MYR'],
            ['Plantation Worker', 'Sample Plantation Berhad', 'MY', 1400, 2000, 'MYR'],
            ['Shipyard Worker', 'Sample Shipbuilding Pte Ltd', 'SG', 700, 1100, 'SGD'],
            ['Hotel Housekeeper', 'Sample Hotels Ltd', 'MT', 900, 1200, 'EUR'],
            ['Warehouse Worker', 'Sample Logistics SRL', 'RO', 800, 1100, 'EUR'],
        ];

        foreach ($positions as [$title, $companyName, $code, $min, $max, $currency]) {
            $country = Country::where('code', $code)->first();
            $company = Company::where('name', $companyName)->first();

            if ($country === null || $company === null) {
                continue;
            }

            Position::updateOrCreate(
                ['title' => $title, 'company_id' => $company->id],
                [
                    'country_id' => $country->id,
                    'salary_min' => $min,
                    'salary_max' => $max,
                    'currency' => $currency,
                    'status' => 'active',
                ]
            );
        }
    }
}
