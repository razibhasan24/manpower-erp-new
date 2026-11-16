<?php

// Company web routes (Phases 08-12). Loaded by routes/web.php via glob.
// Every route is behind auth plus the module.action permission; the
// controller and policy then enforce branch/country/ownership scope.

use App\Http\Controllers\CompanyController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('companies', [CompanyController::class, 'index'])->middleware('permission:core.view')->name('companies.index');
    Route::get('companies/create', [CompanyController::class, 'create'])->middleware('permission:core.manage')->name('companies.create');
    Route::post('companies', [CompanyController::class, 'store'])->middleware('permission:core.manage')->name('companies.store');
    Route::get('companies/{company}', [CompanyController::class, 'show'])->middleware('permission:core.view')->name('companies.show');
    Route::get('companies/{company}/edit', [CompanyController::class, 'edit'])->middleware('permission:core.manage')->name('companies.edit');
    Route::put('companies/{company}', [CompanyController::class, 'update'])->middleware('permission:core.manage')->name('companies.update');
    Route::delete('companies/{company}', [CompanyController::class, 'destroy'])->middleware('permission:core.manage')->name('companies.destroy');
});
