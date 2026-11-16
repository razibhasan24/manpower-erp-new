<?php

use App\Http\Controllers\Hrm\AttendanceController;
use App\Http\Controllers\Hrm\DepartmentController;
use App\Http\Controllers\Hrm\DesignationController;
use App\Http\Controllers\Hrm\EmployeeController;
use App\Http\Controllers\Hrm\EmployeeDocumentController;
use App\Http\Controllers\Hrm\HolidayController;
use App\Http\Controllers\Hrm\LeaveController;
use App\Http\Controllers\Hrm\PayrollController;
use App\Http\Controllers\Hrm\SalaryStructureController;
use Illuminate\Support\Facades\Route;

/**
 * HRM & Payroll web routes (Phase 26). Glob-loaded by routes/web.php (Group A).
 */
Route::middleware(['auth', 'verified'])->prefix('hrm')->name('hrm.')->group(function () {
    Route::resource('departments', DepartmentController::class)->middleware('permission:department.view');
    Route::resource('designations', DesignationController::class)->middleware('permission:designation.view');
    Route::resource('employees', EmployeeController::class)->middleware('permission:employee.view');
    Route::resource('holidays', HolidayController::class)->middleware('permission:holiday.view');
    Route::resource('leaves', LeaveController::class)->middleware('permission:leave.view');
    Route::resource('salary-structures', SalaryStructureController::class)->middleware('permission:salary_structure.view');

    Route::get('attendance', [AttendanceController::class, 'index'])->name('attendance.index')->middleware('permission:attendance.view');
    Route::post('attendance', [AttendanceController::class, 'store'])->name('attendance.store')->middleware('permission:attendance.create');
    Route::post('attendance/check-in', [AttendanceController::class, 'checkIn'])->name('attendance.check-in')->middleware('permission:attendance.create');
    Route::post('attendance/check-out', [AttendanceController::class, 'checkOut'])->name('attendance.check-out')->middleware('permission:attendance.create');
    Route::post('attendance/sync-biometric', [AttendanceController::class, 'syncBiometric'])->name('attendance.sync-biometric')->middleware('permission:attendance.create');
    Route::get('attendance/{employee}/summary', [AttendanceController::class, 'monthlySummary'])->name('attendance.summary')->middleware('permission:attendance.view');

    Route::resource('payroll', PayrollController::class)->only(['index', 'create', 'store', 'show'])->middleware('permission:payroll.view');
    Route::post('payroll/{payroll}/approve', [PayrollController::class, 'approve'])->name('payroll.approve')->middleware('permission:payroll.approve');
    Route::post('payroll/{payroll}/pay', [PayrollController::class, 'pay'])->name('payroll.pay')->middleware('permission:payroll.pay');
    Route::post('employees/{employee}/advance-salary', [PayrollController::class, 'advance'])->name('employees.advance-salary')->middleware('permission:payroll.create');

    Route::post('employees/{employee}/documents', [EmployeeDocumentController::class, 'store'])->name('employees.documents.store')->middleware('permission:employee.update');
    Route::get('employees/{employee}/documents/{document}/download', [EmployeeDocumentController::class, 'download'])->name('employees.documents.download')->middleware('permission:employee.view');
    Route::delete('employees/{employee}/documents/{document}', [EmployeeDocumentController::class, 'destroy'])->name('employees.documents.destroy')->middleware('permission:employee.update');
});
