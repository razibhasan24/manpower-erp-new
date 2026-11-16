<?php

use App\Http\Controllers\PassportReturnController;
use App\Models\PassportReturn;
use App\Services\PassportReturnService;
use App\Http\Resources\PassportReturnResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
 * API v1 for Passport Return. Same services and policies as the web UI
 * (Phase 01 section 11). Sanctum auth; permission middleware matches web.
 */

Route::prefix('v1')->group(function () {
Route::middleware(['auth:sanctum', 'permission:passport-return.view'])->group(function () {
    Route::get('passport-returns', function (Request $request, PassportReturnService $service) {
        $records = $service->scopedQuery($request->user())->paginate(15);
        return PassportReturnResource::collection($records);
    })->name('api.passport-returns.index');
    Route::get('passport-returns/{passportReturn}', function (PassportReturn $passportReturn) {
        return new PassportReturnResource($passportReturn);
    })->name('api.passport-returns.show')->whereNumber('passportReturn');
});
Route::middleware(['auth:sanctum', 'permission:passport-return.create'])->post('passport-returns', [PassportReturnController::class, 'store'])->name('api.passport-returns.store');
Route::middleware(['auth:sanctum', 'permission:passport-return.edit'])->group(function () {
    Route::put('passport-returns/{passportReturn}', [PassportReturnController::class, 'update'])->name('api.passport-returns.update');
    Route::post('passport-returns/{passportReturn}/status', [PassportReturnController::class, 'changeStatus'])->name('api.passport-returns.status');
});
});
