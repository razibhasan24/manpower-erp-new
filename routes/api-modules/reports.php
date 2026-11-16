<?php

use App\Http\Controllers\Api\V1\BackupApiController;
use App\Http\Controllers\Api\V1\ReportApiController;
use Illuminate\Support\Facades\Route;

/*
| API v1 — reports and backups (Phase 30).
| Reports: same permission checks and scoped rows as the web reports.
| Backups: history + manual creation only. Download and restore stay
| on the web UI (Super Admin only; restore needs typed confirmation).
*/

Route::prefix('v1')->middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
    Route::get('/reports', [ReportApiController::class, 'index'])->name('api.v1.reports.index');
    Route::get('/reports/{report}', [ReportApiController::class, 'show'])->name('api.v1.reports.show');

    Route::get('/backups', [BackupApiController::class, 'index'])->name('api.v1.backups.index');
    Route::post('/backups', [BackupApiController::class, 'store'])->name('api.v1.backups.store');
});
