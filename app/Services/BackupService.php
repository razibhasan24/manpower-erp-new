<?php

namespace App\Services;

use App\Jobs\RunBackupJob;
use App\Models\Backup;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use ZipArchive;

/**
 * BackupService — Phase 29 (Backup & Restore, prompt §29).
 *
 * Backup types:
 *   db    — MySQL dump only
 *   files — uploaded files (private disk) only
 *   full  — database + files in one archive
 *
 * Storage: the `private` disk, under `backups/`. Backup files are never
 * publicly accessible (Phase 01 §14). Integrity is verified after every
 * creation (the archive must open cleanly; see verify()).
 *
 * NOTE (Group E, 12 Nov 2026): the shipped `backups` table (Group A
 * migration 000069) has NO checksum column, so no SHA-256 is stored.
 * Verification therefore checks archive integrity only. If a checksum
 * column is added later (new migration), record the hash here again.
 *
 * Security rules enforced here and in BackupController:
 *   - Only users with `backup.create` may create; `backup.download` to
 *     download (Super Admin only, per controller); `backup.restore` to
 *     restore, with an explicit confirmation flag.
 *   - Database credentials are read from config and passed to mysqldump
 *     via environment variables / process input. They are NEVER written
 *     to a log, an exception message, or a response.
 *   - Every create / download / restore / delete writes an audit row.
 *
 * Optional: if spatie/laravel-backup is installed (Phase 01 §3), the
 * config/backup.php shipped with this phase configures it; this service
 * is the native fallback that works without any extra package, using only
 * mysqldump + ZipArchive, both available on XAMPP.
 */
class BackupService
{
    public const TYPES = ['db', 'files', 'full'];

    public const DISK = 'private';

    public const DIRECTORY = 'backups';

    /**
     * Create a backup synchronously and record it in `backups`.
     */
    public function create(string $type, ?User $createdBy = null): Backup
    {
        if (! in_array($type, self::TYPES, true)) {
            throw new \InvalidArgumentException("Unknown backup type '{$type}'.");
        }

        $timestamp = now()->format('Y-m-d-His');
        $filename = "backup-{$type}-{$timestamp}.zip";
        $relativePath = self::DIRECTORY.'/'.$filename;

        $tmpDir = storage_path('app/private/'.self::DIRECTORY.'/.tmp-'.$timestamp);
        if (! is_dir($tmpDir)) {
            mkdir($tmpDir, 0755, true);
        }

        try {
            if ($type === 'db' || $type === 'full') {
                $this->dumpDatabase($tmpDir.'/database.sql');
            }

            if ($type === 'files' || $type === 'full') {
                $this->copyUploads($tmpDir.'/files');
            }

            $zip = new ZipArchive();
            $zipPath = storage_path('app/private/'.$relativePath);
            if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
                throw new \RuntimeException('Could not create backup archive.');
            }
            $this->addDirectoryToZip($zip, $tmpDir, '');
            $zip->close();
        } finally {
            $this->removeDirectory($tmpDir);
        }

        $size = Storage::disk(self::DISK)->size($relativePath);

        $backup = Backup::create([
            'filename' => $filename,
            'path' => $relativePath,
            'type' => $type,
            'size' => $size,
            'created_by' => $createdBy?->id,
            'status' => 'completed',
            'verified_at' => now(),
        ]);

        // Integrity verification immediately after creation (§29).
        if (! $this->verify($backup)) {
            $backup->update(['status' => 'failed']);
            Log::error('Backup integrity verification failed', ['backup_id' => $backup->id]);
            throw new \RuntimeException('Backup was created but failed integrity verification.');
        }

        $this->audit('backup.created', $createdBy, $backup, ['type' => $type, 'size' => $size]);

        return $backup;
    }

    /**
     * Queue a backup for background execution (used by the daily schedule).
     */
    public function createAsync(string $type, ?User $createdBy = null): void
    {
        RunBackupJob::dispatch($type, $createdBy?->id);
    }

    /**
     * Verify a backup's integrity: file exists and the archive opens
     * cleanly (ZipArchive consistency check). No checksum comparison:
     * the shipped schema stores no checksum (see class docblock).
     */
    public function verify(Backup $backup): bool
    {
        $disk = Storage::disk(self::DISK);

        if (! $disk->exists($backup->path)) {
            return false;
        }

        $zip = new ZipArchive();
        $fullPath = storage_path('app/private/'.$backup->path);
        if ($zip->open($fullPath, ZipArchive::CHECKCONS) !== true) {
            return false;
        }
        $zip->close();

        $backup->update(['verified_at' => now(), 'status' => 'completed']);

        return true;
    }

    /**
     * Restore a backup. Highly authorised only (controller enforces
     * `backup.restore` + a typed confirmation). A fresh backup of the
     * CURRENT database is taken first, so a bad restore can be undone.
     *
     * Database restore replaces data; files restore overwrites uploaded
     * files. This is intentionally a manual, audited, confirmed operation
     * — it is never run from a schedule.
     */
    public function restore(Backup $backup, User $restoredBy, bool $confirmed): void
    {
        if (! $confirmed) {
            throw new \InvalidArgumentException('Restore requires explicit confirmation.');
        }

        if (! $this->verify($backup)) {
            throw new \RuntimeException('Backup failed integrity verification and cannot be restored.');
        }

        // Safety net: back up the current state before touching anything.
        $safety = $this->create('full', $restoredBy);
        Log::warning('Restore started; safety backup created first', [
            'restoring_backup_id' => $backup->id,
            'safety_backup_id' => $safety->id,
        ]);

        $tmpDir = storage_path('app/private/'.self::DIRECTORY.'/.restore-'.now()->format('YmdHis'));
        mkdir($tmpDir, 0755, true);

        try {
            $zip = new ZipArchive();
            $zip->open(storage_path('app/private/'.$backup->path));
            $zip->extractTo($tmpDir);
            $zip->close();

            if (($backup->type === 'db' || $backup->type === 'full') && file_exists($tmpDir.'/database.sql')) {
                $this->importDatabase($tmpDir.'/database.sql');
            }

            if (($backup->type === 'files' || $backup->type === 'full') && is_dir($tmpDir.'/files')) {
                $this->restoreUploads($tmpDir.'/files');
            }
        } finally {
            $this->removeDirectory($tmpDir);
        }

        $this->audit('backup.restored', $restoredBy, $backup, ['safety_backup_id' => $safety->id]);
    }

    /**
     * Delete a backup file and its history row (Super Admin only).
     */
    public function delete(Backup $backup, ?User $deletedBy = null): void
    {
        Storage::disk(self::DISK)->delete($backup->path);
        $this->audit('backup.deleted', $deletedBy, $backup, []);
        $backup->delete();
    }

    // ------------------------------------------------------------------
    // Internals
    // ------------------------------------------------------------------

    protected function dumpDatabase(string $targetPath): void
    {
        $connection = config('database.connections.'.config('database.default'));

        $process = new Process([
            'mysqldump',
            '--host='.$connection['host'],
            '--port='.(string) ($connection['port'] ?? 3306),
            '--user='.$connection['username'],
            '--single-transaction',
            '--routines',
            '--result-file='.$targetPath,
            $connection['database'],
        ]);

        // Password via environment so it never appears in a command line,
        // a process list, or a log.
        $process->setEnv(['MYSQL_PWD' => $connection['password']]);
        $process->setTimeout(3600);
        $process->run();

        if (! $process->isSuccessful()) {
            // Log only the exit code, never the output (it may contain data).
            Log::error('mysqldump failed', ['exit_code' => $process->getExitCode()]);
            throw new ProcessFailedException($process);
        }
    }

    protected function importDatabase(string $sourcePath): void
    {
        $connection = config('database.connections.'.config('database.default'));

        $process = new Process([
            'mysql',
            '--host='.$connection['host'],
            '--port='.(string) ($connection['port'] ?? 3306),
            '--user='.$connection['username'],
            $connection['database'],
        ]);
        $process->setEnv(['MYSQL_PWD' => $connection['password']]);
        $process->setInput(file_get_contents($sourcePath));
        $process->setTimeout(3600);
        $process->run();

        if (! $process->isSuccessful()) {
            Log::error('mysql import failed', ['exit_code' => $process->getExitCode()]);
            throw new ProcessFailedException($process);
        }
    }

    protected function copyUploads(string $targetDir): void
    {
        $source = storage_path('app/private');
        $this->copyDirectoryExcluding($source, $targetDir, [self::DIRECTORY]);
    }

    protected function restoreUploads(string $sourceDir): void
    {
        $target = storage_path('app/private');
        $this->copyDirectoryExcluding($sourceDir, $target, [self::DIRECTORY]);
    }

    protected function copyDirectoryExcluding(string $source, string $target, array $excludeTopLevel): void
    {
        if (! is_dir($target)) {
            mkdir($target, 0755, true);
        }

        foreach (scandir($source) as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }
            if ($source === storage_path('app/private') && in_array($item, $excludeTopLevel, true)) {
                continue;
            }
            $from = $source.'/'.$item;
            $to = $target.'/'.$item;
            if (is_dir($from)) {
                $this->copyDirectoryExcluding($from, $to, []);
            } else {
                copy($from, $to);
            }
        }
    }

    protected function addDirectoryToZip(ZipArchive $zip, string $dir, string $prefix): void
    {
        foreach (scandir($dir) as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }
            $path = $dir.'/'.$item;
            $local = $prefix === '' ? $item : $prefix.'/'.$item;
            if (is_dir($path)) {
                $zip->addEmptyDir($local);
                $this->addDirectoryToZip($zip, $path, $local);
            } else {
                $zip->addFile($path, $local);
            }
        }
    }

    protected function removeDirectory(string $dir): void
    {
        if (! is_dir($dir)) {
            return;
        }
        foreach (scandir($dir) as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }
            $path = $dir.'/'.$item;
            is_dir($path) ? $this->removeDirectory($path) : unlink($path);
        }
        rmdir($dir);
    }

    protected function audit(string $action, ?User $user, Backup $backup, array $context): void
    {
        if (! Schema::hasTable('audit_logs')) {
            return;
        }

        DB::table('audit_logs')->insert([
            'user_id' => $user?->id,
            'action' => $action,
            'auditable_type' => Backup::class,
            'auditable_id' => $backup->id,
            'old_values' => null,
            'new_values' => json_encode(array_merge(['filename' => $backup->filename, 'type' => $backup->type], $context)),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now(),
        ]);
    }
}
