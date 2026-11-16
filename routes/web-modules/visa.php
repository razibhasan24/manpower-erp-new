<?php

use App\Http\Controllers\VisaController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'permission:visa.create'])->group(function () {
    Route::get('visas/create', [VisaController::class, 'create'])->name('visas.create');
    Route::post('visas', [VisaController::class, 'store'])->name('visas.store');
});
Route::middleware(['auth', 'permission:visa.view'])->group(function () {
    Route::get('visas', [VisaController::class, 'index'])->name('visas.index');
    Route::get('visas/{visa}', [VisaController::class, 'show'])->name('visas.show')->whereNumber('visa');
});
Route::middleware(['auth', 'permission:visa.edit'])->group(function () {
    Route::get('visas/{visa}/edit', [VisaController::class, 'edit'])->name('visas.edit');
    Route::put('visas/{visa}', [VisaController::class, 'update'])->name('visas.update');
    Route::post('visas/{visa}/status', [VisaController::class, 'changeStatus'])->name('visas.status');
    Route::delete('visas/{visa}', [VisaController::class, 'destroy'])->name('visas.destroy');
});
Route::middleware(['auth', 'permission:visa.download'])->group(function () {
    Route::get('visas/{visa}/download/{field}', [VisaController::class, 'download'])->name('visas.download');
});

Route::middleware(['auth', 'permission:visa.edit'])->group(function () {
    Route::post('visas/{visa}/mofa', [\App\Http\Controllers\VisaController::class, 'updateMofa'])->name('visas.mofa');
    Route::post('visas/{visa}/work-permit', [\App\Http\Controllers\VisaController::class, 'storeWorkPermit'])->name('visas.work-permit');
    Route::post('visas/{visa}/appointment', [\App\Http\Controllers\VisaController::class, 'storeAppointment'])->name('visas.appointment');
});
