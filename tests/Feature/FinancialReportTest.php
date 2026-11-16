<?php

// ---------------------------------------------------------------------------
// NOT EXECUTED HERE: this workspace has no PHP / Composer / MySQL, so this
// test file was written but NOT run. Run on the XAMPP machine with:
//   php artisan test --filter=FinancialReportTest
// Do not claim it passes until that run succeeds.
// ---------------------------------------------------------------------------

namespace Tests\Feature;

use App\Models\Account;
use App\Models\AccountType;
use App\Services\VoucherService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\CreatesUsers;

/**
 * Financial reports are computed ONLY from posted journal entries
 * (Phase 01 §10 rule 5). A draft voucher must never appear in a
 * report; a reversed voucher must net to zero.
 *
 * NOTE: the financial report endpoints themselves belong to Group D /
 * Phase 25. These tests assert the data rule through the due reports
 * (Group E) and through direct journal-entry queries, so they stay
 * valid whichever endpoint Group D ships.
 */
class FinancialReportTest extends TestCase
{
    use CreatesUsers, RefreshDatabase;

    protected int $referenceSeq = 7000;

    protected function accounts(): array
    {
        // Tests do not run the seeders; the account types must exist
        // first (accounts.account_type_id is a foreign key).
        $assets = AccountType::firstOrCreate(
            ['name' => 'Assets'], ['normal_balance' => 'debit']
        );
        $incomeType = AccountType::firstOrCreate(
            ['name' => 'Income'], ['normal_balance' => 'credit']
        );

        $cash = Account::firstOrCreate(
            ['code' => '1100'],
            ['name' => 'Cash in Hand', 'account_type_id' => $assets->id, 'normal_balance' => 'debit', 'status' => 'active']
        );
        $income = Account::firstOrCreate(
            ['code' => '4100'],
            ['name' => 'Service Charge Income', 'account_type_id' => $incomeType->id, 'normal_balance' => 'credit', 'status' => 'active']
        );

        return [$cash, $income];
    }

    protected function postVoucher(float $debit, float $credit, string $type = 'receipt'): \App\Models\Voucher
    {
        [$cash, $income] = $this->accounts();

        return app(VoucherService::class)->post([
            'voucher_type' => $type,
            'voucher_date' => now()->toDateString(),
            'branch_id' => $this->makeBranch('BR-FR')->id,
            // reference_id is an unsigned bigint: integers only.
            'reference_type' => 'test',
            'reference_id' => ++$this->referenceSeq,
            'narration' => 'Test',
            'lines' => [
                ['account_id' => $cash->id, 'debit' => $debit, 'credit' => 0],
                ['account_id' => $income->id, 'debit' => 0, 'credit' => $credit],
            ],
        ]);
    }

    public function test_posted_entries_are_the_only_source_of_report_totals(): void
    {
        $this->postVoucher(10000, 10000);

        $total = \App\Models\JournalEntryLine::query()
            ->whereHas('journalEntry.voucher', fn ($q) => $q->where('status', 'posted'))
            ->sum('debit');

        $this->assertEquals(10000, $total);
    }

    public function test_reversed_voucher_nets_to_zero_in_reports(): void
    {
        $service = app(VoucherService::class);
        $voucher = $this->postVoucher(20000, 20000);
        $service->reverse($voucher, 'Correction');

        $net = \App\Models\JournalEntryLine::query()
            ->whereHas('journalEntry.voucher', fn ($q) => $q->whereIn('status', ['posted', 'reversed']))
            ->selectRaw('SUM(debit) - SUM(credit) as net')
            ->value('net');

        // The original and its reversal cancel out.
        $this->assertEquals(0, (float) $net);
    }

    public function test_agent_due_report_only_counts_posted_vouchers(): void
    {
        $branch = $this->makeBranch('BR-FR2');
        $country = $this->makeCountry('FR2');
        $agent = $this->makeAgent($this->makeUser('Agent'), $branch, [$country]);

        [$cash] = $this->accounts();

        // A posted agent-charge line: debit agent sub-ledger 30000.
        app(VoucherService::class)->post([
            'voucher_type' => 'journal',
            'voucher_date' => now()->toDateString(),
            'branch_id' => $branch->id,
            'reference_type' => 'agent_charge',
            'reference_id' => $agent->id,
            'narration' => 'Agent charge',
            'lines' => [
                ['account_id' => $cash->id, 'debit' => 30000, 'credit' => 0, 'sub_ledger_type' => 'agent', 'sub_ledger_id' => $agent->id],
                ['account_id' => $cash->id, 'debit' => 0, 'credit' => 30000],
            ],
        ]);

        $staff = $this->makeUser('Accounts Staff', [], ['report.view', 'ledger.view']);

        $this->actingAs($staff)->getJson('/api/v1/reports/agent-due')
            ->assertOk()
            ->assertJsonPath('data.summary.Total Due', '30,000.00');
    }
}
