<?php

namespace App\Services;

use App\Models\VisaReturn;
use App\Models\Recruitment;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Services\Concerns\WorkflowStepper;

class VisaReturnService
{
    use WorkflowStepper;

    public const STATUSES = ['pending', 'returned', 'cancelled'];

    public function __construct(protected WorkflowService $workflow) {}

    /** Scoped list query: isolation is enforced here, never in the view (Phase 01 section 8.3). */
    public function scopedQuery(User $user)
    {
        $query = VisaReturn::query()->with(['recruitment.passportHolder', 'recruitment.agent'])->latest();
        if (method_exists(VisaReturn::class, 'scopeVisibleTo')) {
            $query->visibleTo($user);
        }
        return $query;
    }

    public function create(array $data, ?User $user = null): VisaReturn
    {
        return DB::transaction(function () use ($data, $user) {
            $record = VisaReturn::create($this->attributes($data, $user));
            $this->storeFiles($record, $data);
            $recruitment = $record->recruitment;
            if ($recruitment && ($data['status'] ?? null) === 'returned') {
                $this->stepWorkflow($recruitment, 'visa_processing', 'returned', $data['remarks'] ?? null, $user);
            }
            return $record->fresh();
        });
    }

    public function update(VisaReturn $visaReturn, array $data, ?User $user = null): VisaReturn
    {
        return DB::transaction(function () use ($visaReturn, $data, $user) {
            $visaReturn->update($this->attributes($data, $user, $visaReturn));
            $this->storeFiles($visaReturn, $data);
            if (isset($data['status']) || isset($data['result'])) {
                $recruitment = $visaReturn->recruitment;
                if ($recruitment && ($data['status'] ?? null) === 'returned') {
                    $this->stepWorkflow($recruitment, 'visa_processing', 'returned', $data['remarks'] ?? null, $user);
                }
            }
            return $visaReturn->fresh();
        });
    }

    /** Status-only change: still goes through WorkflowService so history is append-only. */
    public function changeStatus(VisaReturn $visaReturn, string $status, ?string $remarks, ?User $user = null): VisaReturn
    {
        return DB::transaction(function () use ($visaReturn, $status, $remarks, $user) {
            $visaReturn->update(['status' => $status, 'remarks' => $remarks ?? $visaReturn->remarks]);
            $recruitment = $visaReturn->recruitment;
            if ($recruitment && $status === 'returned') {
                $this->stepWorkflow($recruitment, 'visa_processing', 'returned', $remarks, $user);
            }
            return $visaReturn->fresh();
        });
    }

    /** Cancel/archive only — recruitment records are never hard-deleted (Phase 01 section 7). */
    public function cancel(VisaReturn $visaReturn, ?string $remarks, ?User $user = null): VisaReturn
    {
        return $this->changeStatus($visaReturn, 'cancelled', $remarks, $user);
    }

    protected function attributes(array $data, ?User $user, ?VisaReturn $existing = null): array
    {
        $allowed = ['visa_id', 'return_date', 'return_reason', 'return_type', 'responsible_person', 'received_by', 'remarks'];
        $attrs = array_intersect_key($data, array_flip($allowed));
        if ($existing === null && $user) {
            $attrs['created_by'] = $user->id;
        }
        $attrs['updated_by'] = $user?->id;
        return $attrs;
    }

    /** Uploads go to the private disk and are served only through an authorised download (Phase 01 section 13). */
    protected function storeFiles(VisaReturn $visaReturn, array $data): void
    {
        foreach (['document_path'] as $field) {
            if (! empty($data[$field]) && $data[$field] instanceof UploadedFile) {
                $visaReturn->update([$field => $data[$field]->store('recruitments/' . $visaReturn->recruitment_id . '/visa-return', 'private')]);
            }
        }
    }

    public function downloadPath(VisaReturn $visaReturn, string $field): ?string
    {
        $path = $visaReturn->$field ?? null;
        return $path && Storage::disk('private')->exists($path) ? $path : null;
    }
}
