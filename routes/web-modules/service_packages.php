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
