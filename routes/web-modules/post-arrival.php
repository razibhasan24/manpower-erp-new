<?php

use App\Http\Controllers\PostArrivalController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'permission:post-arrival.create'])->group(function () {
    Route::get('post-arrivals/create', [PostArrivalController::class, 'create'])->name('post-arrivals.create');
    Route::post('post-arrivals', [PostArrivalController::class, 'store'])->name('post-arrivals.store');
});
Route::middleware(['auth', 'permission:post-arrival.view'])->group(function () {
    Route::get('post-arrivals', [PostArrivalController::class, 'index'])->name('post-arrivals.index');
    Route::get('post-arrivals/{postArrival}', [PostArrivalController::class, 'show'])->name('post-arrivals.show')->whereNumber('postArrival');
});
Route::middleware(['auth', 'permission:post-arrival.edit'])->group(function () {
    Route::get('post-arrivals/{postArrival}/edit', [PostArrivalController::class, 'edit'])->name('post-arrivals.edit');
    Route::put('post-arrivals/{postArrival}', [PostArrivalController::class, 'update'])->name('post-arrivals.update');
    Route::post('post-arrivals/{postArrival}/status', [PostArrivalController::class, 'changeStatus'])->name('post-arrivals.status');
    Route::delete('post-arrivals/{postArrival}', [PostArrivalController::class, 'destroy'])->name('post-arrivals.destroy');
});
Route::middleware(['auth', 'permission:post-arrival.download'])->group(function () {
    Route::get('post-arrivals/{postArrival}/download/{field}', [PostArrivalController::class, 'download'])->name('post-arrivals.download');
});

Route::middleware(['auth', 'permission:post-arrival.edit'])->group(function () {
    Route::post('post-arrivals/{postArrival}/complaint', [\App\Http\Controllers\PostArrivalController::class, 'storeComplaint'])->name('post-arrivals.complaint');
    Route::post('post-arrivals/{postArrival}/support', [\App\Http\Controllers\PostArrivalController::class, 'storeSupport'])->name('post-arrivals.support');
});
