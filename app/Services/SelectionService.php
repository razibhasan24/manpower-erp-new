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
use App\Models\Account;
use App\Models\Voucher;
use App\Services\ChartOfAccountsService;
use Illuminate\Validation\ValidationException;

class SelectionService
{
    use WorkflowStepper;

    public const STATUSES = ['pending', 'approved', 'rejected', 'returned', 'cancelled'];
    /** Prompt section 11 lists: approved = Selected, returned = Waiting List (DB enum has no selected/waiting). */

    public function __construct(
        protected WorkflowService $workflow,
        protected VoucherService $vouchers,
        protected ChartOfAccountsService $chart,
    ) {}

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
                $this->postSelectionFinance($record, $recruitment, $data);
                $this->stepWorkflow($recruitment, 'selection', $data['status'], $data['remarks'] ?? null, $user);
            }
            return $record->fresh();
        });
    }

    /** Save the selection cost and post its receivable and any advance to accounting. */
    protected function postSelectionFinance(Selection $selection, Recruitment $recruitment, array $data): void
    {
        $costKeys = ['company_service_charge', 'agent_charge', 'medical_cost', 'training_cost', 'visa_cost', 'other_cost'];
        $allFinanceKeys = array_merge(['salary'], $costKeys);
        $total = round(array_sum(array_map(
            fn ($key) => (float) (array_key_exists($key, $data) ? ($data[$key] ?? 0) : ($recruitment->{$key} ?? 0)),
            $costKeys
        )), 2);
        $advance = round((float) (array_key_exists('advance_amount', $data) ? ($data['advance_amount'] ?? 0) : ($recruitment->advance_amount ?? 0)), 2);
        $partyType = $data['financial_responsible_type'] ?? $recruitment->financial_responsible_type;

        if (! in_array($partyType, ['agent', 'passport_holder'], true)) {
            throw ValidationException::withMessages(['financial_responsible_type' => 'Select Agent or Passport Holder for this account.']);
        }

        $party = $partyType === 'agent' ? $recruitment->agent : $recruitment->passportHolder;
        if (! $party) {
            throw ValidationException::withMessages(['financial_responsible_type' => 'The selected recruitment does not have this responsible party.']);
        }
        if ($advance > $total) {
            throw ValidationException::withMessages(['advance_amount' => 'Advance cannot be greater than the total amount.']);
        }

        $financiallyChanged = round((float) $recruitment->total_cost, 2) !== $total
            || round((float) $recruitment->advance_amount, 2) !== $advance
            || $recruitment->financial_responsible_type !== $partyType
            || (int) $recruitment->financial_responsible_id !== (int) $party->id;

        if ($financiallyChanged) {
            $this->reversePostedVoucher(Selection::class, $selection->id, 'Selection due updated');
            $this->reversePostedVoucher(Selection::class . ':advance', $selection->id, 'Selection advance updated');
        }

        $financeValues = [];
        foreach ($allFinanceKeys as $key) {
            $financeValues[$key] = array_key_exists($key, $data) ? $data[$key] : $recruitment->{$key};
        }
        $recruitment->update(array_merge($financeValues, [
                'financial_responsible_type' => $partyType,
                'financial_responsible_id' => $party->id,
                'total_cost' => $total,
                'advance_amount' => $advance,
                'remaining_due' => round($total - $advance, 2),
            ]));

        $receivableCode = $partyType === 'agent' ? '1210' : '1220';
        $receivable = $this->chart->requireByCode($receivableCode);
        $income = $this->chart->requireByCode('4100');
        $description = "Selection #{$selection->id} — recruitment {$recruitment->tracking_number}";

        if ($total > 0 && ! Voucher::where('reference_type', Selection::class)->where('reference_id', $selection->id)->where('status', 'posted')->exists()) {
            $this->vouchers->post([
                'voucher_type' => 'journal',
                'voucher_date' => now()->toDateString(),
                'branch_id' => $recruitment->branch_id,
                'reference_type' => Selection::class,
                'reference_id' => $selection->id,
                'narration' => "Selection amount due: {$description}",
                'lines' => [
                    ['account_id' => $receivable->id, 'debit' => $total, 'credit' => 0, 'sub_ledger_type' => $partyType, 'sub_ledger_id' => $party->id],
                    ['account_id' => $income->id, 'debit' => 0, 'credit' => $total],
                ],
            ]);
        }

        if ($advance > 0 && ! Voucher::where('reference_type', Selection::class . ':advance')->where('reference_id', $selection->id)->where('status', 'posted')->exists()) {
            $cashAccount = isset($data['advance_account_id'])
                ? Account::whereIn('code', ['1110', '1120'])->where('status', 'active')->find($data['advance_account_id'])
                : $this->chart->requireByCode('1110');
            if (! $cashAccount) {
                throw ValidationException::withMessages(['advance_account_id' => 'Select an active Cash or Bank account.']);
            }
            $this->vouchers->post([
                'voucher_type' => 'receipt',
                'voucher_date' => now()->toDateString(),
                'branch_id' => $recruitment->branch_id,
                'reference_type' => Selection::class . ':advance',
                'reference_id' => $selection->id,
                'narration' => "Selection advance received: {$description}",
                'lines' => [
                    ['account_id' => $cashAccount->id, 'debit' => $advance, 'credit' => 0],
                    ['account_id' => $receivable->id, 'debit' => 0, 'credit' => $advance, 'sub_ledger_type' => $partyType, 'sub_ledger_id' => $party->id],
                ],
            ]);
        }
    }

    protected function reversePostedVoucher(string $referenceType, int $referenceId, string $reason): void
    {
        $voucher = Voucher::where('reference_type', $referenceType)
            ->where('reference_id', $referenceId)
            ->where('status', 'posted')
            ->first();

        if ($voucher) {
            $this->vouchers->reverse($voucher, $reason);
        }
    }

    public function update(Selection $selection, array $data, ?User $user = null): Selection
    {
        return DB::transaction(function () use ($selection, $data, $user) {
            $selection->update($this->attributes($data, $user, $selection));
            $this->storeFiles($selection, $data);
            $financeKeys = ['salary', 'financial_responsible_type', 'company_service_charge', 'agent_charge', 'medical_cost', 'training_cost', 'visa_cost', 'other_cost', 'advance_amount'];
            if (array_intersect($financeKeys, array_keys($data))) {
                $recruitment = $selection->recruitment;
                if ($recruitment) {
                    $this->postSelectionFinance($selection, $recruitment, $data);
                }
            }
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
            $recruitment = $selection->recruitment;
            $this->postSelectionFinance($selection, $recruitment, $data);
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
