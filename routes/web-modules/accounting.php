<?php

use App\Http\Controllers\Accounting\AccountController;
use App\Http\Controllers\Accounting\AccountTypeController;
use App\Http\Controllers\Accounting\JournalEntryController;
use App\Http\Controllers\Accounting\VoucherController;
use Illuminate\Support\Facades\Route;

/**
 * Accounting web routes (Phases 23–24). Glob-loaded by routes/web.php
 * (Group A). All behind auth + permission middleware.
 * Note: vouchers have NO edit/update/destroy routes — posted vouchers
 * are immutable; correction is via POST .../reverse.
 */
Route::middleware(['auth', 'verified'])->prefix('accounting')->name('accounting.')->group(function () {
    Route::resource('account-types', AccountTypeController::class)
        ->only(['index', 'show'])
        ->middleware('permission:account_type.view');

    Route::resource('accounts', AccountController::class)
        ->middleware('permission:account.view');

    Route::resource('vouchers', VoucherController::class)
        ->only(['index', 'create', 'store', 'show'])
        ->middleware('permission:voucher.view');
    Route::post('vouchers/operation', [VoucherController::class, 'storeOperation'])
        ->name('vouchers.operation')
        ->middleware('permission:voucher.create');
    Route::post('vouchers/{voucher}/reverse', [VoucherController::class, 'reverse'])
        ->name('vouchers.reverse')
        ->middleware('permission:voucher.reverse');

    Route::resource('journal-entries', JournalEntryController::class)
        ->only(['index', 'show'])
        ->middleware('permission:voucher.view');
});
