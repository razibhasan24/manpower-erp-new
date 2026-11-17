<?php

namespace App\Services;

use App\Models\Delegation;
use App\Models\DelegationDocument;
use App\Models\Recruitment;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Services\Concerns\WorkflowStepper;

class DelegationService
{
    use WorkflowStepper;

    public const STATUSES = ['pending', 'approved', 'rejected', 'returned', 'cancelled'];

    public function __construct(protected WorkflowService $workflow) {}

    /** Scoped list query: isolation is enforced here, never in the view (Phase 01 section 8.3). */
    public function scopedQuery(User $user)
    {
        $query = Delegation::query()->with(['recruitment.passportHolder', 'recruitment.agent'])->latest();
        if (method_exists(Delegation::class, 'scopeVisibleTo')) {
            $query->visibleTo($user);
        }
        return $query;
    }

    public function create(array $data, ?User $user = null): Delegation
    {
        return DB::transaction(function () use ($data, $user) {
            $record = Delegation::create($this->attributes($data, $user));
            $this->storeFiles($record, $data);
            $recruitment = $record->recruitment;
            if ($recruitment) {
                $this->stepWorkflow($recruitment, 'delegation', $data['status'], $data['remarks'] ?? null, $user);
            }
            return $record->fresh();
        });
    }

    public function update(Delegation $delegation, array $data, ?User $user = null): Delegation
    {
        return DB::transaction(function () use ($delegation, $data, $user) {
            $delegation->update($this->attributes($data, $user, $delegation));
            $this->storeFiles($delegation, $data);
            if (isset($data['status']) || isset($data['result'])) {
                $recruitment = $delegation->recruitment;
                if ($recruitment) {
                    $this->stepWorkflow($recruitment, 'delegation', $data['status'], $data['remarks'] ?? null, $user);
                }
            }
            return $delegation->fresh();
        });
    }

    /** Status-only change: still goes through WorkflowService so history is append-only. */
    public function changeStatus(Delegation $delegation, string $status, ?string $remarks, ?User $user = null): Delegation
    {
        return DB::transaction(function () use ($delegation, $status, $remarks, $user) {
            $delegation->update(['status' => $status, 'remarks' => $remarks ?? $delegation->remarks]);
            $recruitment = $delegation->recruitment;
            if ($recruitment) {
                $this->stepWorkflow($recruitment, 'delegation', $status, $remarks, $user);
            }
            return $delegation->fresh();
        });
    }

    /** Cancel/archive only — recruitment records are never hard-deleted (Phase 01 section 7). */
    public function cancel(Delegation $delegation, ?string $remarks, ?User $user = null): Delegation
    {
        return $this->changeStatus($delegation, 'cancelled', $remarks, $user);
    }

    protected function attributes(array $data, ?User $user, ?Delegation $existing = null): array
    {
        $allowed = ['recruitment_id', 'application_date', 'reference_number', 'status', 'remarks'];
        $attrs = array_intersect_key($data, array_flip($allowed));
        if ($existing === null && $user) {
            $attrs['created_by'] = $user->id;
        }
        $attrs['updated_by'] = $user?->id;
        return $attrs;
    }

    /** Uploads go to the private disk and are served only through an authorised download (Phase 01 section 13). */
    protected function storeFiles(Delegation $delegation, array $data): void
    {
        // Delegations have no file columns; documents live in delegation_documents.
        foreach (['document' => 'document', 'e_duty_document' => 'e_duty'] as $input => $type) {
            if (! empty($data[$input]) && $data[$input] instanceof UploadedFile) {
                $path = $data[$input]->store('recruitments/' . $delegation->recruitment_id . '/delegation', 'private');
                DelegationDocument::create([
                    'delegation_id' => $delegation->id, 'type' => $type, 'file_path' => $path,
                    'original_name' => $data[$input]->getClientOriginalName(),
                    'mime_type' => $data[$input]->getMimeType(), 'size' => $data[$input]->getSize(),
                    'uploaded_by' => $delegation->created_by,
                ]);
            }
        }
    }

    public function downloadPath(Delegation $delegation, string $field): ?string
    {
        $type = $field === 'e_duty_document_path' || $field === 'e_duty_document' ? 'e_duty' : 'document';
        $doc = DelegationDocument::where('delegation_id', $delegation->id)->where('type', $type)->latest()->first();
        $path = $doc?->file_path;
        return $path && Storage::disk('private')->exists($path) ? $path : null;
    }
}
