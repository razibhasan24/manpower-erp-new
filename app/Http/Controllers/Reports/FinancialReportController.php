<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Services\FinancialReportExportService;
use App\Services\FinancialReportService;
use App\Services\SubLedgerService;
use Illuminate\Http\Request;

/**
 * Ledgers & Financial Reports (Phase 25). All reports are computed
 * from POSTED journal entries only. Date/branch/account filters on
 * every report; PDF and Excel exports actually export (via
 * FinancialReportExportService), they are not dead buttons.
 */
class FinancialReportController extends Controller
{
    public function __construct(
        protected FinancialReportService $reports,
        protected SubLedgerService $ledgers,
        protected FinancialReportExportService $exporter
    ) {}

    protected function filters(Request $request): array
    {
        return $request->only(['from', 'to', 'branch_id', 'account_id']);
    }

    protected function maybeExport(Request $request, string $title, string $pdfView, array $data, array $headings, array $rows)
    {
        return match ($request->query('export')) {
            'pdf' => $this->exporter->exportPdf($title, $pdfView, $data),
            'excel' => $this->exporter->exportExcel($title, $headings, $rows),
            default => null,
        };
    }

    public function trialBalance(Request $request)
    {
        $this->authorize('viewAny', \App\Models\Voucher::class);
        $data = $this->reports->trialBalance($this->filters($request));

        $export = $this->maybeExport($request, 'Trial Balance', 'reports.financial.pdf.trial-balance',
            ['rows' => $data['rows'], 'filters' => $this->filters($request)],
            ['Code', 'Account', 'Debit', 'Credit'],
            array_map(fn ($r) => [$r['code'], $r['name'], $r['debit'], $r['credit']], $data['rows']));
        if ($export) {
            return $export;
        }

        return view('reports.financial.trial-balance', array_merge($data, ['filters' => $this->filters($request)]));
    }

    public function generalLedger(Request $request)
    {
        $this->authorize('viewAny', \App\Models\Voucher::class);
        $accounts = $this->reports->generalLedger($this->filters($request));

        return view('reports.financial.general-ledger', ['accounts' => $accounts, 'filters' => $this->filters($request)]);
    }

    public function accountLedger(Request $request, Account $account)
    {
        $this->authorize('view', $account);
        $ledger = $this->reports->accountLedger($account->id, $this->filters($request));

        return view('reports.financial.account-ledger', ['ledger' => $ledger, 'account' => $account, 'filters' => $this->filters($request)]);
    }

    public function balanceSheet(Request $request)
    {
        $this->authorize('viewAny', \App\Models\Voucher::class);
        $data = $this->reports->balanceSheet($this->filters($request));

        $export = $this->maybeExport($request, 'Balance Sheet', 'reports.financial.pdf.balance-sheet',
            array_merge($data, ['filters' => $this->filters($request)]),
            ['Code', 'Account', 'Amount'],
            array_map(fn ($r) => [$r['code'], $r['name'], $r['amount']], array_merge($data['assets']->all(), $data['liabilities']->all(), $data['equity']->all())));
        if ($export) {
            return $export;
        }

        return view('reports.financial.balance-sheet', array_merge($data, ['filters' => $this->filters($request)]));
    }

    public function incomeStatement(Request $request)
    {
        $this->authorize('viewAny', \App\Models\Voucher::class);
        $data = $this->reports->incomeStatement($this->filters($request));

        $export = $this->maybeExport($request, 'Income Statement', 'reports.financial.pdf.income-statement',
            array_merge($data, ['filters' => $this->filters($request)]),
            ['Code', 'Account', 'Amount'],
            array_map(fn ($r) => [$r['code'], $r['name'], $r['amount']], array_merge($data['income']->all(), $data['expenses']->all())));
        if ($export) {
            return $export;
        }

        return view('reports.financial.income-statement', array_merge($data, ['filters' => $this->filters($request)]));
    }

    public function cashBook(Request $request)
    {
        $this->authorize('viewAny', \App\Models\Voucher::class);
        $data = $this->reports->cashBook($this->filters($request));

        return view('reports.financial.cash-book', array_merge($data, ['filters' => $this->filters($request)]));
    }

    public function bankBook(Request $request)
    {
        $this->authorize('viewAny', \App\Models\Voucher::class);
        $data = $this->reports->bankBook($this->filters($request));

        return view('reports.financial.bank-book', array_merge($data, ['filters' => $this->filters($request)]));
    }

    public function dayBook(Request $request)
    {
        $this->authorize('viewAny', \App\Models\Voucher::class);
        $data = $this->reports->dayBook($this->filters($request));

        $export = $this->maybeExport($request, 'Day Book', 'reports.financial.pdf.day-book',
            array_merge($data, ['filters' => $this->filters($request)]),
            ['Voucher', 'Type', 'Date', 'Narration', 'Debit', 'Credit'],
            array_map(fn ($v) => [$v['voucher_number'], $v['voucher_type'], $v['voucher_date'], $v['narration'], $v['total_debit'], $v['total_credit']], $data['vouchers']));
        if ($export) {
            return $export;
        }

        return view('reports.financial.day-book', array_merge($data, ['filters' => $this->filters($request)]));
    }
}
