<?php

use App\Http\Controllers\FlightController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'permission:flight.create'])->group(function () {
    Route::get('flights/create', [FlightController::class, 'create'])->name('flights.create');
    Route::post('flights', [FlightController::class, 'store'])->name('flights.store');
});
Route::middleware(['auth', 'permission:flight.view'])->group(function () {
    Route::get('flights', [FlightController::class, 'index'])->name('flights.index');
    Route::get('flights/{flight}', [FlightController::class, 'show'])->name('flights.show')->whereNumber('flight');
});
Route::middleware(['auth', 'permission:flight.edit'])->group(function () {
    Route::get('flights/{flight}/edit', [FlightController::class, 'edit'])->name('flights.edit');
    Route::put('flights/{flight}', [FlightController::class, 'update'])->name('flights.update');
    Route::post('flights/{flight}/status', [FlightController::class, 'changeStatus'])->name('flights.status');
    Route::delete('flights/{flight}', [FlightController::class, 'destroy'])->name('flights.destroy');
});
Route::middleware(['auth', 'permission:flight.download'])->group(function () {
    Route::get('flights/{flight}/download/{field}', [FlightController::class, 'download'])->name('flights.download');
});
