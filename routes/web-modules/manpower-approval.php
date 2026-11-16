<?php

use App\Http\Controllers\ManpowerApprovalController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'permission:manpower-approval.create'])->group(function () {
    Route::get('manpower-approvals/create', [ManpowerApprovalController::class, 'create'])->name('manpower-approvals.create');
    Route::post('manpower-approvals', [ManpowerApprovalController::class, 'store'])->name('manpower-approvals.store');
});
Route::middleware(['auth', 'permission:manpower-approval.view'])->group(function () {
    Route::get('manpower-approvals', [ManpowerApprovalController::class, 'index'])->name('manpower-approvals.index');
    Route::get('manpower-approvals/{manpowerApproval}', [ManpowerApprovalController::class, 'show'])->name('manpower-approvals.show')->whereNumber('manpowerApproval');
});
Route::middleware(['auth', 'permission:manpower-approval.edit'])->group(function () {
    Route::get('manpower-approvals/{manpowerApproval}/edit', [ManpowerApprovalController::class, 'edit'])->name('manpower-approvals.edit');
    Route::put('manpower-approvals/{manpowerApproval}', [ManpowerApprovalController::class, 'update'])->name('manpower-approvals.update');
    Route::post('manpower-approvals/{manpowerApproval}/status', [ManpowerApprovalController::class, 'changeStatus'])->name('manpower-approvals.status');
    Route::delete('manpower-approvals/{manpowerApproval}', [ManpowerApprovalController::class, 'destroy'])->name('manpower-approvals.destroy');
});
Route::middleware(['auth', 'permission:manpower-approval.download'])->group(function () {
    Route::get('manpower-approvals/{manpowerApproval}/download/{field}', [ManpowerApprovalController::class, 'download'])->name('manpower-approvals.download');
});
