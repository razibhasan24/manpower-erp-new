<?php

namespace App\Services;

use App\Models\PoliceClearance;
use App\Models\Recruitment;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Services\Concerns\WorkflowStepper;

class PoliceClearanceService
{
    use WorkflowStepper;

    public const STATUSES = ['pending', 'cleared', 'uncleared', 'returned', 'cancelled'];

    public function __construct(protected WorkflowService $workflow) {}

    /** Scoped list query: isolation is enforced here, never in the view (Phase 01 section 8.3). */
    public function scopedQuery(User $user)
    {
        $query = PoliceClearance::query()->with(['recruitment.passportHolder', 'recruitment.agent'])->when(request()->filled('service_type'), fn ($q) => $q->whereHas('recruitment', fn ($r) => $r->where('service_type', request('service_type'))))->latest();
        if (method_exists(PoliceClearance::class, 'scopeVisibleTo')) {
            $query->visibleTo($user);
        }
        return $query;
    }

    public function create(array $data, ?User $user = null): PoliceClearance
    {
        return DB::transaction(function () use ($data, $user) {
            if (Recruitment::whereKey($data['recruitment_id'])->where('service_type', 'hajj_umrah')->exists()) {
                throw \Illuminate\Validation\ValidationException::withMessages(['recruitment_id' => 'Police clearance is not part of the Hajj and Umrah workflow.']);
            }
            $record = PoliceClearance::create($this->attributes($data, $user));
            $this->storeFiles($record, $data);
            $recruitment = $record->recruitment;
            if ($recruitment) {
                $this->stepWorkflow($recruitment, 'police_clearance', $data['status'], $data['remarks'] ?? null, $user);
            }
            return $record->fresh();
        });
    }

    public function update(PoliceClearance $policeClearance, array $data, ?User $user = null): PoliceClearance
    {
        return DB::transaction(function () use ($policeClearance, $data, $user) {
            $policeClearance->update($this->attributes($data, $user, $policeClearance));
            $this->storeFiles($policeClearance, $data);
            if (isset($data['status']) || isset($data['result'])) {
                $recruitment = $policeClearance->recruitment;
                if ($recruitment) {
                    $this->stepWorkflow($recruitment, 'police_clearance', $data['status'], $data['remarks'] ?? null, $user);
                }
            }
            return $policeClearance->fresh();
        });
    }

    /** Status-only change: still goes through WorkflowService so history is append-only. */
    public function changeStatus(PoliceClearance $policeClearance, string $status, ?string $remarks, ?User $user = null): PoliceClearance
    {
        return DB::transaction(function () use ($policeClearance, $status, $remarks, $user) {
            $policeClearance->update(['status' => $status, 'remarks' => $remarks ?? $policeClearance->remarks]);
            $recruitment = $policeClearance->recruitment;
            if ($recruitment) {
                $this->stepWorkflow($recruitment, 'police_clearance', $status, $remarks, $user);
            }
            return $policeClearance->fresh();
        });
    }

    /** Cancel/archive only — recruitment records are never hard-deleted (Phase 01 section 7). */
    public function cancel(PoliceClearance $policeClearance, ?string $remarks, ?User $user = null): PoliceClearance
    {
        return $this->changeStatus($policeClearance, 'cancelled', $remarks, $user);
    }

    protected function attributes(array $data, ?User $user, ?PoliceClearance $existing = null): array
    {
        $allowed = ['recruitment_id', 'status', 'issue_date', 'expiry_date', 'remarks'];
        $attrs = array_intersect_key($data, array_flip($allowed));
        if ($existing === null && $user) {
            $attrs['created_by'] = $user->id;
        }
        $attrs['updated_by'] = $user?->id;
        return $attrs;
    }

    /** Uploads go to the private disk and are served only through an authorised download (Phase 01 section 13). */
    protected function storeFiles(PoliceClearance $policeClearance, array $data): void
    {
        foreach (['certificate_path'] as $field) {
            if (! empty($data[$field]) && $data[$field] instanceof UploadedFile) {
                $policeClearance->update([$field => $data[$field]->store('recruitments/' . $policeClearance->recruitment_id . '/police-clearance', 'private')]);
            }
        }
    }

    public function downloadPath(PoliceClearance $policeClearance, string $field): ?string
    {
        $path = $policeClearance->$field ?? null;
        return $path && Storage::disk('private')->exists($path) ? $path : null;
    }
}
