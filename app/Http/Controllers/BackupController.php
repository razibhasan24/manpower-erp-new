<?php

namespace App\Http\Controllers;

use App\Http\Requests\BackupRestoreRequest;
use App\Models\Backup;
use App\Services\BackupService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * BackupController — Phase 29 (prompt §29).
 *
 * Permissions (seeded in Phase 05; names from Phase 01 §8.2):
 *   backup.view     — see backup history
 *   backup.create   — create a manual backup
 *   backup.download — download a backup file (Super Admin only)
 *   backup.restore  — restore (highly authorised only, + confirmation)
 *   backup.delete   — delete a backup (Super Admin only)
 *
 * Restore requires the typed confirmation phrase (see
 * BackupRestoreRequest) and is audit-logged by BackupService.
 */
class BackupController extends Controller
{
    public function __construct(protected BackupService $backups) {}

    public function index()
    {
        $this->authorize('viewAny', Backup::class);

        $backups = Backup::visibleTo(auth()->user())->with('creator')->orderByDesc('created_at')->paginate(20);

        return view('backups.index', compact('backups'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Backup::class);

        $validated = $request->validate([
            'type' => ['required', 'in:db,files,full'],
        ]);

        $backup = $this->backups->create($validated['type'], $request->user());

        return redirect()->route('backups.index')
            ->with('success', 'Backup created and verified: '.$backup->filename);
    }

    public function download(Backup $backup)
    {
        $this->authorize('download', $backup);

        abort_unless(Storage::disk(BackupService::DISK)->exists($backup->path), 404);

        return Storage::disk(BackupService::DISK)->download($backup->path, $backup->filename);
    }

    public function restoreForm(Backup $backup)
    {
        $this->authorize('restore', $backup);

        return view('backups.restore', compact('backup'));
    }

    public function restore(BackupRestoreRequest $request, Backup $backup)
    {
        $this->authorize('restore', $backup);

        $this->backups->restore($backup, $request->user(), $request->boolean('confirmed'));

        return redirect()->route('backups.index')
            ->with('success', 'Backup restored. A safety backup of the previous state was created first.');
    }

    public function destroy(Backup $backup)
    {
        $this->authorize('delete', $backup);

        $this->backups->delete($backup, auth()->user());

        return redirect()->route('backups.index')
            ->with('success', 'Backup deleted.');
    }
}
