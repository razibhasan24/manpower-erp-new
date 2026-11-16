<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\AccountType;
use Illuminate\Database\Seeder;

/**
 * Starter chart of accounts (Phase 23, D11 proposal — user to confirm).
 * Hierarchical codes: 1000 Assets, 2000 Liabilities, 3000 Equity,
 * 4000 Income, 5000 Expenses. VoucherService and PayrollService rely
 * on codes 1110 (Cash in Hand), 1120 (Bank), 1210 (Agent Receivable),
 * 1220 (Candidate Receivable), 1310 (Advance to Employee),
 * 2110 (Vendor Payable), 2120 (Salary Payable), 2130 (Deductions
 * Payable), 4100 (Service Charge Income), 5100 (Salary Expense).
 * Safe to re-run: firstOrCreate by code.
 */
class ChartOfAccountsSeeder extends Seeder
{
    /** [code, name, type, parent_code, normal_balance] */
    protected array $chart = [
        ['1000', 'Assets', 'Assets', null, 'debit'],
        ['1100', 'Cash & Bank', 'Assets', '1000', 'debit'],
        ['1110', 'Cash in Hand', 'Assets', '1100', 'debit'],
        ['1120', 'Bank — Main Account', 'Assets', '1100', 'debit'],
        ['1200', 'Accounts Receivable', 'Assets', '1000', 'debit'],
        ['1210', 'Agent Receivable', 'Assets', '1200', 'debit'],
        ['1220', 'Candidate Receivable', 'Assets', '1200', 'debit'],
        ['1300', 'Advances', 'Assets', '1000', 'debit'],
        ['1310', 'Advance to Employee', 'Assets', '1300', 'debit'],
        ['1320', 'Advance to Vendor', 'Assets', '1300', 'debit'],
        ['2000', 'Liabilities', 'Liabilities', null, 'credit'],
        ['2100', 'Accounts Payable', 'Liabilities', '2000', 'credit'],
        ['2110', 'Vendor Payable', 'Liabilities', '2100', 'credit'],
        ['2120', 'Salary Payable', 'Liabilities', '2100', 'credit'],
        ['2130', 'Deductions Payable', 'Liabilities', '2100', 'credit'],
        ['2200', 'Agent Payable', 'Liabilities', '2000', 'credit'],
        ['3000', 'Equity', 'Equity', null, 'credit'],
        ['3100', "Owner's Equity", 'Equity', '3000', 'credit'],
        ['3200', 'Retained Earnings', 'Equity', '3000', 'credit'],
        ['4000', 'Income', 'Income', null, 'credit'],
        ['4100', 'Service Charge Income', 'Income', '4000', 'credit'],
        ['4200', 'Agent Charge Income', 'Income', '4000', 'credit'],
        ['4300', 'Other Income', 'Income', '4000', 'credit'],
        ['5000', 'Expenses', 'Expenses', null, 'debit'],
        ['5100', 'Salary Expense', 'Expenses', '5000', 'debit'],
        ['5200', 'Office Expense', 'Expenses', '5000', 'debit'],
        ['5300', 'Medical Expense', 'Expenses', '5000', 'debit'],
        ['5400', 'Visa Expense', 'Expenses', '5000', 'debit'],
        ['5500', 'Training Expense', 'Expenses', '5000', 'debit'],
        ['5600', 'Travel Expense', 'Expenses', '5000', 'debit'],
        ['5700', 'Bank Charges', 'Expenses', '5000', 'debit'],
        ['5800', 'Refund Expense', 'Expenses', '5000', 'debit'],
    ];

    public function run(): void
    {
        $types = AccountType::pluck('id', 'name');
        $ids = [];

        foreach ($this->chart as [$code, $name, $type, $parentCode, $normalBalance]) {
            $account = Account::firstOrCreate(
                ['code' => $code],
                [
                    'name' => $name,
                    'account_type_id' => $types[$type],
                    'parent_id' => $parentCode ? ($ids[$parentCode] ?? null) : null,
                    'normal_balance' => $normalBalance,
                    'status' => 'active',
                ]
            );
            $ids[$code] = $account->id;
        }
    }
}
