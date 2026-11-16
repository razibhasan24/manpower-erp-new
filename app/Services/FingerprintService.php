<?php

namespace App\Services;

use App\Models\Fingerprint;
use App\Models\Recruitment;
use App\Models\RecruitmentDocument;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Services\Concerns\WorkflowStepper;

class FingerprintService
{
    use WorkflowStepper;

    public const STATUSES = ['pending', 'submitted', 'completed', 'rejected', 'cancelled'];

    public function __construct(protected WorkflowService $workflow) {}

    /** 'submitted' is a fingerprint-record status, not a workflow status; the recruitment stays pending at this stage. */
    protected function fingerprintWorkflowStatus(string $status): string
    {
        return $status === 'submitted' ? 'pending' : $status;
    }

    /** Scoped list query: isolation is enforced here, never in the view (Phase 01 section 8.3). */
    public function scopedQuery(User $user)
    {
        $query = Fingerprint::query()->with(['recruitment.passportHolder', 'recruitment.agent'])->latest();
        if (method_exists(Fingerprint::class, 'scopeVisibleTo')) {
            $query->visibleTo($user);
        }
        return $query;
    }

    public function create(array $data, ?User $user = null): Fingerprint
    {
        return DB::transaction(function () use ($data, $user) {
            $record = Fingerprint::create($this->attributes($data, $user));
            $this->storeFiles($record, $data);
            $recruitment = $record->recruitment;
            if ($recruitment) {
                $this->stepWorkflow($recruitment, 'fingerprint', $this->fingerprintWorkflowStatus($data['status']), $data['remarks'] ?? null, $user);
            }
            return $record->fresh();
        });
    }

    public function update(Fingerprint $fingerprint, array $data, ?User $user = null): Fingerprint
    {
        return DB::transaction(function () use ($fingerprint, $data, $user) {
            $fingerprint->update($this->attributes($data, $user, $fingerprint));
            $this->storeFiles($fingerprint, $data);
            if (isset($data['status']) || isset($data['result'])) {
                $recruitment = $fingerprint->recruitment;
                if ($recruitment) {
                    $this->stepWorkflow($recruitment, 'fingerprint', $this->fingerprintWorkflowStatus($data['status']), $data['remarks'] ?? null, $user);
                }
            }
            return $fingerprint->fresh();
        });
    }

    /** Status-only change: still goes through WorkflowService so history is append-only. */
    public function changeStatus(Fingerprint $fingerprint, string $status, ?string $remarks, ?User $user = null): Fingerprint
    {
        return DB::transaction(function () use ($fingerprint, $status, $remarks, $user) {
            $fingerprint->update(['status' => $status, 'remarks' => $remarks ?? $fingerprint->remarks]);
            $recruitment = $fingerprint->recruitment;
            if ($recruitment) {
                $this->stepWorkflow($recruitment, 'fingerprint', $this->fingerprintWorkflowStatus($status), $remarks, $user);
            }
            return $fingerprint->fresh();
        });
    }

    /** Cancel/archive only — recruitment records are never hard-deleted (Phase 01 section 7). */
    public function cancel(Fingerprint $fingerprint, ?string $remarks, ?User $user = null): Fingerprint
    {
        return $this->changeStatus($fingerprint, 'cancelled', $remarks, $user);
    }

    protected function attributes(array $data, ?User $user, ?Fingerprint $existing = null): array
    {
        $allowed = ['appointment_date', 'submission_date', 'status', 'reference_number', 'remarks'];
        $attrs = array_intersect_key($data, array_flip($allowed));
        if ($existing === null && $user) {
            $attrs['created_by'] = $user->id;
        }
        $attrs['updated_by'] = $user?->id;
        return $attrs;
    }

    /** Uploads go to the private disk and are served only through an authorised download (Phase 01 section 13). */
    protected function storeFiles(Fingerprint $fingerprint, array $data): void
    {
        // Fingerprints have no file column; the document is a recruitment document (stage fingerprint).
        foreach (['document_path'] as $field) {
            if (! empty($data[$field]) && $data[$field] instanceof UploadedFile) {
                $path = $data[$field]->store('recruitments/' . $fingerprint->recruitment_id . '/fingerprint', 'private');
                RecruitmentDocument::create([
                    'recruitment_id' => $fingerprint->recruitment_id, 'type' => 'fingerprint', 'stage' => 'fingerprint',
                    'file_path' => $path, 'original_name' => $data[$field]->getClientOriginalName(),
                    'mime_type' => $data[$field]->getMimeType(), 'size' => $data[$field]->getSize(),
                    'uploaded_by' => $fingerprint->created_by,
                ]);
            }
        }
    }

    public function downloadPath(Fingerprint $fingerprint, string $field): ?string
    {
        $doc = RecruitmentDocument::where('recruitment_id', $fingerprint->recruitment_id)->where('stage', 'fingerprint')->latest()->first();
        $path = $doc?->file_path;
        return $path && Storage::disk('private')->exists($path) ? $path : null;
    }
}
