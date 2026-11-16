<?php

use App\Http\Controllers\DelegationController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'permission:delegation.create'])->group(function () {
    Route::get('delegations/create', [DelegationController::class, 'create'])->name('delegations.create');
    Route::post('delegations', [DelegationController::class, 'store'])->name('delegations.store');
});
Route::middleware(['auth', 'permission:delegation.view'])->group(function () {
    Route::get('delegations', [DelegationController::class, 'index'])->name('delegations.index');
    Route::get('delegations/{delegation}', [DelegationController::class, 'show'])->name('delegations.show')->whereNumber('delegation');
});
Route::middleware(['auth', 'permission:delegation.edit'])->group(function () {
    Route::get('delegations/{delegation}/edit', [DelegationController::class, 'edit'])->name('delegations.edit');
    Route::put('delegations/{delegation}', [DelegationController::class, 'update'])->name('delegations.update');
    Route::post('delegations/{delegation}/status', [DelegationController::class, 'changeStatus'])->name('delegations.status');
    Route::delete('delegations/{delegation}', [DelegationController::class, 'destroy'])->name('delegations.destroy');
});
Route::middleware(['auth', 'permission:delegation.download'])->group(function () {
    Route::get('delegations/{delegation}/download/{field}', [DelegationController::class, 'download'])->name('delegations.download');
});
