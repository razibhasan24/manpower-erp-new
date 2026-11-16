<?php

use App\Http\Controllers\VisaController;
use App\Models\Visa;
use App\Services\VisaService;
use App\Http\Resources\VisaResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
 * API v1 for Visa & MOFA. Same services and policies as the web UI
 * (Phase 01 section 11). Sanctum auth; permission middleware matches web.
 */

Route::prefix('v1')->group(function () {
Route::middleware(['auth:sanctum', 'permission:visa.view'])->group(function () {
    // MERGE FIX 2026-11-13 (coordinator): the index/show closure routes
    // that were here are REMOVED. They returned records without a
    // per-record Policy check (show returned any visa by id).
    // Index/show are provided by routes/api-modules/pipeline.php via
    // the Api\V1 controllers, which authorise viewAny/view and scope
    // lists with visibleTo().
});
Route::middleware(['auth:sanctum', 'permission:visa.create'])->post('visas', [VisaController::class, 'store'])->name('api.visas.store');
Route::middleware(['auth:sanctum', 'permission:visa.edit'])->group(function () {
    Route::put('visas/{visa}', [VisaController::class, 'update'])->name('api.visas.update');
    Route::post('visas/{visa}/status', [VisaController::class, 'changeStatus'])->name('api.visas.status');
});
});
