<?php

use App\Http\Controllers\FingerprintController;
use App\Models\Fingerprint;
use App\Services\FingerprintService;
use App\Http\Resources\FingerprintResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
 * API v1 for Fingerprint. Same services and policies as the web UI
 * (Phase 01 section 11). Sanctum auth; permission middleware matches web.
 */

Route::prefix('v1')->group(function () {
Route::middleware(['auth:sanctum', 'permission:fingerprint.view'])->group(function () {
    Route::get('fingerprints', function (Request $request, FingerprintService $service) {
        $records = $service->scopedQuery($request->user())->paginate(15);
        return FingerprintResource::collection($records);
    })->name('api.fingerprints.index');
    Route::get('fingerprints/{fingerprint}', function (Fingerprint $fingerprint) {
        return new FingerprintResource($fingerprint);
    })->name('api.fingerprints.show')->whereNumber('fingerprint');
});
Route::middleware(['auth:sanctum', 'permission:fingerprint.create'])->post('fingerprints', [FingerprintController::class, 'store'])->name('api.fingerprints.store');
Route::middleware(['auth:sanctum', 'permission:fingerprint.edit'])->group(function () {
    Route::put('fingerprints/{fingerprint}', [FingerprintController::class, 'update'])->name('api.fingerprints.update');
    Route::post('fingerprints/{fingerprint}/status', [FingerprintController::class, 'changeStatus'])->name('api.fingerprints.status');
});
});
