<?php

use App\Http\Controllers\ConfigurationModuleController;
use Illuminate\Support\Facades\Route;

Route::get('configuration-modules/{module}', [ConfigurationModuleController::class, 'show'])
    ->where('module', 'system-configuration|pdf-settings|invoice-settings|email-settings|notification-settings|workflow-settings')
    ->middleware(['auth', 'verified', 'permission:setting.edit'])
    ->name('configuration-modules.show');
