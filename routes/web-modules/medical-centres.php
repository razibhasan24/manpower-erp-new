<?php

use App\Http\Controllers\MedicalCentreController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'permission:medical-centre.view'])->group(function () {
    Route::get('medical-centres', [MedicalCentreController::class, 'index'])->name('medical-centres.index');
});

Route::middleware(['auth', 'permission:medical-centre.create'])->group(function () {
    Route::get('medical-centres/create', [MedicalCentreController::class, 'create'])->name('medical-centres.create');
    Route::post('medical-centres', [MedicalCentreController::class, 'store'])->name('medical-centres.store');
});

Route::middleware(['auth', 'permission:medical-centre.edit'])->group(function () {
    Route::get('medical-centres/{medicalCentre}/edit', [MedicalCentreController::class, 'edit'])->whereNumber('medicalCentre')->name('medical-centres.edit');
    Route::put('medical-centres/{medicalCentre}', [MedicalCentreController::class, 'update'])->whereNumber('medicalCentre')->name('medical-centres.update');
});

Route::middleware(['auth', 'permission:medical-centre.delete'])->group(function () {
    Route::delete('medical-centres/{medicalCentre}', [MedicalCentreController::class, 'destroy'])->whereNumber('medicalCentre')->name('medical-centres.destroy');
});
