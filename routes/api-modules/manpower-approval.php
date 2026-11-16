<?php

use App\Http\Controllers\ManpowerApprovalController;
use App\Models\ManpowerApproval;
use App\Services\ManpowerApprovalService;
use App\Http\Resources\ManpowerApprovalResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
 * API v1 for Manpower Approval. Same services and policies as the web UI
 * (Phase 01 section 11). Sanctum auth; permission middleware matches web.
 */

Route::prefix('v1')->group(function () {
Route::middleware(['auth:sanctum', 'permission:manpower-approval.view'])->group(function () {
    Route::get('manpower-approvals', function (Request $request, ManpowerApprovalService $service) {
        $records = $service->scopedQuery($request->user())->paginate(15);
        return ManpowerApprovalResource::collection($records);
    })->name('api.manpower-approvals.index');
    Route::get('manpower-approvals/{manpowerApproval}', function (ManpowerApproval $manpowerApproval) {
        return new ManpowerApprovalResource($manpowerApproval);
    })->name('api.manpower-approvals.show')->whereNumber('manpowerApproval');
});
Route::middleware(['auth:sanctum', 'permission:manpower-approval.create'])->post('manpower-approvals', [ManpowerApprovalController::class, 'store'])->name('api.manpower-approvals.store');
Route::middleware(['auth:sanctum', 'permission:manpower-approval.edit'])->group(function () {
    Route::put('manpower-approvals/{manpowerApproval}', [ManpowerApprovalController::class, 'update'])->name('api.manpower-approvals.update');
    Route::post('manpower-approvals/{manpowerApproval}/status', [ManpowerApprovalController::class, 'changeStatus'])->name('api.manpower-approvals.status');
});
});
