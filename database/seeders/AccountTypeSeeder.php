<?php

namespace Database\Seeders;

use App\Models\AccountType;
use Illuminate\Database\Seeder;

/**
 * Seeds the five account types (§21.B). Seeded, not user-editable.
 */
class AccountTypeSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            ['name' => 'Assets', 'normal_balance' => 'debit'],
            ['name' => 'Liabilities', 'normal_balance' => 'credit'],
            ['name' => 'Equity', 'normal_balance' => 'credit'],
            ['name' => 'Income', 'normal_balance' => 'credit'],
            ['name' => 'Expenses', 'normal_balance' => 'debit'],
        ] as $type) {
            AccountType::firstOrCreate(['name' => $type['name']], $type);
        }
    }
}
