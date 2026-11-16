<?php

// ---------------------------------------------------------------------------
// NOT EXECUTED HERE: this workspace has no PHP / Composer / MySQL, so this
// test file was written but NOT run. Run on the XAMPP machine with:
//   php artisan test --filter=AccountingTest
// Do not claim it passes until that run succeeds.
// ---------------------------------------------------------------------------

namespace Tests\Feature;

use App\Models\Account;
use App\Models\AccountType;
use App\Models\Voucher;
use App\Services\VoucherService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;
use Tests\Traits\CreatesUsers;

/**
 * Double-entry accounting (Phase 01 §10). Contract under test:
 *   VoucherService::post(array): Voucher — creates AND posts in one
 *   step; there is no draft-posting flow. A voucher is posted at
 *   creation; corrections are reversal vouchers only.
 *   VoucherService::reverse(Voucher, ?string): Voucher
 * Rules: debit must equal credit; duplicate posting of the same
 * reference is rejected; posted vouchers are immutable and are
 * corrected only by a reversal voucher. Failures throw
 * ValidationException (the shipped service's contract).
 */
class AccountingTest extends TestCase
{
    use CreatesUsers, RefreshDatabase;

    protected Account $cash;
    protected Account $income;
    protected int $referenceSeq = 9000;

    protected function setUp(): void
    {
        parent::setUp();

        // Tests do not run the seeders, so the account types must exist
        // here first (accounts.account_type_id is a foreign key).
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

    protected function balancedPayload(?int $referenceId = null): array
    {
        return [
            'voucher_type' => 'receipt',
            'voucher_date' => now()->toDateString(),
            'branch_id' => $this->makeBranch('BR-AC')->id,
            // reference_id is an unsigned bigint in the schema: integers only.
            'reference_type' => 'test',
            'reference_id' => $referenceId ?? ++$this->referenceSeq,
            'narration' => 'Service charge received',
            'lines' => [
                ['account_id' => $this->cash->id, 'debit' => 50000, 'credit' => 0],
                ['account_id' => $this->income->id, 'debit' => 0, 'credit' => 50000],
            ],
        ];
    }

    public function test_balanced_voucher_posts_successfully(): void
    {
        $voucher = app(VoucherService::class)->post($this->balancedPayload());

        $this->assertSame('posted', $voucher->status);
        $this->assertEquals(50000, $voucher->total_debit);
        $this->assertEquals(50000, $voucher->total_credit);
        $this->assertDatabaseHas('journal_entries', ['voucher_id' => $voucher->id]);
        $this->assertDatabaseCount('journal_entry_lines', 2);
    }

    public function test_unbalanced_voucher_is_rejected_and_nothing_is_saved(): void
    {
        $payload = $this->balancedPayload();
        $payload['lines'][1]['credit'] = 40000; // debit 50000, credit 40000

        $this->expectException(ValidationException::class);
        app(VoucherService::class)->post($payload);

        $this->assertDatabaseCount('vouchers', 0);
        $this->assertDatabaseCount('journal_entries', 0);
    }

    public function test_duplicate_posting_of_same_reference_is_rejected(): void
    {
        $service = app(VoucherService::class);
        $service->post($this->balancedPayload(9101));

        $this->expectException(ValidationException::class);
        $service->post($this->balancedPayload(9101));

        $this->assertDatabaseCount('vouchers', 1);
    }

    public function test_posted_voucher_is_reversed_not_edited(): void
    {
        $service = app(VoucherService::class);
        $voucher = $service->post($this->balancedPayload(9102));

        $reversal = $service->reverse($voucher, 'Wrong amount entered');

        // The ORIGINAL carries the link to its reversal; both stay visible.
        $this->assertSame('reversed', $voucher->fresh()->status);
        $this->assertSame($reversal->id, $voucher->fresh()->reversal_voucher_id);
        $this->assertSame('posted', $reversal->status);
        $this->assertEquals(50000, $reversal->total_debit);
        $this->assertEquals(50000, $reversal->total_credit);

        $this->assertDatabaseHas('vouchers', ['id' => $voucher->id]);
        $this->assertDatabaseHas('vouchers', ['id' => $reversal->id]);
    }

    public function test_reversing_an_already_reversed_voucher_is_rejected(): void
    {
        $service = app(VoucherService::class);
        $voucher = $service->post($this->balancedPayload(9103));
        $service->reverse($voucher, 'First reversal');

        $this->expectException(ValidationException::class);
        $service->reverse($voucher->fresh(), 'Second reversal');
    }

    public function test_every_journal_line_has_exactly_one_side_positive(): void
    {
        $voucher = app(VoucherService::class)->post($this->balancedPayload(9104));

        $lines = $voucher->journalEntry->lines;
        foreach ($lines as $line) {
            $this->assertTrue(
                ($line->debit > 0 && $line->credit == 0) || ($line->credit > 0 && $line->debit == 0),
                'Each line must have exactly one of debit/credit greater than zero.'
            );
        }
    }

    public function test_accounting_api_rejects_unbalanced_voucher_with_422(): void
    {
        // Group D's API: POST /api/v1/accounting/vouchers (create = post).
        $staff = $this->makeUser('Accounts Staff', [], ['voucher.create']);

        $payload = $this->balancedPayload(9105);
        $payload['lines'][1]['credit'] = 40000;

        $this->actingAs($staff)
            ->postJson('/api/v1/accounting/vouchers', $payload)
            ->assertStatus(422)
            ->assertJsonValidationErrors('lines');
    }

    public function test_accounting_api_create_requires_voucher_create_permission(): void
    {
        $staff = $this->makeUser('Accounts Staff', [], ['voucher.view']);

        $this->actingAs($staff)
            ->postJson('/api/v1/accounting/vouchers', $this->balancedPayload(9106))
            ->assertForbidden();
    }
}
