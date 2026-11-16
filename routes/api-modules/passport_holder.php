<?php

// Passport Holder API routes (Phases 08-12, /api/v1). Loaded by routes/api.php
// via glob. Sanctum auth plus the same module.action permissions and
// policies as the web routes.

use App\Http\Controllers\Api\V1\PassportHolderController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::get('passport-holders', [PassportHolderController::class, 'index'])->middleware('permission:passport-holder.view')->name('api.passport-holders.index');
    Route::post('passport-holders', [PassportHolderController::class, 'store'])->middleware('permission:passport-holder.create')->name('api.passport-holders.store');
    Route::get('passport-holders/{passport_holder}', [PassportHolderController::class, 'show'])->middleware('permission:passport-holder.view')->name('api.passport-holders.show');
    Route::put('passport-holders/{passport_holder}', [PassportHolderController::class, 'update'])->middleware('permission:passport-holder.edit')->name('api.passport-holders.update');
    Route::patch('passport-holders/{passport_holder}', [PassportHolderController::class, 'update'])->middleware('permission:passport-holder.edit')->name('api.passport-holders.patch');
    Route::delete('passport-holders/{passport_holder}', [PassportHolderController::class, 'destroy'])->middleware('permission:passport-holder.delete')->name('api.passport-holders.destroy');
});
