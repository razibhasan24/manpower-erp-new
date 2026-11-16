<?php

use Illuminate\Support\Str;

return [
    'driver' => env('SESSION_DRIVER', 'database'),
    'lifetime' => env('SESSION_LIFETIME', 120),
    'expire_on_close' => false,
    'encrypt' => env('SESSION_ENCRYPT', false),
    'files' => storage_path('framework/sessions'),
    'connection' => env('SESSION_CONNECTION'),
    'table' => 'sessions',
    'store' => env('SESSION_STORE'),
    'lottery' => [2, 100],
    'cookie' => env('SESSION_COOKIE', Str::slug(env('APP_NAME', 'laravel'), '_') . '_session'),
    'path' => env('SESSION_PATH', '/'),
    'domain' => env('SESSION_DOMAIN'),
    // Secure cookies in production; SameSite=lax (Phase 01 section 13).
    'secure' => env('SESSION_SECURE_COOKIE', env('APP_ENV') === 'production'),
    'http_only' => true,
    'same_site' => env('SESSION_SAME_SITE', 'lax'),
    'partitioned' => false,
];
