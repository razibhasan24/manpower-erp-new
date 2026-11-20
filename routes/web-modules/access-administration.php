<?php

use App\Http\Controllers\AccessAdministrationController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('audit-logs', [AccessAdministrationController::class, 'auditLogs'])
        ->middleware('permission:audit-log.view')->name('audit-logs.index');
    Route::get('login-history', [AccessAdministrationController::class, 'loginHistory'])
        ->middleware('permission:user.view')->name('login-history.index');
    Route::get('user-activity', [AccessAdministrationController::class, 'userActivity'])
        ->middleware('permission:user.view')->name('user-activity.index');
    Route::get('branch-user-permissions', [AccessAdministrationController::class, 'branchUserPermissions'])
        ->middleware('permission:user.manage')->name('branch-user-permissions.index');
    Route::get('roles', [AccessAdministrationController::class, 'roles'])
        ->middleware('permission:role.view')->name('roles.index');
    Route::get('permissions', [AccessAdministrationController::class, 'permissions'])
        ->middleware('permission:role.view')->name('permissions.index');
    Route::get('users', [AccessAdministrationController::class, 'users'])
        ->middleware('permission:user.view')->name('users.index');
    Route::get('users/create', [AccessAdministrationController::class, 'createUser'])
        ->middleware(['permission:user.create', 'permission:role.manage'])->name('users.create');
    Route::post('users', [AccessAdministrationController::class, 'storeUser'])
        ->middleware(['permission:user.create', 'permission:role.manage'])->name('users.store');
    Route::get('users/{user}/edit', [AccessAdministrationController::class, 'editUser'])
        ->middleware(['permission:user.edit', 'permission:role.manage'])->name('users.edit');
    Route::put('users/{user}', [AccessAdministrationController::class, 'updateUser'])
        ->middleware(['permission:user.edit', 'permission:role.manage'])->name('users.update');
    Route::get('roles/{role}/edit', [AccessAdministrationController::class, 'editRole'])
        ->middleware(['permission:role.edit', 'permission:role.manage'])->name('roles.edit');
    Route::put('roles/{role}', [AccessAdministrationController::class, 'updateRole'])
        ->middleware(['permission:role.edit', 'permission:role.manage'])->name('roles.update');
});
