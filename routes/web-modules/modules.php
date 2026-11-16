<?php

// Development-status pages for planned modules. One named route, with the
// module key validated against config/modules.php by the controller. Every
// page is permission-checked server-side.

use App\Http\Controllers\ModuleStatusController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('modules/{module}', [ModuleStatusController::class, 'show'])
        ->where('module', '[a-z0-9-]+')
        ->name('modules.show');
});
