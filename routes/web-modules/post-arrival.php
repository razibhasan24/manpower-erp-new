<?php

use App\Http\Controllers\PostArrivalController;
use App\Http\Controllers\FlightSupportController;
use App\Http\Controllers\PostArrivalRegistersController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'permission:post-arrival.view'])->group(function () {
    Route::get('airport-pickup', [FlightSupportController::class, 'airportPickup'])->name('airport-pickup.index');
    Route::get('accommodation', [FlightSupportController::class, 'accommodation'])->name('accommodation.index');
    Route::get('employer-information', [FlightSupportController::class, 'employerInformation'])->name('employer-information.index');
    Route::get('follow-ups', [PostArrivalRegistersController::class, 'followUps'])->name('follow-ups.index');
    Route::get('candidate-feedback', [PostArrivalRegistersController::class, 'feedback'])->name('candidate-feedback.index');
    Route::get('post-arrival-complaints', [PostArrivalRegistersController::class, 'complaints'])->name('post-arrival-complaints.index');
});

Route::middleware(['auth', 'permission:post-arrival.create'])->group(function () {
    Route::get('post-arrivals/create', [PostArrivalController::class, 'create'])->name('post-arrivals.create');
    Route::post('post-arrivals', [PostArrivalController::class, 'store'])->name('post-arrivals.store');
    Route::get('airport-pickup/create', [PostArrivalController::class, 'createAirportPickup'])->name('airport-pickup.create');
    Route::post('airport-pickup', [PostArrivalController::class, 'storeAirportPickup'])->name('airport-pickup.store');
    Route::get('employer-information/create', [PostArrivalController::class, 'createEmployerInformation'])->name('employer-information.create');
    Route::post('employer-information', [PostArrivalController::class, 'storeEmployerInformation'])->name('employer-information.store');
    Route::get('accommodation/create', [PostArrivalController::class, 'createAccommodation'])->name('accommodation.create');
    Route::post('accommodation', [PostArrivalController::class, 'storeAccommodation'])->name('accommodation.store');
    Route::get('candidate-feedback/create', [PostArrivalController::class, 'createCandidateFeedback'])->name('candidate-feedback.create');
    Route::post('candidate-feedback', [PostArrivalController::class, 'storeCandidateFeedback'])->name('candidate-feedback.store');
    Route::get('post-arrival-complaints/create', [PostArrivalRegistersController::class, 'createComplaint'])->name('post-arrival-complaints.create');
    Route::post('post-arrival-complaints', [PostArrivalRegistersController::class, 'storeComplaint'])->name('post-arrival-complaints.store');
    Route::get('follow-ups/create', [PostArrivalRegistersController::class, 'createFollowUp'])->name('follow-ups.create');
    Route::post('follow-ups', [PostArrivalRegistersController::class, 'storeFollowUp'])->name('follow-ups.store');
});
Route::middleware(['auth', 'permission:post-arrival.edit'])->group(function () {
    Route::delete('airport-pickup/{postArrival}', [PostArrivalController::class, 'removeAirportPickup'])->whereNumber('postArrival')->name('airport-pickup.destroy');
    Route::delete('employer-information/{postArrival}', [PostArrivalController::class, 'removeRegisterField'])->defaults('field', 'employer-information')->whereNumber('postArrival')->name('employer-information.destroy');
    Route::delete('accommodation/{postArrival}', [PostArrivalController::class, 'removeRegisterField'])->defaults('field', 'accommodation')->whereNumber('postArrival')->name('accommodation.destroy');
    Route::delete('candidate-feedback/{postArrival}', [PostArrivalController::class, 'removeRegisterField'])->defaults('field', 'candidate-feedback')->whereNumber('postArrival')->name('candidate-feedback.destroy');
    Route::get('post-arrival-complaints/{complaint}/edit', [PostArrivalRegistersController::class, 'editComplaint'])->whereNumber('complaint')->name('post-arrival-complaints.edit');
    Route::put('post-arrival-complaints/{complaint}', [PostArrivalRegistersController::class, 'updateComplaint'])->whereNumber('complaint')->name('post-arrival-complaints.update');
    Route::delete('post-arrival-complaints/{complaint}', [PostArrivalRegistersController::class, 'destroyComplaint'])->whereNumber('complaint')->name('post-arrival-complaints.destroy');
    Route::get('follow-ups/{support}/edit', [PostArrivalRegistersController::class, 'editFollowUp'])->whereNumber('support')->name('follow-ups.edit');
    Route::put('follow-ups/{support}', [PostArrivalRegistersController::class, 'updateFollowUp'])->whereNumber('support')->name('follow-ups.update');
    Route::delete('follow-ups/{support}', [PostArrivalRegistersController::class, 'destroyFollowUp'])->whereNumber('support')->name('follow-ups.destroy');
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
