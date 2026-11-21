<?php

use App\Http\Controllers\Reports\FinancialReportController;
use App\Http\Controllers\Reports\SubLedgerController;
use Illuminate\Support\Facades\Route;

/**
 * Financial reports web routes (Phase 25). Glob-loaded by routes/web.php
 * (Group A). Exports (?export=pdf|excel) run through the same
 * permission check as the report itself.
 */
Route::middleware([
    'auth',
    'verified',
    'permission:report.view',
    // Financial reports are staff-only. Agent / Passport Holder users have
    // their own scoped sub-ledger in My Portal and must never see company-wide
    // accounts, even by typing this URL directly.
    'role:Super Admin|Admin|Branch Manager|Accounts Staff|General Staff',
])->prefix('reports/financial')->name('reports.financial.')->group(function () {
    Route::get('trial-balance', [FinancialReportController::class, 'trialBalance'])->name('trial-balance');
    Route::get('general-ledger', [FinancialReportController::class, 'generalLedger'])->name('general-ledger');
    Route::get('account-ledger/{account}', [FinancialReportController::class, 'accountLedger'])->name('account-ledger');
    Route::get('balance-sheet', [FinancialReportController::class, 'balanceSheet'])->name('balance-sheet');
    Route::get('income-statement', [FinancialReportController::class, 'incomeStatement'])->name('income-statement');
    Route::get('cash-book', [FinancialReportController::class, 'cashBook'])->name('cash-book');
    Route::get('bank-book', [FinancialReportController::class, 'bankBook'])->name('bank-book');
    Route::get('day-book', [FinancialReportController::class, 'dayBook'])->name('day-book');

    Route::get('sub-ledgers/{type}/{id}', [SubLedgerController::class, 'show'])->whereNumber('id')->name('sub-ledger');
    Route::get('sub-ledgers/{type}/due-list', [SubLedgerController::class, 'dueList'])->name('sub-ledger.due-list');
    Route::get('sub-ledgers/vendors/create', [SubLedgerController::class, 'createVendor'])->name('sub-ledger.vendor.create')->middleware('permission:voucher.create');
    Route::post('sub-ledgers/vendors', [SubLedgerController::class, 'storeVendor'])->name('sub-ledger.vendor.store')->middleware('permission:voucher.create');
    Route::get('sub-ledgers/{type}/{id}/transactions/{action}', [SubLedgerController::class, 'transactionForm'])
        ->whereNumber('id')->whereIn('type', ['agent', 'passport_holder', 'vendor'])->whereIn('action', ['receive', 'pay'])
        ->name('sub-ledger.transaction.create')->middleware('permission:voucher.create');
    Route::post('sub-ledgers/{type}/{id}/transactions/{action}', [SubLedgerController::class, 'storeTransaction'])
        ->whereNumber('id')->whereIn('type', ['agent', 'passport_holder', 'vendor'])->whereIn('action', ['receive', 'pay'])
        ->name('sub-ledger.transaction.store')->middleware('permission:voucher.create');
});
