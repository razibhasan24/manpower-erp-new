<?php

use App\Http\Controllers\Api\V1\FlightController;
use App\Http\Controllers\Api\V1\RecruitmentController;
use App\Http\Controllers\Api\V1\VisaController;
use Illuminate\Support\Facades\Route;

/*
| API v1 — recruitment pipeline additions (Phase 30, Group E).
|
| The per-module route files (recruitment.php, visa.php, flight.php)
| already register the standard index/show/store/update routes against
| the owning groups' controllers. This file registers ONLY the
| endpoints no other group registered:
|
|  - POST /v1/recruitments/{recruitment}/transition — the workflow
|    transition endpoint. Group C's RecruitmentController::transition()
|    exists but has no route anywhere else.
|  - GET /v1/visas and /v1/flights — versioned read endpoints backed
|    by Group E's Api\V1 controllers with Policy checks.
|    (MERGE FIX 2026-11-13: this file was unversioned (/api/visas) until
|    wrapped in the v1 prefix here; the unguarded closure index/show
|    routes in visa.php / flight.php were removed at the same time.)
*/

Route::prefix('v1')->middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
    Route::post('/recruitments/{recruitment}/transition', [RecruitmentController::class, 'transition'])
        ->middleware('permission:recruitment.edit')
        ->name('api.v1.recruitments.transition');

    Route::get('/visas', [VisaController::class, 'index'])->name('api.v1.visas.index');
    Route::get('/visas/{visa}', [VisaController::class, 'show'])->name('api.v1.visas.show');

    Route::get('/flights', [FlightController::class, 'index'])->name('api.v1.flights.index');
    Route::get('/flights/{flight}', [FlightController::class, 'show'])->name('api.v1.flights.show');
});
