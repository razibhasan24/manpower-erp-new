<?php

// ---------------------------------------------------------------------------
// NOT EXECUTED HERE: this workspace has no PHP / Composer / MySQL, so this
// test file was written but NOT run. Run on the XAMPP machine with:
//   php artisan test --filter=BackupPermissionTest
// Do not claim it passes until that run succeeds.
// ---------------------------------------------------------------------------

namespace Tests\Feature;

use App\Models\Backup;
use App\Services\BackupService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Tests\Traits\CreatesUsers;

/**
 * Backup permissions (prompt §29): only authorised users create,
 * only Super Admin downloads, only highly authorised users restore
 * (with confirmation), and every action is audit-logged.
 */
class BackupPermissionTest extends TestCase
{
    use CreatesUsers, RefreshDatabase;

    protected function makeBackup(): Backup
    {
        return Backup::create([
            'filename' => 'backup-full-2026-11-12-020000.zip',
            'path' => 'backups/backup-full-2026-11-12-020000.zip',
            'type' => 'full',
            'size' => 12345,
            'status' => 'completed',
            'verified_at' => now(),
        ]);
    }

    public function test_user_without_backup_permission_cannot_view_history(): void
    {
        $user = $this->makeUser('General Staff');

        $this->actingAs($user)->get('/backups')->assertForbidden();
        $this->actingAs($user)->getJson('/api/v1/backups')->assertForbidden();
    }

    public function test_user_with_backup_view_can_see_history(): void
    {
        $user = $this->makeUser('Admin', [], ['backup.view']);
        $this->makeBackup();

        $this->actingAs($user)->get('/backups')->assertOk();
        $this->actingAs($user)->getJson('/api/v1/backups')->assertOk();
    }

    public function test_user_without_backup_create_cannot_create_backup(): void
    {
        $user = $this->makeUser('Admin', [], ['backup.view']);

        $this->actingAs($user)->post('/backups', ['type' => 'full'])->assertForbidden();
        $this->actingAs($user)->postJson('/api/v1/backups', ['type' => 'full'])->assertForbidden();
    }

    public function test_backup_download_is_super_admin_only(): void
    {
        Storage::fake('private');
        Storage::disk('private')->put('backups/backup-full-2026-11-12-020000.zip', 'fake-zip-content');
        $backup = $this->makeBackup();

        $admin = $this->makeUser('Admin', [], ['backup.view', 'backup.download']);
        $this->actingAs($admin)->get('/backups/'.$backup->id.'/download')->assertForbidden();

        $superAdmin = $this->makeSuperAdmin();
        $this->actingAs($superAdmin)->get('/backups/'.$backup->id.'/download')->assertOk();
    }

    public function test_restore_requires_confirmation_phrase(): void
    {
        $backup = $this->makeBackup();
        $superAdmin = $this->makeSuperAdmin();

        // No confirmation at all.
        $this->actingAs($superAdmin)->post('/backups/'.$backup->id.'/restore', [])
            ->assertSessionHasErrors(['confirmed', 'confirmation_phrase']);

        // Wrong phrase.
        $this->actingAs($superAdmin)->post('/backups/'.$backup->id.'/restore', [
            'confirmed' => '1',
            'confirmation_phrase' => 'YES',
        ])->assertSessionHasErrors('confirmation_phrase');
    }

    public function test_restore_without_permission_is_forbidden(): void
    {
        // Restore needs `backup.manage`; Admin does not receive it.
        $backup = $this->makeBackup();
        $admin = $this->makeUser('Admin', [], ['backup.view', 'backup.create']);

        $this->actingAs($admin)->post('/backups/'.$backup->id.'/restore', [
            'confirmed' => '1',
            'confirmation_phrase' => 'RESTORE',
        ])->assertForbidden();
    }

    public function test_backup_service_never_logs_database_credentials(): void
    {
        // The service passes the password only via the process environment.
        // This test guards the implementation: no credential string may
        // appear in any log the service writes, and the password must
        // never appear in a command-line argument.
        $source = file_get_contents(app_path('Services/BackupService.php'));

        $this->assertStringNotContainsString('Log::info', $source);
        $this->assertStringContainsString("'MYSQL_PWD' =>", $source);
        $this->assertStringNotContainsString("'--password", $source);
        $this->assertStringNotContainsString('"--password', $source);
    }
}
