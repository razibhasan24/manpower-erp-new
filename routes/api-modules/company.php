<?php

// Company API routes (Phases 08-12, /api/v1). Loaded by routes/api.php
// via glob. Sanctum auth plus the same module.action permissions and
// policies as the web routes.

use App\Http\Controllers\Api\V1\CompanyController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::get('companies', [CompanyController::class, 'index'])->middleware('permission:core.view')->name('api.companies.index');
    Route::post('companies', [CompanyController::class, 'store'])->middleware('permission:core.manage')->name('api.companies.store');
    Route::get('companies/{company}', [CompanyController::class, 'show'])->middleware('permission:core.view')->name('api.companies.show');
    Route::put('companies/{company}', [CompanyController::class, 'update'])->middleware('permission:core.manage')->name('api.companies.update');
    Route::patch('companies/{company}', [CompanyController::class, 'update'])->middleware('permission:core.manage')->name('api.companies.patch');
    Route::delete('companies/{company}', [CompanyController::class, 'destroy'])->middleware('permission:core.manage')->name('api.companies.destroy');
});
