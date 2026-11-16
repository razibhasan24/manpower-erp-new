<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

/**
 * Base test case (MERGE FIX 2026-11-13, coordinator).
 *
 * Group E wrote 111 tests referencing Tests\TestCase, but the file did
 * not exist, so no test could run at all. This is the standard
 * Laravel 12 base test case. Tests were still NOT executed here —
 * this workspace has no PHP/Composer/MySQL. Run on the XAMPP machine:
 *   php artisan test
 */
abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
}
