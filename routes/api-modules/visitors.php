<?php

use App\Http\Controllers\Visitor\VisitorController;
use Illuminate\Support\Facades\Route;

/**
 * Visitor API routes, v1 (Phase 27). Glob-loaded by routes/api.php (Group A).
 */
Route::middleware(['auth:sanctum'])->prefix('v1/visitors')->group(function () {
    Route::apiResource('/', VisitorController::class)
        ->parameters(['' => 'visitor'])
        ->only(['index', 'store', 'show']);
    Route::post('{visitor}/check-out', [VisitorController::class, 'checkOut']);
    Route::get('reports/daily', [VisitorController::class, 'dailyReport']);
    Route::get('reports/monthly', [VisitorController::class, 'monthlyReport']);
});
