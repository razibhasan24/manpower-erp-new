<?php

// Country API routes (Phases 08-12, /api/v1). Loaded by routes/api.php
// via glob. Sanctum auth plus the same module.action permissions and
// policies as the web routes.

use App\Http\Controllers\Api\V1\CountryController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::get('countries', [CountryController::class, 'index'])->middleware('permission:core.view')->name('api.countries.index');
    Route::post('countries', [CountryController::class, 'store'])->middleware('permission:core.manage')->name('api.countries.store');
    Route::get('countries/{country}', [CountryController::class, 'show'])->middleware('permission:core.view')->name('api.countries.show');
    Route::put('countries/{country}', [CountryController::class, 'update'])->middleware('permission:core.manage')->name('api.countries.update');
    Route::patch('countries/{country}', [CountryController::class, 'update'])->middleware('permission:core.manage')->name('api.countries.patch');
    Route::delete('countries/{country}', [CountryController::class, 'destroy'])->middleware('permission:core.manage')->name('api.countries.destroy');
});
