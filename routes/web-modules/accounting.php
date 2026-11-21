<?php

use App\Http\Controllers\Accounting\AccountController;
use App\Http\Controllers\Accounting\AccountTypeController;
use App\Http\Controllers\Accounting\JournalEntryController;
use App\Http\Controllers\Accounting\VoucherController;
use App\Http\Controllers\Accounting\PaymentModeController;
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

    Route::resource('payment-modes', PaymentModeController::class)
        ->only(['index', 'create', 'store', 'edit', 'update', 'destroy'])
        ->middleware('permission:account.view');

    Route::get('vouchers', [VoucherController::class, 'index'])->name('vouchers.index')->middleware('permission:voucher.view');
    Route::get('vouchers/create', [VoucherController::class, 'create'])->name('vouchers.create')->middleware('permission:voucher.create');
    Route::post('vouchers', [VoucherController::class, 'store'])->name('vouchers.store')->middleware('permission:voucher.create');
    Route::get('vouchers/{voucher}', [VoucherController::class, 'show'])->name('vouchers.show');
    Route::get('vouchers/{voucher}/attachment', [VoucherController::class, 'downloadAttachment'])->name('vouchers.attachment');
    Route::get('vouchers/{voucher}/edit', [VoucherController::class, 'edit'])->name('vouchers.edit')->middleware('permission:voucher.edit');
    Route::put('vouchers/{voucher}', [VoucherController::class, 'update'])->name('vouchers.update')->middleware('permission:voucher.edit');
    Route::post('vouchers/{voucher}/approve', [VoucherController::class, 'approve'])->name('vouchers.approve')->middleware('permission:voucher.approve');
    Route::post('vouchers/{voucher}/reject', [VoucherController::class, 'reject'])->name('vouchers.reject')->middleware('permission:voucher.reject');
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
