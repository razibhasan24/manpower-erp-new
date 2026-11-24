<?php

use App\Http\Controllers\FlightController;
use App\Http\Controllers\FlightSupportController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'permission:flight.view'])->group(function () {
    Route::get('airlines', [FlightSupportController::class, 'airlines'])->name('airlines.index');
    Route::get('travel-agencies', [FlightSupportController::class, 'travelAgencies'])->name('travel-agencies.index');
});

Route::middleware(['auth', 'permission:flight.create'])->group(function () {
    Route::get('airlines/create', [FlightSupportController::class, 'createAirline'])->name('airlines.create');
    Route::post('airlines', [FlightSupportController::class, 'storeAirline'])->name('airlines.store');
    Route::get('travel-agencies/create', [FlightSupportController::class, 'createTravelAgency'])->name('travel-agencies.create');
    Route::post('travel-agencies', [FlightSupportController::class, 'storeTravelAgency'])->name('travel-agencies.store');
});

Route::middleware(['auth', 'permission:flight.edit'])->group(function () {
    Route::get('airlines/{airline}/edit', [FlightSupportController::class, 'editAirline'])->whereNumber('airline')->name('airlines.edit');
    Route::put('airlines/{airline}', [FlightSupportController::class, 'updateAirline'])->whereNumber('airline')->name('airlines.update');
    Route::get('travel-agencies/{travelAgency}/edit', [FlightSupportController::class, 'editTravelAgency'])->whereNumber('travelAgency')->name('travel-agencies.edit');
    Route::put('travel-agencies/{travelAgency}', [FlightSupportController::class, 'updateTravelAgency'])->whereNumber('travelAgency')->name('travel-agencies.update');
});
Route::middleware(['auth', 'permission:flight.edit'])->group(function () {
    Route::delete('airlines/{airline}', [FlightSupportController::class, 'destroyAirline'])->whereNumber('airline')->name('airlines.destroy');
    Route::delete('travel-agencies/{travelAgency}', [FlightSupportController::class, 'destroyTravelAgency'])->whereNumber('travelAgency')->name('travel-agencies.destroy');
});

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
