<?php

// ---------------------------------------------------------------------------
// NOT EXECUTED HERE: this workspace has no PHP / Composer / MySQL, so this
// test file was written but NOT run. Run on the XAMPP machine with:
//   php artisan test --filter=ReportServiceTest
// Do not claim it passes until that run succeeds.
// ---------------------------------------------------------------------------

namespace Tests\Unit;

use App\Models\Recruitment;
use App\Services\ReportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\CreatesUsers;

class ReportServiceTest extends TestCase
{
    use CreatesUsers, RefreshDatabase;

    public function test_registry_contains_all_fifteen_non_financial_reports(): void
    {
        $keys = array_keys(ReportService::definitions());

        foreach ([
            'recruitment', 'agent', 'passport-holder', 'country', 'selection',
            'medical', 'police-clearance', 'visa', 'return', 'flight',
            'agent-due', 'candidate-due', 'hrm', 'payroll', 'branch',
        ] as $expected) {
            $this->assertContains($expected, $keys);
        }
    }

    public function test_financial_reports_are_not_in_this_registry(): void
    {
        // Financial reports (trial balance, balance sheet, income
        // statement, general ledger, cash book, bank book, day book)
        // belong to Group D / Phase 25 and must not be duplicated here.
        $keys = array_keys(ReportService::definitions());

        foreach (['trial-balance', 'balance-sheet', 'income-statement', 'general-ledger', 'cash-book', 'bank-book', 'day-book'] as $forbidden) {
            $this->assertNotContains($forbidden, $keys);
        }
    }

    public function test_every_definition_has_columns_and_a_permission(): void
    {
        foreach (ReportService::definitions() as $key => $definition) {
            $this->assertNotEmpty($definition['columns'], "Report {$key} has no columns.");
            $this->assertNotEmpty($definition['permission'], "Report {$key} has no permission.");
            $this->assertNotEmpty($definition['title'], "Report {$key} has no title.");
        }
    }

    public function test_unknown_report_throws_404(): void
    {
        $this->expectException(\Symfony\Component\HttpKernel\Exception\NotFoundHttpException::class);

        ReportService::definition('no-such-report');
    }

    public function test_date_filter_narrows_recruitment_report(): void
    {
        $branch = $this->makeBranch('BR-RS');
        $country = $this->makeCountry('RST');
        $agent = $this->makeAgent($this->makeUser('Agent'), $branch, [$country]);
        $holder = $this->makePassportHolder($this->makeUser('Passport Holder'), $agent, $branch, $country);

        $recruitment = Recruitment::create([
            'tracking_number' => '2026-BR-RS-000001',
            'passport_holder_id' => $holder->id,
            'agent_id' => $agent->id,
            'branch_id' => $branch->id,
            'country_id' => $country->id,
            'current_stage' => 'registration',
            'current_status' => 'pending',
        ]);
        // created_at is not mass-assignable; set it directly.
        $recruitment->created_at = '2026-01-15 10:00:00';
        $recruitment->save();

        $admin = $this->makeSuperAdmin();
        $service = app(ReportService::class);

        $all = $service->run('recruitment', $admin, []);
        $this->assertCount(1, $all['rows']);

        $filtered = $service->run('recruitment', $admin, [
            'date_from' => '2026-02-01',
            'date_to' => '2026-02-28',
        ]);
        $this->assertCount(0, $filtered['rows']);
    }

    public function test_report_rows_use_plain_arrays_with_column_keys(): void
    {
        $admin = $this->makeSuperAdmin();
        $result = app(ReportService::class)->run('branch', $admin, []);

        $this->assertArrayHasKey('columns', $result);
        $this->assertArrayHasKey('rows', $result);
        $this->assertArrayHasKey('summary', $result);

        foreach ($result['rows'] as $row) {
            $this->assertSame(array_keys($result['columns']), array_keys($row));
        }
    }
}
