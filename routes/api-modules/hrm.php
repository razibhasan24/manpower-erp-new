<?php

use App\Http\Controllers\Hrm\AttendanceController;
use App\Http\Controllers\Hrm\DepartmentController;
use App\Http\Controllers\Hrm\DesignationController;
use App\Http\Controllers\Hrm\EmployeeController;
use App\Http\Controllers\Hrm\HolidayController;
use App\Http\Controllers\Hrm\LeaveController;
use App\Http\Controllers\Hrm\PayrollController;
use App\Http\Controllers\Hrm\SalaryStructureController;
use Illuminate\Support\Facades\Route;

/**
 * HRM & Payroll API routes, v1 (Phase 26). Glob-loaded by routes/api.php (Group A).
 */
Route::middleware(['auth:sanctum'])->prefix('v1/hrm')->group(function () {
    Route::apiResource('departments', DepartmentController::class);
    Route::apiResource('designations', DesignationController::class);
    Route::apiResource('employees', EmployeeController::class);
    Route::apiResource('holidays', HolidayController::class);
    Route::apiResource('leaves', LeaveController::class);
    Route::apiResource('salary-structures', SalaryStructureController::class);
    Route::apiResource('payroll', PayrollController::class)->only(['index', 'store', 'show']);
    Route::post('payroll/{payroll}/approve', [PayrollController::class, 'approve']);
    Route::post('payroll/{payroll}/pay', [PayrollController::class, 'pay']);
    Route::post('employees/{employee}/advance-salary', [PayrollController::class, 'advance']);

    Route::get('attendance', [AttendanceController::class, 'index']);
    Route::post('attendance', [AttendanceController::class, 'store']);
    Route::post('attendance/check-in', [AttendanceController::class, 'checkIn']);
    Route::post('attendance/check-out', [AttendanceController::class, 'checkOut']);
    Route::post('attendance/sync-biometric', [AttendanceController::class, 'syncBiometric']);
});
