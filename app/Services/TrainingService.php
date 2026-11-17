<?php

namespace App\Services;

use App\Models\Training;
use App\Models\Recruitment;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Services\Concerns\WorkflowStepper;

class TrainingService
{
    use WorkflowStepper;

    public const STATUSES = ['pending', 'passed', 'failed', 'cancelled'];

    public function __construct(protected WorkflowService $workflow) {}

    /** Training results are not workflow statuses: passed = approved, failed = rejected. */
    protected function trainingWorkflowStatus(string $result): string
    {
        return ['passed' => 'approved', 'failed' => 'rejected'][$result] ?? $result;
    }

    /** Scoped list query: isolation is enforced here, never in the view (Phase 01 section 8.3). */
    public function scopedQuery(User $user)
    {
        $query = Training::query()->with(['recruitment.passportHolder', 'recruitment.agent'])->latest();
        if (method_exists(Training::class, 'scopeVisibleTo')) {
            $query->visibleTo($user);
        }
        return $query;
    }

    public function create(array $data, ?User $user = null): Training
    {
        return DB::transaction(function () use ($data, $user) {
            $record = Training::create($this->attributes($data, $user));
            $this->storeFiles($record, $data);
            $recruitment = $record->recruitment;
            if ($recruitment) {
                $this->stepWorkflow($recruitment, 'training', $this->trainingWorkflowStatus($data['status'] ?? $training->result), $data['remarks'] ?? null, $user);
            }
            return $record->fresh();
        });
    }

    public function update(Training $training, array $data, ?User $user = null): Training
    {
        return DB::transaction(function () use ($training, $data, $user) {
            $training->update($this->attributes($data, $user, $training));
            $this->storeFiles($training, $data);
            if (isset($data['status']) || isset($data['result'])) {
                $recruitment = $training->recruitment;
                if ($recruitment) {
                    $this->stepWorkflow($recruitment, 'training', $this->trainingWorkflowStatus($data['status'] ?? $training->result), $data['remarks'] ?? null, $user);
                }
            }
            return $training->fresh();
        });
    }

    /** Status-only change: still goes through WorkflowService so history is append-only. */
    public function changeStatus(Training $training, string $status, ?string $remarks, ?User $user = null): Training
    {
        return DB::transaction(function () use ($training, $status, $remarks, $user) {
            $training->update(['result' => $status, 'remarks' => $remarks ?? $training->remarks]);
            $recruitment = $training->recruitment;
            if ($recruitment) {
                $this->stepWorkflow($recruitment, 'training', $this->trainingWorkflowStatus($status), $remarks, $user);
            }
            return $training->fresh();
        });
    }

    /** Cancel/archive only — recruitment records are never hard-deleted (Phase 01 section 7). */
    public function cancel(Training $training, ?string $remarks, ?User $user = null): Training
    {
        return $this->changeStatus($training, 'cancelled', $remarks, $user);
    }

    protected function attributes(array $data, ?User $user, ?Training $existing = null): array
    {
        $allowed = ['recruitment_id', 'training_centre_id', 'course_id', 'start_date', 'end_date', 'attendance_percentage', 'result', 'remarks'];
        $attrs = array_intersect_key($data, array_flip($allowed));
        if ($existing === null && $user) {
            $attrs['created_by'] = $user->id;
        }
        $attrs['updated_by'] = $user?->id;
        return $attrs;
    }

    /** Uploads go to the private disk and are served only through an authorised download (Phase 01 section 13). */
    protected function storeFiles(Training $training, array $data): void
    {
        foreach (['certificate_path'] as $field) {
            if (! empty($data[$field]) && $data[$field] instanceof UploadedFile) {
                $training->update([$field => $data[$field]->store('recruitments/' . $training->recruitment_id . '/training', 'private')]);
            }
        }
    }

    public function downloadPath(Training $training, string $field): ?string
    {
        $path = $training->$field ?? null;
        return $path && Storage::disk('private')->exists($path) ? $path : null;
    }
}
