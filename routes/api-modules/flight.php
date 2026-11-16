<?php

use App\Http\Controllers\FlightController;
use App\Models\Flight;
use App\Services\FlightService;
use App\Http\Resources\FlightResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
 * API v1 for Flight Booking. Same services and policies as the web UI
 * (Phase 01 section 11). Sanctum auth; permission middleware matches web.
 */

Route::prefix('v1')->group(function () {
Route::middleware(['auth:sanctum', 'permission:flight.view'])->group(function () {
    // MERGE FIX 2026-11-13 (coordinator): the index/show closure routes
    // that were here are REMOVED. They returned records without a
    // per-record Policy check (show returned any flight by id).
    // Index/show are provided by routes/api-modules/pipeline.php via
    // the Api\V1 controllers, which authorise viewAny/view and scope
    // lists with visibleTo().
});
Route::middleware(['auth:sanctum', 'permission:flight.create'])->post('flights', [FlightController::class, 'store'])->name('api.flights.store');
Route::middleware(['auth:sanctum', 'permission:flight.edit'])->group(function () {
    Route::put('flights/{flight}', [FlightController::class, 'update'])->name('api.flights.update');
    Route::post('flights/{flight}/status', [FlightController::class, 'changeStatus'])->name('api.flights.status');
});
});
