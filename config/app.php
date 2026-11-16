<?php

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\ServiceProvider;

return [
    'name' => env('APP_NAME', 'Manpower Recruitment & Overseas Employment ERP'),
    'env' => env('APP_ENV', 'production'),
    'debug' => (bool) env('APP_DEBUG', false),
    'url' => env('APP_URL', 'http://localhost'),
    'asset_url' => env('ASSET_URL'),
    'timezone' => env('APP_TIMEZONE', 'Asia/Dhaka'),
    'locale' => env('APP_LOCALE', 'en'),
    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'en'),
    'faker_locale' => env('FAKER_LOCALE', 'en_US'),
    'key' => env('APP_KEY'),
    'cipher' => 'AES-256-CBC',
    'maintenance' => [
        'driver' => 'file',
    ],
    'providers' => ServiceProvider::defaultProviders()->merge([
        App\Providers\AppServiceProvider::class,
        Spatie\Permission\PermissionServiceProvider::class,
        Laravel\Sanctum\SanctumServiceProvider::class,
        Barryvdh\DomPDF\ServiceProvider::class,
        Maatwebsite\Excel\ExcelServiceProvider::class,
        Spatie\Backup\BackupServiceProvider::class,
    ])->toArray(),
    'aliases' => Facade::defaultAliases()->merge([
        'Excel' => Maatwebsite\Excel\Facades\Excel::class,
        'PDF' => Barryvdh\DomPDF\Facade\Pdf::class,
    ])->toArray(),
];
