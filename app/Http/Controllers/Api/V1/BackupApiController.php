<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\BackupResource;
use App\Models\Backup;
use App\Services\BackupService;
use Illuminate\Http\Request;

/**
 * Backup API (Phase 30). History + manual creation only.
 * Download and restore stay on the web UI: download is Super Admin
 * only and restore needs the typed confirmation step, which the API
 * deliberately does not expose.
 */
class BackupApiController extends ApiController
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Backup::class);

        $backups = Backup::visibleTo($request->user())->with('creator')
            ->orderByDesc('created_at')
            ->paginate($request->integer('per_page', $this->perPage));

        return $this->paginated($backups, BackupResource::collection($backups));
    }

    public function store(Request $request, BackupService $service)
    {
        $this->authorize('create', Backup::class);

        $validated = $request->validate([
            'type' => ['required', 'in:db,files,full'],
        ]);

        $backup = $service->create($validated['type'], $request->user());

        return $this->success(new BackupResource($backup), 'Backup created and verified.', 201);
    }
}
