<?php

// Position API routes (Phases 08-12, /api/v1). Loaded by routes/api.php
// via glob. Sanctum auth plus the same module.action permissions and
// policies as the web routes.

use App\Http\Controllers\Api\V1\PositionController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::get('positions', [PositionController::class, 'index'])->middleware('permission:core.view')->name('api.positions.index');
    Route::post('positions', [PositionController::class, 'store'])->middleware('permission:core.manage')->name('api.positions.store');
    Route::get('positions/{position}', [PositionController::class, 'show'])->middleware('permission:core.view')->name('api.positions.show');
    Route::put('positions/{position}', [PositionController::class, 'update'])->middleware('permission:core.manage')->name('api.positions.update');
    Route::patch('positions/{position}', [PositionController::class, 'update'])->middleware('permission:core.manage')->name('api.positions.patch');
    Route::delete('positions/{position}', [PositionController::class, 'destroy'])->middleware('permission:core.manage')->name('api.positions.destroy');
});
