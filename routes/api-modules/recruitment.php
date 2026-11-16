<?php

// Recruitment API routes (Phases 08-12, /api/v1). Loaded by routes/api.php
// via glob. Sanctum auth plus the same module.action permissions and
// policies as the web routes.

use App\Http\Controllers\Api\V1\RecruitmentController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::get('recruitments', [RecruitmentController::class, 'index'])->middleware('permission:recruitment.view')->name('api.recruitments.index');
    Route::post('recruitments', [RecruitmentController::class, 'store'])->middleware('permission:recruitment.create')->name('api.recruitments.store');
    Route::get('recruitments/{recruitment}', [RecruitmentController::class, 'show'])->middleware('permission:recruitment.view')->name('api.recruitments.show');
    Route::put('recruitments/{recruitment}', [RecruitmentController::class, 'update'])->middleware('permission:recruitment.edit')->name('api.recruitments.update');
    Route::patch('recruitments/{recruitment}', [RecruitmentController::class, 'update'])->middleware('permission:recruitment.edit')->name('api.recruitments.patch');
    Route::delete('recruitments/{recruitment}', [RecruitmentController::class, 'destroy'])->middleware('permission:recruitment.delete')->name('api.recruitments.destroy');
    Route::post('recruitments/{recruitment}/cancel', [RecruitmentController::class, 'cancel'])
        ->middleware('permission:recruitment.delete')->name('api.recruitments.cancel');
});
