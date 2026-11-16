<?php

// ---------------------------------------------------------------------------
// NOT EXECUTED HERE: this workspace has no PHP / Composer / MySQL, so this
// test file was written but NOT run. Run on the XAMPP machine with:
//   php artisan test --filter=BackupServiceTest
// Do not claim it passes until that run succeeds.
// ---------------------------------------------------------------------------

namespace Tests\Unit;

use App\Models\Backup;
use App\Services\BackupService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Tests\Traits\CreatesUsers;

class BackupServiceTest extends TestCase
{
    use CreatesUsers, RefreshDatabase;

    public function test_verify_fails_for_missing_file(): void
    {
        Storage::fake('private');

        $backup = Backup::create([
            'filename' => 'missing.zip',
            'path' => 'backups/missing.zip',
            'type' => 'db',
            'size' => 0,
            'status' => 'completed',
        ]);

        $this->assertFalse(app(BackupService::class)->verify($backup));
    }

    public function test_verify_fails_for_corrupt_archive(): void
    {
        // The shipped schema stores no checksum, so verification is the
        // archive integrity check: a file that is not a valid zip fails.
        Storage::fake('private');
        Storage::disk('private')->put('backups/tampered.zip', 'this-is-not-a-zip-archive');

        $backup = Backup::create([
            'filename' => 'tampered.zip',
            'path' => 'backups/tampered.zip',
            'type' => 'db',
            'size' => 16,
            'status' => 'completed',
        ]);

        $this->assertFalse(app(BackupService::class)->verify($backup));
    }

    public function test_verify_passes_for_intact_archive(): void
    {
        Storage::fake('private');

        $zip = new \ZipArchive();
        $path = Storage::disk('private')->path('backups/good.zip');
        Storage::disk('private')->makeDirectory('backups');
        $zip->open($path, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
        $zip->addFromString('database.sql', 'SELECT 1;');
        $zip->close();

        $backup = Backup::create([
            'filename' => 'good.zip',
            'path' => 'backups/good.zip',
            'type' => 'db',
            'size' => Storage::disk('private')->size('backups/good.zip'),
            'status' => 'completed',
        ]);

        $this->assertTrue(app(BackupService::class)->verify($backup));
        $this->assertNotNull($backup->fresh()->verified_at);
    }

    public function test_create_rejects_unknown_type(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        app(BackupService::class)->create('everything', null);
    }

    public function test_restore_requires_confirmation(): void
    {
        $backup = Backup::create([
            'filename' => 'x.zip',
            'path' => 'backups/x.zip',
            'type' => 'db',
            'size' => 0,
            'status' => 'completed',
        ]);

        $this->expectException(\InvalidArgumentException::class);

        app(BackupService::class)->restore($backup, $this->makeSuperAdmin(), false);
    }

    public function test_backup_types_are_exactly_db_files_full(): void
    {
        $this->assertSame(['db', 'files', 'full'], BackupService::TYPES);
    }
}
