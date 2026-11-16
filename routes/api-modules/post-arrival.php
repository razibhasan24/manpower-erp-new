<?php

use App\Http\Controllers\PostArrivalController;
use App\Models\PostArrival;
use App\Services\PostArrivalService;
use App\Http\Resources\PostArrivalResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
 * API v1 for Post Arrival Support. Same services and policies as the web UI
 * (Phase 01 section 11). Sanctum auth; permission middleware matches web.
 */

Route::prefix('v1')->group(function () {
Route::middleware(['auth:sanctum', 'permission:post-arrival.view'])->group(function () {
    Route::get('post-arrivals', function (Request $request, PostArrivalService $service) {
        $records = $service->scopedQuery($request->user())->paginate(15);
        return PostArrivalResource::collection($records);
    })->name('api.post-arrivals.index');
    Route::get('post-arrivals/{postArrival}', function (PostArrival $postArrival) {
        return new PostArrivalResource($postArrival);
    })->name('api.post-arrivals.show')->whereNumber('postArrival');
});
Route::middleware(['auth:sanctum', 'permission:post-arrival.create'])->post('post-arrivals', [PostArrivalController::class, 'store'])->name('api.post-arrivals.store');
Route::middleware(['auth:sanctum', 'permission:post-arrival.edit'])->group(function () {
    Route::put('post-arrivals/{postArrival}', [PostArrivalController::class, 'update'])->name('api.post-arrivals.update');
    Route::post('post-arrivals/{postArrival}/status', [PostArrivalController::class, 'changeStatus'])->name('api.post-arrivals.status');
});
});
