<?php

use App\Http\Controllers\ServicePackageController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix('packages/{type}')->where(['type' => 'student|hajj_umrah'])->name('service-packages.')->group(function () {
    Route::get('/', [ServicePackageController::class, 'index'])->name('index');
    Route::get('/create', [ServicePackageController::class, 'create'])->name('create');
    Route::post('/', [ServicePackageController::class, 'store'])->name('store');
    Route::get('/{package}/edit', [ServicePackageController::class, 'edit'])->whereNumber('package')->name('edit');
    Route::put('/{package}', [ServicePackageController::class, 'update'])->whereNumber('package')->name('update');
    Route::delete('/{package}', [ServicePackageController::class, 'destroy'])->whereNumber('package')->name('destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('universities', [\App\Http\Controllers\UniversityController::class, 'index'])->name('universities.index');
    Route::get('universities/create', [\App\Http\Controllers\UniversityController::class, 'create'])->name('universities.create');
    Route::post('universities', [\App\Http\Controllers\UniversityController::class, 'store'])->name('universities.store');
    Route::get('universities/{university}/edit', [\App\Http\Controllers\UniversityController::class, 'edit'])->name('universities.edit');
    Route::put('universities/{university}', [\App\Http\Controllers\UniversityController::class, 'update'])->name('universities.update');
    Route::delete('universities/{university}', [\App\Http\Controllers\UniversityController::class, 'destroy'])->name('universities.destroy');
});
