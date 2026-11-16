<?php

namespace App\Jobs;

use App\Services\BackupService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * RunBackupJob — Phase 29.
 * Runs a scheduled (daily) backup in the background so the request that
 * triggered it never waits on mysqldump / zipping. Retried by the queue
 * on failure and logged — never silently dropped (Phase 01 §14).
 */
class RunBackupJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 2;

    public function __construct(
        public string $type = 'full',
        public ?int $createdById = null,
    ) {}

    public function handle(BackupService $service): void
    {
        $user = $this->createdById
            ? \App\Models\User::find($this->createdById)
            : null;

        $service->create($this->type, $user);
    }
}
