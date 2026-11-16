<?php

use App\Http\Controllers\TrainingController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'permission:training.create'])->group(function () {
    Route::get('trainings/create', [TrainingController::class, 'create'])->name('trainings.create');
    Route::post('trainings', [TrainingController::class, 'store'])->name('trainings.store');
});
Route::middleware(['auth', 'permission:training.view'])->group(function () {
    Route::get('trainings', [TrainingController::class, 'index'])->name('trainings.index');
    Route::get('trainings/{training}', [TrainingController::class, 'show'])->name('trainings.show')->whereNumber('training');
});
Route::middleware(['auth', 'permission:training.edit'])->group(function () {
    Route::get('trainings/{training}/edit', [TrainingController::class, 'edit'])->name('trainings.edit');
    Route::put('trainings/{training}', [TrainingController::class, 'update'])->name('trainings.update');
    Route::post('trainings/{training}/status', [TrainingController::class, 'changeStatus'])->name('trainings.status');
    Route::delete('trainings/{training}', [TrainingController::class, 'destroy'])->name('trainings.destroy');
});
Route::middleware(['auth', 'permission:training.download'])->group(function () {
    Route::get('trainings/{training}/download/{field}', [TrainingController::class, 'download'])->name('trainings.download');
});
