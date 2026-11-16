<?php

use App\Http\Controllers\VisaReturnController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'permission:visa-return.create'])->group(function () {
    Route::get('visa-returns/create', [VisaReturnController::class, 'create'])->name('visa-returns.create');
    Route::post('visa-returns', [VisaReturnController::class, 'store'])->name('visa-returns.store');
});
Route::middleware(['auth', 'permission:visa-return.view'])->group(function () {
    Route::get('visa-returns', [VisaReturnController::class, 'index'])->name('visa-returns.index');
    Route::get('visa-returns/{visaReturn}', [VisaReturnController::class, 'show'])->name('visa-returns.show')->whereNumber('visaReturn');
});
Route::middleware(['auth', 'permission:visa-return.edit'])->group(function () {
    Route::get('visa-returns/{visaReturn}/edit', [VisaReturnController::class, 'edit'])->name('visa-returns.edit');
    Route::put('visa-returns/{visaReturn}', [VisaReturnController::class, 'update'])->name('visa-returns.update');
    Route::post('visa-returns/{visaReturn}/status', [VisaReturnController::class, 'changeStatus'])->name('visa-returns.status');
    Route::delete('visa-returns/{visaReturn}', [VisaReturnController::class, 'destroy'])->name('visa-returns.destroy');
});
Route::middleware(['auth', 'permission:visa-return.download'])->group(function () {
    Route::get('visa-returns/{visaReturn}/download/{field}', [VisaReturnController::class, 'download'])->name('visa-returns.download');
});
