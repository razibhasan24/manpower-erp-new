<?php

return [
    'default' => env('FILESYSTEM_DISK', 'private'),
    'disks' => [
        'local' => [
            'driver' => 'local',
            'root' => storage_path('app/private'),
            'serve' => true,
            'throw' => false,
        ],
        // Private disk (Phase 01 section 14). Uploaded files — passport
        // scans, vouchers, backups — live here and are NEVER in public/.
        // Downloads go through a controller that runs the Policy check.
        'private' => [
            'driver' => 'local',
            'root' => storage_path('app/private'),
            'visibility' => 'private',
            'throw' => false,
        ],
        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL') . '/storage',
            'visibility' => 'public',
            'throw' => false,
        ],
        'backups' => [
            'driver' => 'local',
            'root' => storage_path('app/private/backups'),
            'visibility' => 'private',
            'throw' => false,
        ],
    ],
    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],
];
