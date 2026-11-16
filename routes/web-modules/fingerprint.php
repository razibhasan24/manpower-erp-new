<?php

use App\Http\Controllers\FingerprintController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'permission:fingerprint.create'])->group(function () {
    Route::get('fingerprints/create', [FingerprintController::class, 'create'])->name('fingerprints.create');
    Route::post('fingerprints', [FingerprintController::class, 'store'])->name('fingerprints.store');
});
Route::middleware(['auth', 'permission:fingerprint.view'])->group(function () {
    Route::get('fingerprints', [FingerprintController::class, 'index'])->name('fingerprints.index');
    Route::get('fingerprints/{fingerprint}', [FingerprintController::class, 'show'])->name('fingerprints.show')->whereNumber('fingerprint');
});
Route::middleware(['auth', 'permission:fingerprint.edit'])->group(function () {
    Route::get('fingerprints/{fingerprint}/edit', [FingerprintController::class, 'edit'])->name('fingerprints.edit');
    Route::put('fingerprints/{fingerprint}', [FingerprintController::class, 'update'])->name('fingerprints.update');
    Route::post('fingerprints/{fingerprint}/status', [FingerprintController::class, 'changeStatus'])->name('fingerprints.status');
    Route::delete('fingerprints/{fingerprint}', [FingerprintController::class, 'destroy'])->name('fingerprints.destroy');
});
Route::middleware(['auth', 'permission:fingerprint.download'])->group(function () {
    Route::get('fingerprints/{fingerprint}/download/{field}', [FingerprintController::class, 'download'])->name('fingerprints.download');
});
