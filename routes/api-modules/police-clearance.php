<?php

use App\Http\Controllers\PoliceClearanceController;
use App\Models\PoliceClearance;
use App\Services\PoliceClearanceService;
use App\Http\Resources\PoliceClearanceResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
 * API v1 for Police Clearance. Same services and policies as the web UI
 * (Phase 01 section 11). Sanctum auth; permission middleware matches web.
 */

Route::prefix('v1')->group(function () {
Route::middleware(['auth:sanctum', 'permission:police-clearance.view'])->group(function () {
    Route::get('police-clearances', function (Request $request, PoliceClearanceService $service) {
        $records = $service->scopedQuery($request->user())->paginate(15);
        return PoliceClearanceResource::collection($records);
    })->name('api.police-clearances.index');
    Route::get('police-clearances/{policeClearance}', function (PoliceClearance $policeClearance) {
        return new PoliceClearanceResource($policeClearance);
    })->name('api.police-clearances.show')->whereNumber('policeClearance');
});
Route::middleware(['auth:sanctum', 'permission:police-clearance.create'])->post('police-clearances', [PoliceClearanceController::class, 'store'])->name('api.police-clearances.store');
Route::middleware(['auth:sanctum', 'permission:police-clearance.edit'])->group(function () {
    Route::put('police-clearances/{policeClearance}', [PoliceClearanceController::class, 'update'])->name('api.police-clearances.update');
    Route::post('police-clearances/{policeClearance}/status', [PoliceClearanceController::class, 'changeStatus'])->name('api.police-clearances.status');
});
});
