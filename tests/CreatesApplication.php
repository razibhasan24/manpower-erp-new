<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;

/**
 * Creates the Laravel application for tests (MERGE FIX 2026-11-13,
 * coordinator). Standard Laravel 12 trait; missing alongside
 * tests/TestCase.php, so no test could run at all.
 */
trait CreatesApplication
{
    public function createApplication(): Application
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }
}
