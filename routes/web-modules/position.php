<?php

// Position web routes (Phases 08-12). Loaded by routes/web.php via glob.
// Every route is behind auth plus the module.action permission; the
// controller and policy then enforce branch/country/ownership scope.

use App\Http\Controllers\PositionController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('positions', [PositionController::class, 'index'])->middleware('permission:core.view')->name('positions.index');
    Route::get('positions/create', [PositionController::class, 'create'])->middleware('permission:core.manage')->name('positions.create');
    Route::post('positions', [PositionController::class, 'store'])->middleware('permission:core.manage')->name('positions.store');
    Route::get('positions/{position}', [PositionController::class, 'show'])->middleware('permission:core.view')->name('positions.show');
    Route::get('positions/{position}/edit', [PositionController::class, 'edit'])->middleware('permission:core.manage')->name('positions.edit');
    Route::put('positions/{position}', [PositionController::class, 'update'])->middleware('permission:core.manage')->name('positions.update');
    Route::delete('positions/{position}', [PositionController::class, 'destroy'])->middleware('permission:core.manage')->name('positions.destroy');
});
