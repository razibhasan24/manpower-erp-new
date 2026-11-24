<?php

use App\Http\Controllers\TrainingAttendanceController;
use App\Http\Controllers\TrainingCertificateController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'permission:attendance.view'])->group(function () {
    Route::get('training-attendance', [TrainingAttendanceController::class, 'index'])->name('training-attendance.index');
});
Route::middleware(['auth', 'permission:attendance.create'])->group(function () {
    Route::get('training-attendance/create', [TrainingAttendanceController::class, 'create'])->name('training-attendance.create');
    Route::post('training-attendance', [TrainingAttendanceController::class, 'store'])->name('training-attendance.store');
});
Route::middleware(['auth', 'permission:attendance.update'])->group(function () {
    Route::get('training-attendance/{trainingAttendance}/edit', [TrainingAttendanceController::class, 'edit'])->whereNumber('trainingAttendance')->name('training-attendance.edit');
    Route::put('training-attendance/{trainingAttendance}', [TrainingAttendanceController::class, 'update'])->whereNumber('trainingAttendance')->name('training-attendance.update');
});
Route::middleware(['auth', 'permission:attendance.delete'])->group(function () {
    Route::delete('training-attendance/{trainingAttendance}', [TrainingAttendanceController::class, 'destroy'])->whereNumber('trainingAttendance')->name('training-attendance.destroy');
});

Route::middleware(['auth', 'permission:training.view'])->group(function () {
    Route::get('training-certificates', [TrainingCertificateController::class, 'index'])->name('training-certificates.index');
    Route::get('training-certificates/{trainingCertificate}', [TrainingCertificateController::class, 'show'])->whereNumber('trainingCertificate')->name('training-certificates.show');
});
Route::middleware(['auth', 'permission:training.create'])->group(function () {
    Route::get('training-certificates/create', [TrainingCertificateController::class, 'create'])->name('training-certificates.create');
    Route::post('training-certificates', [TrainingCertificateController::class, 'store'])->name('training-certificates.store');
});
Route::middleware(['auth', 'permission:training.edit'])->group(function () {
    Route::get('training-certificates/{trainingCertificate}/edit', [TrainingCertificateController::class, 'edit'])->whereNumber('trainingCertificate')->name('training-certificates.edit');
    Route::put('training-certificates/{trainingCertificate}', [TrainingCertificateController::class, 'update'])->whereNumber('trainingCertificate')->name('training-certificates.update');
});
Route::middleware(['auth', 'permission:training.delete'])->group(function () {
    Route::delete('training-certificates/{trainingCertificate}', [TrainingCertificateController::class, 'destroy'])->whereNumber('trainingCertificate')->name('training-certificates.destroy');
});
Route::middleware(['auth', 'permission:training.download'])->group(function () {
    Route::get('training-certificates/{trainingCertificate}/download', [TrainingCertificateController::class, 'download'])->whereNumber('trainingCertificate')->name('training-certificates.download');
});
