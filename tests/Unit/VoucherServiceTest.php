<?php

// ---------------------------------------------------------------------------
// NOT EXECUTED HERE: this workspace has no PHP / Composer / MySQL, so this
// test file was written but NOT run. Run on the XAMPP machine with:
//   php artisan test --filter=VoucherServiceTest
// Do not claim it passes until that run succeeds.
// ---------------------------------------------------------------------------

namespace Tests\Unit;

use App\Models\Account;
use App\Models\AccountType;
use App\Services\VoucherService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

/**
 * Unit tests for the double-entry balance rule (Phase 01 §10).
 * Contract: VoucherService::post(array): Voucher,
 * VoucherService::reverse(Voucher, ?string): Voucher.
 * Failures throw ValidationException (the shipped service's contract).
 */
class VoucherServiceTest extends TestCase
{
    use RefreshDatabase;

    protected Account $cash;
    protected Account $income;
    protected int $referenceSeq = 8000;

    protected function setUp(): void
    {
        parent::setUp();

        $assets = AccountType::create(['name' => 'Assets', 'normal_balance' => 'debit']);
        $incomeType = AccountType::create(['name' => 'Income', 'normal_balance' => 'credit']);

        $this->cash = Account::create([
            'code' => '1100', 'name' => 'Cash in Hand',
            'account_type_id' => $assets->id, 'normal_balance' => 'debit', 'status' => 'active',
        ]);
        $this->income = Account::create([
            'code' => '4100', 'name' => 'Service Charge Income',
            'account_type_id' => $incomeType->id, 'normal_balance' => 'credit', 'status' => 'active',
        ]);
    }

    protected function payload(array $overrides = []): array
    {
        return array_merge([
            'voucher_type' => 'receipt',
            'voucher_date' => now()->toDateString(),
            'branch_id' => null, // no branch row exists in this unit test
            'reference_type' => 'test',
            'reference_id' => ++$this->referenceSeq,
            'narration' => 'Unit test voucher',
            'lines' => [
                ['account_id' => $this->cash->id, 'debit' => 1000, 'credit' => 0],
                ['account_id' => $this->income->id, 'debit' => 0, 'credit' => 1000],
            ],
        ], $overrides);
    }

    public function test_post_creates_balanced_journal_entry(): void
    {
        $voucher = app(VoucherService::class)->post($this->payload());

        $this->assertEquals($voucher->journalEntry->lines->sum('debit'), $voucher->journalEntry->lines->sum('credit'));
        $this->assertEquals(1000, $voucher->total_debit);
    }

    public function test_post_rejects_zero_lines(): void
    {
        $this->expectException(ValidationException::class);
        app(VoucherService::class)->post($this->payload(['lines' => []]));
    }

    public function test_post_rejects_single_line_voucher(): void
    {
        $this->expectException(ValidationException::class);
        app(VoucherService::class)->post($this->payload([
            'lines' => [
                ['account_id' => $this->cash->id, 'debit' => 1000, 'credit' => 0],
            ],
        ]));
    }

    public function test_post_rejects_line_with_both_debit_and_credit(): void
    {
        $this->expectException(ValidationException::class);
        app(VoucherService::class)->post($this->payload([
            'lines' => [
                ['account_id' => $this->cash->id, 'debit' => 1000, 'credit' => 500],
                ['account_id' => $this->income->id, 'debit' => 0, 'credit' => 1500],
            ],
        ]));
    }

    public function test_post_rejects_negative_amounts(): void
    {
        $this->expectException(ValidationException::class);
        app(VoucherService::class)->post($this->payload([
            'lines' => [
                ['account_id' => $this->cash->id, 'debit' => -1000, 'credit' => 0],
                ['account_id' => $this->income->id, 'debit' => 0, 'credit' => -1000],
            ],
        ]));
    }

    public function test_voucher_numbers_are_unique(): void
    {
        $service = app(VoucherService::class);
        $first = $service->post($this->payload());
        $second = $service->post($this->payload());

        $this->assertNotSame($first->voucher_number, $second->voucher_number);
    }

    public function test_reverse_creates_mirror_lines(): void
    {
        $service = app(VoucherService::class);
        $voucher = $service->post($this->payload());
        $reversal = $service->reverse($voucher, 'Unit test reversal');

        $originalLines = $voucher->journalEntry->lines->sortBy('account_id')->values();
        $reversalLines = $reversal->journalEntry->lines->sortBy('account_id')->values();

        foreach ($originalLines as $i => $line) {
            $this->assertEquals($line->debit, $reversalLines[$i]->credit);
            $this->assertEquals($line->credit, $reversalLines[$i]->debit);
        }

        // The original voucher points at its reversal.
        $this->assertSame($reversal->id, $voucher->fresh()->reversal_voucher_id);
    }

    public function test_balance_check_is_pure(): void
    {
        $service = app(VoucherService::class);

        $this->assertTrue($service->balanceCheck([
            ['account_id' => $this->cash->id, 'debit' => 100, 'credit' => 0],
            ['account_id' => $this->income->id, 'debit' => 0, 'credit' => 100],
        ]));
        $this->assertFalse($service->balanceCheck([
            ['account_id' => $this->cash->id, 'debit' => 100, 'credit' => 0],
            ['account_id' => $this->income->id, 'debit' => 0, 'credit' => 99],
        ]));
    }
}
