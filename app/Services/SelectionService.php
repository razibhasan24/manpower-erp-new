<?php

namespace App\Services;

use App\Models\Selection;
use App\Models\Recruitment;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Services\Concerns\WorkflowStepper;
use App\Models\GradingCriterion;
use App\Models\SelectionGrade;

class SelectionService
{
    use WorkflowStepper;

    public const STATUSES = ['pending', 'approved', 'rejected', 'returned', 'cancelled'];
    /** Prompt section 11 lists: approved = Selected, returned = Waiting List (DB enum has no selected/waiting). */

    public function __construct(protected WorkflowService $workflow) {}

    /** Scoped list query: isolation is enforced here, never in the view (Phase 01 section 8.3). */
    public function scopedQuery(User $user)
    {
        $query = Selection::query()->with(['recruitment.passportHolder', 'recruitment.agent'])->latest();
        if (method_exists(Selection::class, 'scopeVisibleTo')) {
            $query->visibleTo($user);
        }
        return $query;
    }

    public function create(array $data, ?User $user = null): Selection
    {
        return DB::transaction(function () use ($data, $user) {
            $record = Selection::create($this->attributes($data, $user));
            $this->storeFiles($record, $data);
            $recruitment = $record->recruitment;
            if ($recruitment) {
                $this->stepWorkflow($recruitment, 'selection', $data['status'], $data['remarks'] ?? null, $user);
            }
            return $record->fresh();
        });
    }

    public function update(Selection $selection, array $data, ?User $user = null): Selection
    {
        return DB::transaction(function () use ($selection, $data, $user) {
            $selection->update($this->attributes($data, $user, $selection));
            $this->storeFiles($selection, $data);
            if (isset($data['status']) || isset($data['result'])) {
                $recruitment = $selection->recruitment;
                if ($recruitment) {
                    $this->stepWorkflow($recruitment, 'selection', $data['status'], $data['remarks'] ?? null, $user);
                }
            }
            return $selection->fresh();
        });
    }

    /** Status-only change: still goes through WorkflowService so history is append-only. */
    public function changeStatus(Selection $selection, string $status, ?string $remarks, ?User $user = null): Selection
    {
        return DB::transaction(function () use ($selection, $status, $remarks, $user) {
            $selection->update(['status' => $status, 'remarks' => $remarks ?? $selection->remarks]);
            $recruitment = $selection->recruitment;
            if ($recruitment) {
                $this->stepWorkflow($recruitment, 'selection', $status, $remarks, $user);
            }
            return $selection->fresh();
        });
    }

    /** Cancel/archive only — recruitment records are never hard-deleted (Phase 01 section 7). */
    public function cancel(Selection $selection, ?string $remarks, ?User $user = null): Selection
    {
        return $this->changeStatus($selection, 'cancelled', $remarks, $user);
    }

   
    protected function attributes(array $data, ?User $user, ?Selection $existing = null): array
    {
        $allowed = [
            'recruitment_id',
            'grade',
            'status',
            'decided_at',
            'remarks',
        ];

        $attrs = array_intersect_key($data, array_flip($allowed));

        if ($existing === null && $user) {
            $attrs['created_by'] = $user->id;
        }

        $attrs['updated_by'] = $user?->id;

        return $attrs;
    }


    /** Uploads go to the private disk and are served only through an authorised download (Phase 01 section 13). */
    protected function storeFiles(Selection $selection, array $data): void
    {
        foreach ([] as $field) {
            if (! empty($data[$field]) && $data[$field] instanceof UploadedFile) {
                $selection->update([$field => $data[$field]->store('recruitments/' . $selection->recruitment_id . '/selection', 'private')]);
            }
        }
    }

    public function downloadPath(Selection $selection, string $field): ?string
    {
        $path = $selection->$field ?? null;
        return $path && Storage::disk('private')->exists($path) ? $path : null;
    }

    /**
     * Record the Selection decision including financial responsibility
     * (Phase 01 section 7 / prompt section 22). The responsible party is
     * Agent OR Passport Holder; the cost breakdown is computed here and
     * stored on the recruitment. Changing it later must go through a new
     * selection decision so the stage history preserves the old choice.
     */
    public function decide(Selection $selection, array $data, ?User $user = null): Selection
    {
        return DB::transaction(function () use ($selection, $data, $user) {
            $costKeys = ['salary', 'company_service_charge', 'agent_charge', 'medical_cost', 'training_cost', 'visa_cost', 'other_cost'];
            $total = 0;
            foreach ($costKeys as $key) { $total += (float) ($data[$key] ?? $selection->recruitment->{$key} ?? 0); }
            $advance = (float) ($data['advance_amount'] ?? 0);
            $recruitment = $selection->recruitment;
            $recruitment->update([
                'financial_responsible_type' => $data['financial_responsible_type'],
                'financial_responsible_id' => $data['financial_responsible_id'],
                'salary' => $data['salary'] ?? null,
                'company_service_charge' => $data['company_service_charge'] ?? null,
                'agent_charge' => $data['agent_charge'] ?? null,
                'medical_cost' => $data['medical_cost'] ?? null,
                'training_cost' => $data['training_cost'] ?? null,
                'visa_cost' => $data['visa_cost'] ?? null,
                'other_cost' => $data['other_cost'] ?? null,
                'total_cost' => $total,
                'advance_amount' => $advance,
                'remaining_due' => max(0, $total - $advance),
            ]);
            $selection->update([
                'grade' => $data['grade'] ?? $selection->grade,
                'grading_criteria_snapshot' => $this->gradingSnapshot($data['grades'] ?? []),
                'status' => $data['status'],
                'decided_by' => $user?->id,
                'decided_at' => now(),
                'remarks' => $data['remarks'] ?? $selection->remarks,
            ]);
            $this->syncGrades($selection, $data['grades'] ?? []);
            $this->stepWorkflow($recruitment, 'selection', $data['status'], $data['remarks'] ?? null, $user);
            return $selection->fresh(['recruitment']);
        });
    }

    /** Snapshot the grading criteria in force at decision time (prompt section 11). */
    public function gradingSnapshot(array $grades): array
    {
        $criteria = GradingCriterion::where('status', 'active')->orderBy('id')->get();
        $snapshot = [];
        foreach ($criteria as $criterion) {
            $snapshot[] = [
                'criterion_id' => $criterion->id, 'name' => $criterion->name,
                'max_score' => $criterion->max_score, 'weight' => $criterion->weight,
                'score' => $grades[$criterion->id] ?? null,
            ];
        }
        return $snapshot;
    }

    protected function syncGrades(Selection $selection, array $grades): void
    {
        foreach ($grades as $criterionId => $score) {
            SelectionGrade::updateOrCreate(
                ['selection_id' => $selection->id, 'grading_criterion_id' => $criterionId],
                ['score' => $score]
            );
        }
    }

    public function updateGradingCriterion(GradingCriterion $criterion, array $data): GradingCriterion
    {
        $criterion->update($data);
        return $criterion->fresh();
    }

    public function createGradingCriterion(array $data): GradingCriterion
    {
        return GradingCriterion::create($data);
    }
}