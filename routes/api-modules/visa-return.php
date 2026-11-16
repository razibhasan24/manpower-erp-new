<?php

use App\Http\Controllers\VisaReturnController;
use App\Models\VisaReturn;
use App\Services\VisaReturnService;
use App\Http\Resources\VisaReturnResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
 * API v1 for Visa Return. Same services and policies as the web UI
 * (Phase 01 section 11). Sanctum auth; permission middleware matches web.
 */

Route::prefix('v1')->group(function () {
Route::middleware(['auth:sanctum', 'permission:visa-return.view'])->group(function () {
    Route::get('visa-returns', function (Request $request, VisaReturnService $service) {
        $records = $service->scopedQuery($request->user())->paginate(15);
        return VisaReturnResource::collection($records);
    })->name('api.visa-returns.index');
    Route::get('visa-returns/{visaReturn}', function (VisaReturn $visaReturn) {
        return new VisaReturnResource($visaReturn);
    })->name('api.visa-returns.show')->whereNumber('visaReturn');
});
Route::middleware(['auth:sanctum', 'permission:visa-return.create'])->post('visa-returns', [VisaReturnController::class, 'store'])->name('api.visa-returns.store');
Route::middleware(['auth:sanctum', 'permission:visa-return.edit'])->group(function () {
    Route::put('visa-returns/{visaReturn}', [VisaReturnController::class, 'update'])->name('api.visa-returns.update');
    Route::post('visa-returns/{visaReturn}/status', [VisaReturnController::class, 'changeStatus'])->name('api.visa-returns.status');
});
});
