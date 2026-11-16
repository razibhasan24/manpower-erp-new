<?php

// Country web routes (Phases 08-12). Loaded by routes/web.php via glob.
// Every route is behind auth plus the module.action permission; the
// controller and policy then enforce branch/country/ownership scope.

use App\Http\Controllers\CountryController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('countries', [CountryController::class, 'index'])->middleware('permission:core.view')->name('countries.index');
    Route::get('countries/create', [CountryController::class, 'create'])->middleware('permission:core.manage')->name('countries.create');
    Route::post('countries', [CountryController::class, 'store'])->middleware('permission:core.manage')->name('countries.store');
    Route::get('countries/{country}', [CountryController::class, 'show'])->middleware('permission:core.view')->name('countries.show');
    Route::get('countries/{country}/edit', [CountryController::class, 'edit'])->middleware('permission:core.manage')->name('countries.edit');
    Route::put('countries/{country}', [CountryController::class, 'update'])->middleware('permission:core.manage')->name('countries.update');
    Route::delete('countries/{country}', [CountryController::class, 'destroy'])->middleware('permission:core.manage')->name('countries.destroy');
});
