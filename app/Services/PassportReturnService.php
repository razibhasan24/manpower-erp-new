<?php

namespace App\Services;

use App\Models\PassportReturn;
use App\Models\Recruitment;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Services\Concerns\WorkflowStepper;

class PassportReturnService
{
    use WorkflowStepper;

    public const STATUSES = ['pending', 'returned', 'cancelled'];

    public function __construct(protected WorkflowService $workflow) {}

    /** Scoped list query: isolation is enforced here, never in the view (Phase 01 section 8.3). */
    public function scopedQuery(User $user)
    {
        $query = PassportReturn::query()->with(['recruitment.passportHolder', 'recruitment.agent'])->latest();
        if (method_exists(PassportReturn::class, 'scopeVisibleTo')) {
            $query->visibleTo($user);
        }
        return $query;
    }

    public function create(array $data, ?User $user = null): PassportReturn
    {
        return DB::transaction(function () use ($data, $user) {
            $record = PassportReturn::create($this->attributes($data, $user));
            $this->storeFiles($record, $data);
            $recruitment = $record->recruitment;
            if ($recruitment && ($data['status'] ?? null) === 'returned') {
                $this->stepWorkflow($recruitment, 'visa_processing', 'returned', $data['remarks'] ?? null, $user);
            }
            return $record->fresh();
        });
    }

    public function update(PassportReturn $passportReturn, array $data, ?User $user = null): PassportReturn
    {
        return DB::transaction(function () use ($passportReturn, $data, $user) {
            $passportReturn->update($this->attributes($data, $user, $passportReturn));
            $this->storeFiles($passportReturn, $data);
            if (isset($data['status']) || isset($data['result'])) {
                $recruitment = $passportReturn->recruitment;
                if ($recruitment && ($data['status'] ?? null) === 'returned') {
                    $this->stepWorkflow($recruitment, 'visa_processing', 'returned', $data['remarks'] ?? null, $user);
                }
            }
            return $passportReturn->fresh();
        });
    }

    /** Status-only change: still goes through WorkflowService so history is append-only. */
    public function changeStatus(PassportReturn $passportReturn, string $status, ?string $remarks, ?User $user = null): PassportReturn
    {
        return DB::transaction(function () use ($passportReturn, $status, $remarks, $user) {
            $passportReturn->update(['status' => $status, 'remarks' => $remarks ?? $passportReturn->remarks]);
            $recruitment = $passportReturn->recruitment;
            if ($recruitment && $status === 'returned') {
                $this->stepWorkflow($recruitment, 'visa_processing', 'returned', $remarks, $user);
            }
            return $passportReturn->fresh();
        });
    }

    /** Cancel/archive only — recruitment records are never hard-deleted (Phase 01 section 7). */
    public function cancel(PassportReturn $passportReturn, ?string $remarks, ?User $user = null): PassportReturn
    {
        return $this->changeStatus($passportReturn, 'cancelled', $remarks, $user);
    }

    protected function attributes(array $data, ?User $user, ?PassportReturn $existing = null): array
    {
        $allowed = ['status', 'return_date', 'return_reason', 'return_type', 'responsible_person', 'received_by', 'delivered_to', 'identity_verified_by', 'remarks'];
        $attrs = array_intersect_key($data, array_flip($allowed));
        if ($existing === null && $user) {
            $attrs['created_by'] = $user->id;
        }
        $attrs['updated_by'] = $user?->id;
        return $attrs;
    }

    /** Uploads go to the private disk and are served only through an authorised download (Phase 01 section 13). */
    protected function storeFiles(PassportReturn $passportReturn, array $data): void
    {
        foreach (['acknowledgement_path'] as $field) {
            if (! empty($data[$field]) && $data[$field] instanceof UploadedFile) {
                $passportReturn->update([$field => $data[$field]->store('recruitments/' . $passportReturn->recruitment_id . '/passport-return', 'private')]);
            }
        }
    }

    public function downloadPath(PassportReturn $passportReturn, string $field): ?string
    {
        $path = $passportReturn->$field ?? null;
        return $path && Storage::disk('private')->exists($path) ? $path : null;
    }
}
