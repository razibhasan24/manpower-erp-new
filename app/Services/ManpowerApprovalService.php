<?php

namespace App\Services;

use App\Models\ManpowerApproval;
use App\Models\Recruitment;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Services\Concerns\WorkflowStepper;

class ManpowerApprovalService
{
    use WorkflowStepper;

    public const STATUSES = ['pending', 'approved', 'rejected', 'returned', 'cancelled'];

    public function __construct(protected WorkflowService $workflow) {}

    /** Scoped list query: isolation is enforced here, never in the view (Phase 01 section 8.3). */
    public function scopedQuery(User $user)
    {
        $query = ManpowerApproval::query()->with(['recruitment.passportHolder', 'recruitment.agent'])->latest();
        if (method_exists(ManpowerApproval::class, 'scopeVisibleTo')) {
            $query->visibleTo($user);
        }
        return $query;
    }

    public function create(array $data, ?User $user = null): ManpowerApproval
    {
        return DB::transaction(function () use ($data, $user) {
            $record = ManpowerApproval::create($this->attributes($data, $user));
            $this->storeFiles($record, $data);
            $recruitment = $record->recruitment;
            if ($recruitment) {
                $this->stepWorkflow($recruitment, 'manpower_approval', $data['status'], $data['remarks'] ?? null, $user);
            }
            return $record->fresh();
        });
    }

    public function update(ManpowerApproval $manpowerApproval, array $data, ?User $user = null): ManpowerApproval
    {
        return DB::transaction(function () use ($manpowerApproval, $data, $user) {
            $manpowerApproval->update($this->attributes($data, $user, $manpowerApproval));
            $this->storeFiles($manpowerApproval, $data);
            if (isset($data['status']) || isset($data['result'])) {
                $recruitment = $manpowerApproval->recruitment;
                if ($recruitment) {
                    $this->stepWorkflow($recruitment, 'manpower_approval', $data['status'], $data['remarks'] ?? null, $user);
                }
            }
            return $manpowerApproval->fresh();
        });
    }

    /** Status-only change: still goes through WorkflowService so history is append-only. */
    public function changeStatus(ManpowerApproval $manpowerApproval, string $status, ?string $remarks, ?User $user = null): ManpowerApproval
    {
        return DB::transaction(function () use ($manpowerApproval, $status, $remarks, $user) {
            $manpowerApproval->update(['status' => $status, 'remarks' => $remarks ?? $manpowerApproval->remarks]);
            $recruitment = $manpowerApproval->recruitment;
            if ($recruitment) {
                $this->stepWorkflow($recruitment, 'manpower_approval', $status, $remarks, $user);
            }
            return $manpowerApproval->fresh();
        });
    }

    /** Cancel/archive only — recruitment records are never hard-deleted (Phase 01 section 7). */
    public function cancel(ManpowerApproval $manpowerApproval, ?string $remarks, ?User $user = null): ManpowerApproval
    {
        return $this->changeStatus($manpowerApproval, 'cancelled', $remarks, $user);
    }

    protected function attributes(array $data, ?User $user, ?ManpowerApproval $existing = null): array
    {
        $allowed = ['recruitment_id', 'approval_reference', 'approval_date', 'status', 'remarks'];
        $attrs = array_intersect_key($data, array_flip($allowed));
        if ($existing === null && $user) {
            $attrs['created_by'] = $user->id;
        }
        $attrs['updated_by'] = $user?->id;
        return $attrs;
    }

    /** Uploads go to the private disk and are served only through an authorised download (Phase 01 section 13). */
    protected function storeFiles(ManpowerApproval $manpowerApproval, array $data): void
    {
        foreach (['documents_path'] as $field) {
            if (! empty($data[$field]) && $data[$field] instanceof UploadedFile) {
                $manpowerApproval->update([$field => $data[$field]->store('recruitments/' . $manpowerApproval->recruitment_id . '/manpower-approval', 'private')]);
            }
        }
    }

    public function downloadPath(ManpowerApproval $manpowerApproval, string $field): ?string
    {
        $path = $manpowerApproval->$field ?? null;
        return $path && Storage::disk('private')->exists($path) ? $path : null;
    }
}
