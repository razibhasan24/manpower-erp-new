<?php

use App\Http\Controllers\PoliceClearanceController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'permission:police-clearance.create'])->group(function () {
    Route::get('police-clearances/create', [PoliceClearanceController::class, 'create'])->name('police-clearances.create');
    Route::post('police-clearances', [PoliceClearanceController::class, 'store'])->name('police-clearances.store');
});
Route::middleware(['auth', 'permission:police-clearance.view'])->group(function () {
    Route::get('police-clearances', [PoliceClearanceController::class, 'index'])->name('police-clearances.index');
    Route::get('police-clearances/{policeClearance}', [PoliceClearanceController::class, 'show'])->name('police-clearances.show')->whereNumber('policeClearance');
});
Route::middleware(['auth', 'permission:police-clearance.edit'])->group(function () {
    Route::get('police-clearances/{policeClearance}/edit', [PoliceClearanceController::class, 'edit'])->name('police-clearances.edit');
    Route::put('police-clearances/{policeClearance}', [PoliceClearanceController::class, 'update'])->name('police-clearances.update');
    Route::post('police-clearances/{policeClearance}/status', [PoliceClearanceController::class, 'changeStatus'])->name('police-clearances.status');
    Route::delete('police-clearances/{policeClearance}', [PoliceClearanceController::class, 'destroy'])->name('police-clearances.destroy');
});
Route::middleware(['auth', 'permission:police-clearance.download'])->group(function () {
    Route::get('police-clearances/{policeClearance}/download/{field}', [PoliceClearanceController::class, 'download'])->name('police-clearances.download');
});
