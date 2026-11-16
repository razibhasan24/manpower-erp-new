<?php

use App\Http\Controllers\TrainingController;
use App\Models\Training;
use App\Services\TrainingService;
use App\Http\Resources\TrainingResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
 * API v1 for Training. Same services and policies as the web UI
 * (Phase 01 section 11). Sanctum auth; permission middleware matches web.
 */

Route::prefix('v1')->group(function () {
Route::middleware(['auth:sanctum', 'permission:training.view'])->group(function () {
    Route::get('trainings', function (Request $request, TrainingService $service) {
        $records = $service->scopedQuery($request->user())->paginate(15);
        return TrainingResource::collection($records);
    })->name('api.trainings.index');
    Route::get('trainings/{training}', function (Training $training) {
        return new TrainingResource($training);
    })->name('api.trainings.show')->whereNumber('training');
});
Route::middleware(['auth:sanctum', 'permission:training.create'])->post('trainings', [TrainingController::class, 'store'])->name('api.trainings.store');
Route::middleware(['auth:sanctum', 'permission:training.edit'])->group(function () {
    Route::put('trainings/{training}', [TrainingController::class, 'update'])->name('api.trainings.update');
    Route::post('trainings/{training}/status', [TrainingController::class, 'changeStatus'])->name('api.trainings.status');
});
});
