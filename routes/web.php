<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\PasswordResetSuccessController;
use App\Http\Controllers\CompanyProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MyPortalController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Web Routes — Group A (Foundation)
|--------------------------------------------------------------------------
| Core routes only: auth, dashboard, settings/branding, profile.
| Every other module registers its routes in routes/web-modules/*.php,
| which are loaded by the glob at the bottom of this file. Do NOT add
| module routes here.
*/

// Public branding asset. The login page and password-reset email must be
// able to display the logo before a session exists; only this one file is
// exposed, and it is served from the private disk through a controlled route.
Route::get('/branding/logo', function () {
    $profile = \App\Services\BrandingService::profile();

    if ($profile === null || empty($profile->logo_path)
        || ! Storage::disk('private')->exists($profile->logo_path)) {
        abort(404);
    }

    return Storage::disk('private')->response($profile->logo_path, null, [
        'Cache-Control' => 'public, max-age=3600',
    ]);
})->name('branding.logo');

// Guest: login + password reset.
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:5,1');

    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('password.email');

    // Must be declared before /reset-password/{token}.
    Route::get('/reset-password/success', PasswordResetSuccessController::class)
        ->name('password.reset.success');
    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('password.store');
});

// Authenticated core routes.
Route::middleware(['auth', 'branch.scope'])->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Canonical dashboard URL. The root URL redirects here so the named
    // "dashboard" route and the existing authentication tests agree.
    Route::get('/', fn () => redirect()->route('dashboard'))->name('home');

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware('permission:dashboard.view')
        ->name('dashboard');

    Route::get('/dashboard/recruitment', [DashboardController::class, 'recruitment'])
        ->middleware(['permission:dashboard.view', 'permission:recruitment.view'])
        ->name('dashboard.recruitment');

    Route::get('/dashboard/financial', [DashboardController::class, 'financial'])
        ->middleware(['permission:dashboard.view', 'permission:accounting.view'])
        ->name('dashboard.financial');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Agent / Passport Holder self-service portal. The controller resolves the
    // party from the authenticated user and enforces ownership; no id is ever
    // accepted from the URL.
    Route::middleware('permission:ledger.view')->group(function () {
        Route::get('/my/ledger', [MyPortalController::class, 'ledger'])->name('my.ledger');
        Route::get('/my/payments', [MyPortalController::class, 'payments'])->name('my.payments');
    });
    Route::get('/my/documents', [MyPortalController::class, 'documents'])
        ->middleware('permission:document.view')->name('my.documents');
    Route::get('/my/documents/{document}/download', [MyPortalController::class, 'downloadDocument'])
        ->middleware('permission:document.download')->name('my.documents.download');

    Route::get('/settings', [SettingsController::class, 'index'])
        ->middleware('permission:setting.view')->name('settings.index');
    Route::put('/settings', [SettingsController::class, 'update'])
        ->middleware('permission:setting.edit')->name('settings.update');

    Route::get('/settings/company-profile', [CompanyProfileController::class, 'edit'])
        ->middleware('permission:setting.view')->name('company-profile.edit');
    Route::put('/settings/company-profile', [CompanyProfileController::class, 'update'])
        ->middleware('permission:setting.edit')->name('company-profile.update');
});

// Module routes (other groups). Each file in routes/web-modules/*.php is
// a self-contained Route group; adding a module never edits this file.
foreach (glob(__DIR__.'/web-modules/*.php') as $file) {
    require $file;
}
