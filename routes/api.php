<?php

use App\Http\Controllers\Auth\ApiTokenController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes — Group A (Foundation)
|--------------------------------------------------------------------------
| Versioned from day one: /api/v1/.... Web and API share the same
| Services and Policies (Phase 01 section 11). Module endpoints live in
| routes/api-modules/*.php, loaded by the glob at the bottom.
*/

Route::prefix('v1')->group(function () {
    // Token login for the future mobile app (Sanctum, Phase 30).
    Route::post('/login', [ApiTokenController::class, 'store'])
        ->middleware('throttle:5,1')->name('api.login');

    Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
        Route::get('/me', [ApiTokenController::class, 'me'])->name('api.me');
        Route::get('/dashboard', [DashboardController::class, 'apiStats'])->name('api.dashboard');
        Route::post('/logout', [ApiTokenController::class, 'destroy'])->name('api.logout');
    });
});

// Module API routes (other groups).
foreach (glob(__DIR__.'/api-modules/*.php') as $file) {
    require $file;
}
