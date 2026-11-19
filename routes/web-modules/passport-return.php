<?php

use App\Http\Controllers\PassportReturnController;
use App\Http\Controllers\PassportReceivedController;
use Illuminate\Support\Facades\Route;

Route::get('passport-received', [PassportReceivedController::class, 'index'])
    ->middleware(['auth', 'permission:passport-return.view'])
    ->name('passport-received.index');

Route::middleware(['auth', 'permission:passport-return.create'])->group(function () {
    Route::get('passport-returns/create', [PassportReturnController::class, 'create'])->name('passport-returns.create');
    Route::post('passport-returns', [PassportReturnController::class, 'store'])->name('passport-returns.store');
});
Route::middleware(['auth', 'permission:passport-return.view'])->group(function () {
    Route::get('passport-returns', [PassportReturnController::class, 'index'])->name('passport-returns.index');
    Route::get('passport-returns/{passportReturn}', [PassportReturnController::class, 'show'])->name('passport-returns.show')->whereNumber('passportReturn');
});
Route::middleware(['auth', 'permission:passport-return.edit'])->group(function () {
    Route::get('passport-returns/{passportReturn}/edit', [PassportReturnController::class, 'edit'])->name('passport-returns.edit');
    Route::put('passport-returns/{passportReturn}', [PassportReturnController::class, 'update'])->name('passport-returns.update');
    Route::post('passport-returns/{passportReturn}/status', [PassportReturnController::class, 'changeStatus'])->name('passport-returns.status');
    Route::delete('passport-returns/{passportReturn}', [PassportReturnController::class, 'destroy'])->name('passport-returns.destroy');
});
Route::middleware(['auth', 'permission:passport-return.download'])->group(function () {
    Route::get('passport-returns/{passportReturn}/download/{field}', [PassportReturnController::class, 'download'])->name('passport-returns.download');
});
