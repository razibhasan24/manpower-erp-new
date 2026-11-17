<?php

use App\Http\Controllers\TrainingCentreController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'permission:training-centre.view'])->group(function () {
    Route::get('training-centres', [TrainingCentreController::class, 'index'])->name('training-centres.index');
});
Route::middleware(['auth', 'permission:training-centre.create'])->group(function () {
    Route::get('training-centres/create', [TrainingCentreController::class, 'create'])->name('training-centres.create');
    Route::post('training-centres', [TrainingCentreController::class, 'store'])->name('training-centres.store');
});
Route::middleware(['auth', 'permission:training-centre.edit'])->group(function () {
    Route::get('training-centres/{trainingCentre}/edit', [TrainingCentreController::class, 'edit'])->whereNumber('trainingCentre')->name('training-centres.edit');
    Route::put('training-centres/{trainingCentre}', [TrainingCentreController::class, 'update'])->whereNumber('trainingCentre')->name('training-centres.update');
});
Route::middleware(['auth', 'permission:training-centre.delete'])->group(function () {
    Route::delete('training-centres/{trainingCentre}', [TrainingCentreController::class, 'destroy'])->whereNumber('trainingCentre')->name('training-centres.destroy');
});
