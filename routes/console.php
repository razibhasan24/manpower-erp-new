<?php

use Illuminate\Support\Facades\Schedule;

// Phase 29 — scheduled backups (spatie/laravel-backup). Times are local
// (APP_TIMEZONE, Asia/Dhaka by default). Backup files are stored on the
// private disk and are downloadable by Super Admin only.
Schedule::command('backup:run')->dailyAt('02:00');
Schedule::command('backup:clean')->dailyAt('03:00');
Schedule::command('backup:monitor')->dailyAt('06:00');
