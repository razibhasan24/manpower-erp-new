<?php

// Branch API routes (Phases 08-12, /api/v1). Loaded by routes/api.php
// via glob. Sanctum auth plus the same module.action permissions and
// policies as the web routes.

use App\Http\Controllers\Api\V1\BranchController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::get('branches', [BranchController::class, 'index'])->middleware('permission:branch.view')->name('api.branches.index');
    Route::post('branches', [BranchController::class, 'store'])->middleware('permission:branch.create')->name('api.branches.store');
    Route::get('branches/{branch}', [BranchController::class, 'show'])->middleware('permission:branch.view')->name('api.branches.show');
    Route::put('branches/{branch}', [BranchController::class, 'update'])->middleware('permission:branch.edit')->name('api.branches.update');
    Route::patch('branches/{branch}', [BranchController::class, 'update'])->middleware('permission:branch.edit')->name('api.branches.patch');
    Route::delete('branches/{branch}', [BranchController::class, 'destroy'])->middleware('permission:branch.delete')->name('api.branches.destroy');
});
