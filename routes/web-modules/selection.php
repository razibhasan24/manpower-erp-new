<?php

use App\Http\Controllers\SelectionController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'permission:selection.view'])->get('selections/criteria', [\App\Http\Controllers\SelectionController::class, 'criteria'])->name('selections.criteria');
Route::middleware(['auth', 'permission:selection.create'])->post('selections/criteria', [\App\Http\Controllers\SelectionController::class, 'storeCriterion'])->name('selections.criteria.store');
Route::middleware(['auth', 'permission:selection.create'])->group(function () {
    Route::get('selections/create', [SelectionController::class, 'create'])->name('selections.create');
    Route::post('selections', [SelectionController::class, 'store'])->name('selections.store');
});
Route::middleware(['auth', 'permission:selection.view'])->group(function () {
    Route::get('selections', [SelectionController::class, 'index'])->name('selections.index');
    Route::get('selections/{selection}', [SelectionController::class, 'show'])->name('selections.show')->whereNumber('selection');
});
Route::middleware(['auth', 'permission:selection.edit'])->group(function () {
    Route::get('selections/{selection}/edit', [SelectionController::class, 'edit'])->name('selections.edit');
    Route::put('selections/{selection}', [SelectionController::class, 'update'])->name('selections.update');
    Route::post('selections/{selection}/status', [SelectionController::class, 'changeStatus'])->name('selections.status');
    Route::delete('selections/{selection}', [SelectionController::class, 'destroy'])->name('selections.destroy');
});
Route::middleware(['auth', 'permission:selection.download'])->group(function () {
    Route::get('selections/{selection}/download/{field}', [SelectionController::class, 'download'])->name('selections.download');
});

Route::middleware(['auth', 'permission:selection.approve'])->post('selections/{selection}/decide', [\App\Http\Controllers\SelectionController::class, 'decide'])->name('selections.decide');
