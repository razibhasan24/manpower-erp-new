<?php

use App\Http\Controllers\MedicalController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'permission:medical.create'])->group(function () {
    Route::get('medicals/create', [MedicalController::class, 'create'])->name('medicals.create');
    Route::post('medicals', [MedicalController::class, 'store'])->name('medicals.store');
});
Route::middleware(['auth', 'permission:medical.view'])->group(function () {
    Route::get('medicals', [MedicalController::class, 'index'])->name('medicals.index');
    Route::get('medicals/{medical}', [MedicalController::class, 'show'])->name('medicals.show')->whereNumber('medical');
});
Route::middleware(['auth', 'permission:medical.edit'])->group(function () {
    Route::get('medicals/{medical}/edit', [MedicalController::class, 'edit'])->name('medicals.edit');
    Route::put('medicals/{medical}', [MedicalController::class, 'update'])->name('medicals.update');
    Route::post('medicals/{medical}/status', [MedicalController::class, 'changeStatus'])->name('medicals.status');
    Route::delete('medicals/{medical}', [MedicalController::class, 'destroy'])->name('medicals.destroy');
});
Route::middleware(['auth', 'permission:medical.download'])->group(function () {
    Route::get('medicals/{medical}/download/{field}', [MedicalController::class, 'download'])->name('medicals.download');
});
