<?php

use App\Http\Controllers\BackupController;
use App\Http\Controllers\Reports\ReportController;
use Illuminate\Support\Facades\Route;

// Reports, exports and backups. Every route is permission-guarded at the
// route layer as well as authorised again in the controller/policy.
Route::middleware(['auth'])->group(function () {
    Route::middleware('permission:report.view')->group(function () {
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/{report}', [ReportController::class, 'show'])->name('reports.show');
        Route::get('/reports/{report}/print', [ReportController::class, 'print'])->name('reports.print');
    });

    Route::middleware(['permission:report.view', 'permission:report.export'])->group(function () {
        Route::get('/reports/{report}/pdf', [ReportController::class, 'pdf'])->name('reports.pdf');
        Route::get('/reports/{report}/excel', [ReportController::class, 'excel'])->name('reports.excel');
    });

    Route::get('/backups', [BackupController::class, 'index'])
        ->middleware('permission:backup.view')->name('backups.index');
    Route::post('/backups', [BackupController::class, 'store'])
        ->middleware('permission:backup.create')->name('backups.store');
    Route::get('/backups/{backup}/download', [BackupController::class, 'download'])
        ->middleware('permission:backup.download')->name('backups.download');
    Route::get('/backups/{backup}/restore', [BackupController::class, 'restoreForm'])
        ->middleware('permission:backup.manage')->name('backups.restore-form');
    Route::post('/backups/{backup}/restore', [BackupController::class, 'restore'])
        ->middleware('permission:backup.manage')->name('backups.restore');
    Route::delete('/backups/{backup}', [BackupController::class, 'destroy'])
        ->middleware('permission:backup.delete')->name('backups.destroy');
});
