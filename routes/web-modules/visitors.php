<?php

use App\Http\Controllers\Visitor\VisitorController;
use Illuminate\Support\Facades\Route;

Route::get('visitor-passes', [VisitorController::class, 'passRegister'])
    ->middleware(['auth', 'verified', 'permission:visitor.view'])
    ->name('visitor-passes.index');

/**
 * Visitor web routes (Phase 27). Glob-loaded by routes/web.php (Group A).
 */
Route::middleware(['auth', 'verified'])->prefix('visitors')->name('visitors.')->group(function () {
    Route::get('daily-report', [VisitorController::class, 'dailyReport'])->name('daily-report')->middleware('permission:visitor.view');
    Route::get('monthly-report', [VisitorController::class, 'monthlyReport'])->name('monthly-report')->middleware('permission:visitor.view');

    // Static routes MUST come before the {visitor} placeholder, otherwise
    // /visitors/create is captured by the show route and 404s.
    Route::get('/', [VisitorController::class, 'index'])->name('index')->middleware('permission:visitor.view');
    Route::get('/create', [VisitorController::class, 'create'])->name('create')->middleware('permission:visitor.create');
    Route::post('/', [VisitorController::class, 'store'])->name('store')->middleware('permission:visitor.create');

    Route::get('{visitor}/pass', [VisitorController::class, 'pass'])->name('pass')->middleware('permission:visitor.view');
    Route::post('{visitor}/check-out', [VisitorController::class, 'checkOut'])->name('check-out')->middleware('permission:visitor.update');
    Route::get('/{visitor}', [VisitorController::class, 'show'])->name('show')->middleware('permission:visitor.view');
});
