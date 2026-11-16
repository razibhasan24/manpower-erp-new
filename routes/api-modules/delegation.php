<?php

use App\Http\Controllers\DelegationController;
use App\Models\Delegation;
use App\Services\DelegationService;
use App\Http\Resources\DelegationResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
 * API v1 for Delegation Approval. Same services and policies as the web UI
 * (Phase 01 section 11). Sanctum auth; permission middleware matches web.
 */

Route::prefix('v1')->group(function () {
Route::middleware(['auth:sanctum', 'permission:delegation.view'])->group(function () {
    Route::get('delegations', function (Request $request, DelegationService $service) {
        $records = $service->scopedQuery($request->user())->paginate(15);
        return DelegationResource::collection($records);
    })->name('api.delegations.index');
    Route::get('delegations/{delegation}', function (Delegation $delegation) {
        return new DelegationResource($delegation);
    })->name('api.delegations.show')->whereNumber('delegation');
});
Route::middleware(['auth:sanctum', 'permission:delegation.create'])->post('delegations', [DelegationController::class, 'store'])->name('api.delegations.store');
Route::middleware(['auth:sanctum', 'permission:delegation.edit'])->group(function () {
    Route::put('delegations/{delegation}', [DelegationController::class, 'update'])->name('api.delegations.update');
    Route::post('delegations/{delegation}/status', [DelegationController::class, 'changeStatus'])->name('api.delegations.status');
});
});
