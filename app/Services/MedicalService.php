<?php

namespace App\Services;

use App\Models\Medical;
use App\Models\Recruitment;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Services\Concerns\WorkflowStepper;

class MedicalService
{
    use WorkflowStepper;

    public const STATUSES = ['pending', 'fit', 'unfit', 'expired', 'cancelled'];

    public function __construct(protected WorkflowService $workflow) {}

    /** Scoped list query: isolation is enforced here, never in the view (Phase 01 section 8.3). */
    public function scopedQuery(User $user)
    {
        $query = Medical::query()->with(['recruitment.passportHolder', 'recruitment.agent'])->when(request()->filled('service_type'), fn ($q) => $q->whereHas('recruitment', fn ($r) => $r->where('service_type', request('service_type'))))->latest();
        if (method_exists(Medical::class, 'scopeVisibleTo')) {
            $query->visibleTo($user);
        }
        return $query;
    }

    public function create(array $data, ?User $user = null): Medical
    {
        return DB::transaction(function () use ($data, $user) {
            if (Recruitment::whereKey($data['recruitment_id'])->where('service_type', 'hajj_umrah')->exists()) {
                throw \Illuminate\Validation\ValidationException::withMessages(['recruitment_id' => 'Medical is not part of the Hajj and Umrah workflow.']);
            }
            $record = Medical::create($this->attributes($data, $user));
            $this->storeFiles($record, $data);
            $recruitment = $record->recruitment;
            if ($recruitment) {
                $this->stepWorkflow($recruitment, 'medical', $data['result'], $data['remarks'] ?? null, $user);
            }
            return $record->fresh();
        });
    }

    public function update(Medical $medical, array $data, ?User $user = null): Medical
    {
        return DB::transaction(function () use ($medical, $data, $user) {
            $medical->update($this->attributes($data, $user, $medical));
            $this->storeFiles($medical, $data);
            if (isset($data['status']) || isset($data['result'])) {
                $recruitment = $medical->recruitment;
                if ($recruitment) {
                    $this->stepWorkflow($recruitment, 'medical', $data['result'], $data['remarks'] ?? null, $user);
                }
            }
            return $medical->fresh();
        });
    }

    /** Status-only change: still goes through WorkflowService so history is append-only. */
    public function changeStatus(Medical $medical, string $status, ?string $remarks, ?User $user = null): Medical
    {
        return DB::transaction(function () use ($medical, $status, $remarks, $user) {
            $medical->update(['result' => $status, 'remarks' => $remarks ?? $medical->remarks]);
            $recruitment = $medical->recruitment;
            if ($recruitment) {
                $this->stepWorkflow($recruitment, 'medical', $status, $remarks, $user);
            }
            return $medical->fresh();
        });
    }

    /** Cancel/archive only — recruitment records are never hard-deleted (Phase 01 section 7). */
    public function cancel(Medical $medical, ?string $remarks, ?User $user = null): Medical
    {
        return $this->changeStatus($medical, 'cancelled', $remarks, $user);
    }

    protected function attributes(array $data, ?User $user, ?Medical $existing = null): array
    {
        $allowed = ['recruitment_id', 'medical_centre_id', 'medical_date', 'result', 'expiry_date', 'remarks'];
        $attrs = array_intersect_key($data, array_flip($allowed));
        if ($existing === null && $user) {
            $attrs['created_by'] = $user->id;
        }
        $attrs['updated_by'] = $user?->id;
        return $attrs;
    }

    /** Uploads go to the private disk and are served only through an authorised download (Phase 01 section 13). */
    protected function storeFiles(Medical $medical, array $data): void
    {
        foreach (['report_path'] as $field) {
            if (! empty($data[$field]) && $data[$field] instanceof UploadedFile) {
                $medical->update([$field => $data[$field]->store('recruitments/' . $medical->recruitment_id . '/medical', 'private')]);
            }
        }
    }

    public function downloadPath(Medical $medical, string $field): ?string
    {
        $path = $medical->$field ?? null;
        return $path && Storage::disk('private')->exists($path) ? $path : null;
    }
}
