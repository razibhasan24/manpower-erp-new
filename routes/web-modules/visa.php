<?php

use App\Http\Controllers\VisaController;
use App\Http\Controllers\VisaRegisterController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'permission:visa.view'])->group(function () {
    Route::get('visa-lots', [VisaRegisterController::class, 'lots'])->name('visa-register.lots');
    Route::get('work-permits', [VisaRegisterController::class, 'workPermits'])->name('visa-register.work-permits');
    Route::get('appointments', [VisaRegisterController::class, 'appointments'])->name('visa-register.appointments');
    Route::get('visa-documents', [VisaRegisterController::class, 'documents'])->name('visa-register.documents');
});

Route::middleware(['auth', 'permission:visa.edit'])->group(function () {
    Route::get('work-permits/create', [VisaRegisterController::class, 'createWorkPermit'])->name('work-permits.create');
    Route::post('work-permits', [VisaRegisterController::class, 'storeWorkPermit'])->name('work-permits.store');
    Route::get('work-permits/{workPermit}/edit', [VisaRegisterController::class, 'editWorkPermit'])->whereNumber('workPermit')->name('work-permits.edit');
    Route::put('work-permits/{workPermit}', [VisaRegisterController::class, 'updateWorkPermit'])->whereNumber('workPermit')->name('work-permits.update');
    Route::delete('work-permits/{workPermit}', [VisaRegisterController::class, 'destroyWorkPermit'])->whereNumber('workPermit')->name('work-permits.destroy');

    Route::get('appointments/create', [VisaRegisterController::class, 'createAppointment'])->name('appointments.create');
    Route::post('appointments', [VisaRegisterController::class, 'storeAppointment'])->name('appointments.store');
    Route::get('appointments/{appointment}/edit', [VisaRegisterController::class, 'editAppointment'])->whereNumber('appointment')->name('appointments.edit');
    Route::put('appointments/{appointment}', [VisaRegisterController::class, 'updateAppointment'])->whereNumber('appointment')->name('appointments.update');
    Route::delete('appointments/{appointment}', [VisaRegisterController::class, 'destroyAppointment'])->whereNumber('appointment')->name('appointments.destroy');
});

Route::middleware(['auth', 'permission:visa.create'])->group(function () {
    Route::get('visa-lots/create', [VisaRegisterController::class, 'createLot'])->name('visa-register.lots.create');
    Route::post('visa-lots', [VisaRegisterController::class, 'storeLot'])->name('visa-register.lots.store');
});

Route::middleware(['auth', 'permission:visa.create'])->group(function () {
    Route::get('visas/create', [VisaController::class, 'create'])->name('visas.create');
    Route::post('visas', [VisaController::class, 'store'])->name('visas.store');
});
Route::middleware(['auth', 'permission:visa.view'])->group(function () {
    Route::get('visas', [VisaController::class, 'index'])->name('visas.index');
    Route::get('visas/{visa}', [VisaController::class, 'show'])->name('visas.show')->whereNumber('visa');
});
Route::middleware(['auth', 'permission:visa.edit'])->group(function () {
    Route::get('visas/{visa}/edit', [VisaController::class, 'edit'])->name('visas.edit');
    Route::put('visas/{visa}', [VisaController::class, 'update'])->name('visas.update');
    Route::post('visas/{visa}/status', [VisaController::class, 'changeStatus'])->name('visas.status');
    Route::delete('visas/{visa}', [VisaController::class, 'destroy'])->name('visas.destroy');
});
Route::middleware(['auth', 'permission:visa.download'])->group(function () {
    Route::get('visas/{visa}/download/{field}', [VisaController::class, 'download'])->name('visas.download');
});

Route::middleware(['auth', 'permission:visa.edit'])->group(function () {
    Route::post('visas/{visa}/mofa', [\App\Http\Controllers\VisaController::class, 'updateMofa'])->name('visas.mofa');
    Route::post('visas/{visa}/work-permit', [\App\Http\Controllers\VisaController::class, 'storeWorkPermit'])->name('visas.work-permit');
    Route::post('visas/{visa}/appointment', [\App\Http\Controllers\VisaController::class, 'storeAppointment'])->name('visas.appointment');
});
