<?php

use App\Http\Controllers\Accounting\AccountController;
use App\Http\Controllers\Accounting\AccountTypeController;
use App\Http\Controllers\Accounting\JournalEntryController;
use App\Http\Controllers\Accounting\VoucherController;
use App\Http\Controllers\Reports\FinancialReportController;
use App\Http\Controllers\Reports\SubLedgerController;
use Illuminate\Support\Facades\Route;

/**
 * Accounting + financial reports API routes, v1 (Phases 23–25).
 * Glob-loaded by routes/api.php (Group A). Same policies as web.
 */
Route::middleware(['auth:sanctum'])->prefix('v1/accounting')->group(function () {
    Route::apiResource('account-types', AccountTypeController::class)->only(['index', 'show']);
    Route::apiResource('accounts', AccountController::class);
    Route::apiResource('vouchers', VoucherController::class)->only(['index', 'store', 'show']);
    Route::post('vouchers/operation', [VoucherController::class, 'storeOperation']);
    Route::post('vouchers/{voucher}/reverse', [VoucherController::class, 'reverse']);
    Route::apiResource('journal-entries', JournalEntryController::class)->only(['index', 'show']);

    Route::get('reports/trial-balance', [FinancialReportController::class, 'trialBalance']);
    Route::get('reports/general-ledger', [FinancialReportController::class, 'generalLedger']);
    Route::get('reports/balance-sheet', [FinancialReportController::class, 'balanceSheet']);
    Route::get('reports/income-statement', [FinancialReportController::class, 'incomeStatement']);
    Route::get('reports/cash-book', [FinancialReportController::class, 'cashBook']);
    Route::get('reports/bank-book', [FinancialReportController::class, 'bankBook']);
    Route::get('reports/day-book', [FinancialReportController::class, 'dayBook']);
    Route::get('reports/sub-ledgers/{type}/{id}', [SubLedgerController::class, 'show']);
    Route::get('reports/sub-ledgers/{type}/due-list', [SubLedgerController::class, 'dueList']);
});
