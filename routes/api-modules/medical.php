<?php

use App\Http\Controllers\MedicalController;
use App\Models\Medical;
use App\Services\MedicalService;
use App\Http\Resources\MedicalResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
 * API v1 for Medical Assessment. Same services and policies as the web UI
 * (Phase 01 section 11). Sanctum auth; permission middleware matches web.
 */

Route::prefix('v1')->group(function () {
Route::middleware(['auth:sanctum', 'permission:medical.view'])->group(function () {
    Route::get('medicals', function (Request $request, MedicalService $service) {
        $records = $service->scopedQuery($request->user())->paginate(15);
        return MedicalResource::collection($records);
    })->name('api.medicals.index');
    Route::get('medicals/{medical}', function (Medical $medical) {
        return new MedicalResource($medical);
    })->name('api.medicals.show')->whereNumber('medical');
});
Route::middleware(['auth:sanctum', 'permission:medical.create'])->post('medicals', [MedicalController::class, 'store'])->name('api.medicals.store');
Route::middleware(['auth:sanctum', 'permission:medical.edit'])->group(function () {
    Route::put('medicals/{medical}', [MedicalController::class, 'update'])->name('api.medicals.update');
    Route::post('medicals/{medical}/status', [MedicalController::class, 'changeStatus'])->name('api.medicals.status');
});
});
