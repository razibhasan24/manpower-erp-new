<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Recruitment;
use App\Models\User;
use App\Models\Branch;
use App\Models\PassportHolder;
use App\Models\RecruitmentDocument;
use App\Models\TrackingNumber;
use App\Models\ServicePackage;
use App\Models\Voucher;
use App\Services\ChartOfAccountsService;
use App\Services\VoucherService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RecruitmentService
{
    use \App\Services\Concerns\WorkflowStepper;

    public function __construct(protected WorkflowService $workflow) {}

    public const ACTIVE_STATUSES = [
        'pending',
        'approved',
    ];

    /**
     * List visible recruitments.
     */
    public function list(array $filters, User $user): LengthAwarePaginator
    {
        $query = Recruitment::query()
            ->visibleTo($user)
            ->with([
                'passportHolder',
                'agent',
                'servicePackage',
                'branch',
                'country',
                'company',
                'position',
            ]);

        if (! empty($filters['search'])) {
            $term = '%' . $filters['search'] . '%';

            $query->where(function ($q) use ($term) {
                $q->where('tracking_number', 'like', $term);
            });
        }

        if (! empty($filters['current_status'])) {
            $query->where('current_status', $filters['current_status']);
        }

        if (! empty($filters['current_stage'])) {
            $query->where('current_stage', $filters['current_stage']);
        }

        if (! empty($filters['service_type'])) {
            $query->where('service_type', $filters['service_type']);
        }

        if (! empty($filters['active'])) {
            $query->whereIn('current_status', self::ACTIVE_STATUSES);
        }

        if (! empty($filters['branch_id'])) {
            $query->where('branch_id', $filters['branch_id']);
        }

        if (! empty($filters['country_id'])) {
            $query->where('country_id', $filters['country_id']);
        }

        if (! empty($filters['agent_id'])) {
            $query->where('agent_id', $filters['agent_id']);
        }

        return $query->latest()
            ->paginate(15)
            ->withQueryString();
    }

    /**
     * Create a new recruitment.
     */
    public function create(array $data, User $user): Recruitment
    {
        return DB::transaction(function () use ($data, $user) {

            $files = $data['documents'] ?? [];

            unset($data['documents']);
            $serviceType = $data['service_type'] ?? 'overseas';
            $package = null;
            if ($serviceType !== 'overseas') {
                $package = ServicePackage::query()->whereKey($data['service_package_id'] ?? null)
                    ->where('type', $serviceType)->where('is_active', true)->firstOrFail();
                if ($package->currency !== 'BDT') {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'service_package_id' => 'Registration due posting currently requires a BDT package price.',
                    ]);
                }
                $data['quoted_amount'] = $package->price;
                $data['quoted_currency'] = $package->currency;
                $data['total_cost'] = $package->price;
                $data['remaining_due'] = $package->price;
            }

            $holderFields = ['full_name','father_name','mother_name','dob','gender','phone','email','present_address','nid','passport_number','passport_issue_date','passport_expiry_date','agent_id','branch_id','country_id','company_id','position_id','referer','religion','marital_status','height','weight','village_road','post_office','police_station','district'];
            $holderData = array_intersect_key($data, array_flip($holderFields));
            $holderData['status'] = 'active';
            $holderData['created_by'] = $user->id;
            $holderData['updated_by'] = $user->id;
            $passportHolder = PassportHolder::create($holderData);
            unset($data['passport_holder_id']);
            $data['passport_holder_id'] = $passportHolder->id;
            foreach (['full_name','father_name','mother_name','dob','gender','phone','email','present_address','nid','passport_number','passport_issue_date','passport_expiry_date','referer','religion','marital_status','height','weight','village_road','post_office','police_station','district'] as $field) unset($data[$field]);

            $this->assertNoActiveDuplicate($passportHolder);

            $branch = Branch::query()
                ->whereKey((int) $data['branch_id'])
                ->firstOrFail();

            // Package registrations assign the package due immediately to
            // the introducing agent, or to the passport holder if there is none.
            if ($serviceType === 'overseas') {
                $data['financial_responsible_type'] = null;
                $data['financial_responsible_id'] = null;
            } else {
                $responsible = $passportHolder->agent_id ? 'agent' : 'passport_holder';
                $data['financial_responsible_type'] = $responsible;
                $data['financial_responsible_id'] = $responsible === 'agent' ? $passportHolder->agent_id : $passportHolder->id;
            }

            // Generate tracking number.
            [$trackingNumber, $sequence] =
                $this->generateTrackingNumber($branch);

            $data['tracking_number'] = $trackingNumber;

            // Initial workflow status.
            $data['current_stage'] = 'registration';
            $data['current_status'] = 'pending';

            // Create recruitment.
            $recruitment = Recruitment::create($data);

            $recruitment->forceFill([
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ])->save();

            if ($package && (float) $package->price > 0) {
                $chart = app(ChartOfAccountsService::class);
                $receivable = $chart->requireByCode($recruitment->financial_responsible_type === 'agent' ? '1210' : '1220');
                $income = $chart->requireByCode('4100');
                app(VoucherService::class)->post([
                    'voucher_type' => 'journal',
                    'voucher_date' => now()->toDateString(),
                    'branch_id' => $recruitment->branch_id,
                    'reference_type' => Recruitment::class,
                    'reference_id' => $recruitment->id,
                    'narration' => "{$package->name} registration due: {$recruitment->tracking_number}",
                    'lines' => [
                        ['account_id' => $receivable->id, 'debit' => $package->price, 'credit' => 0, 'sub_ledger_type' => $recruitment->financial_responsible_type, 'sub_ledger_id' => $recruitment->financial_responsible_id],
                        ['account_id' => $income->id, 'debit' => 0, 'credit' => $package->price],
                    ],
                ]);
            }

            // Save tracking number.
            TrackingNumber::create([
                'tracking_number' => $trackingNumber,
                'recruitment_id' => $recruitment->id,
                'branch_id' => $branch->id,
                'year' => (int) now()->format('Y'),
                'sequence' => $sequence,
            ]);

            // Upload registration documents.
            foreach ((array) $files as $type => $file) {

                if ($file instanceof UploadedFile) {
                    $this->storeDocument(
                        $recruitment,
                        $file,
                        (string) $type,
                        $user
                    );
                }
            }

            // Record initial registration history and audit log.
            app(WorkflowService::class)->recordInitialRegistration(
                $recruitment,
                $data['remarks'] ?? null,
                $user
            );

            return $recruitment->fresh([
                'passportHolder',
                'agent',
                'branch',
                'country',
                'company',
                'position',
            ]);
        });
    }

    /**
     * Update recruitment information.
     *
     * Workflow fields cannot be changed through this method.
     */
    public function update(
        Recruitment $recruitment,
        array $data,
        User $user
    ): Recruitment {
        return DB::transaction(function () use (
            $recruitment,
            $data,
            $user
        ) {

            $files = $data['documents'] ?? [];

            unset($data['documents']);

            $holderFields = ['full_name','father_name','mother_name','dob','gender','phone','email','present_address','nid','passport_number','passport_issue_date','passport_expiry_date','agent_id','branch_id','country_id','company_id','position_id','referer','religion','marital_status','height','weight','village_road','post_office','police_station','district'];
            $holderData = array_intersect_key($data, array_flip($holderFields));
            $recruitment->passportHolder->update($holderData);
            foreach ($holderFields as $field) unset($data[$field]);

            // Protect workflow-managed and financial fields.
            unset(
                $data['tracking_number'],
                $data['current_stage'],
                $data['current_status'],
                $data['financial_responsible_type'],
                $data['financial_responsible_id']
            );

            $recruitment->update($data);

            $recruitment->forceFill([
                'updated_by' => $user->id,
            ])->save();

            // Upload additional documents.
            foreach ((array) $files as $type => $file) {

                if ($file instanceof UploadedFile) {
                    $this->storeDocument(
                        $recruitment,
                        $file,
                        (string) $type,
                        $user
                    );
                }
            }

            return $recruitment->fresh();
        });
    }

    public function completeStudentStage(Recruitment $recruitment, array $data, User $user): Recruitment
    {
        return DB::transaction(function () use ($recruitment, $data, $user) {
            $stage = $data['stage'];
            $status = match ($stage) {
                'university_management' => 'completed',
                'offer_letter' => 'issued',
                'admission' => 'admitted',
            };

            if ($stage === 'university_management') {
                $recruitment->update(['university_id' => $data['university_id']]);
            }

            if (in_array($stage, ['offer_letter', 'admission'], true) && ($data['document'] ?? null) instanceof UploadedFile) {
                $this->storeDocument($recruitment, $data['document'], $stage, $user, $stage);
            }

            $this->stepWorkflow($recruitment, $stage, $status, $data['remarks'] ?? null, $user);

            return $recruitment->fresh(['passportHolder', 'agent', 'servicePackage', 'university', 'stageHistories', 'documents']);
        });
    }

    /**
     * Delete means cancel.
     * Recruitment records are never hard-deleted here.
     */
    public function delete(
        Recruitment $recruitment,
        User $user
    ): Recruitment {
        return $this->cancel(
            $recruitment,
            'Cancelled from the registrations list.',
            $user
        );
    }

    /**
     * Generate tracking number.
     *
     * Format: YYYY-BRANCH-000001
     */
    public function generateTrackingNumber(Branch $branch): array
    {
        $year = now()->format('Y');

        $prefix = $year . '-' . strtoupper($branch->code) . '-';

        $max = Recruitment::query()
            ->where('tracking_number', 'like', $prefix . '%')
            ->lockForUpdate()
            ->max('tracking_number');

        $serial = 1;

        if ($max !== null) {
            $serial = (
                (int) substr(
                    (string) $max,
                    strlen($prefix)
                )
            ) + 1;
        }

        $trackingNumber = $prefix
            . str_pad(
                (string) $serial,
                6,
                '0',
                STR_PAD_LEFT
            );

        return [$trackingNumber, $serial];
    }

    /**
     * Store document privately.
     */
    public function storeDocument(
        Recruitment $recruitment,
        UploadedFile $file,
        string $type,
        User $user,
        ?string $stage = null
    ): RecruitmentDocument {

        $path = $file->store(
            'recruitments/' . $recruitment->id,
            'private'
        );

        return RecruitmentDocument::create([
            'recruitment_id' => $recruitment->id,
            'type' => $type,
            'stage' => $stage ?? ($recruitment->current_stage ?? 'registration'),
            'file_path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'uploaded_by' => $user->id,
        ]);
    }

    /**
     * Cancel recruitment through WorkflowService.
     *
     * WorkflowService handles:
     * - Authorization
     * - Database transaction
     * - Recruitment status
     * - Stage history
     * - Audit log
     */
    public function cancel(
        Recruitment $recruitment,
        ?string $remarks,
        User $user
    ): Recruitment {

        app(WorkflowService::class)->cancel(
            $recruitment,
            $remarks,
            $user
        );

        return $recruitment->fresh([
            'passportHolder',
            'agent',
            'branch',
            'country',
            'company',
            'position',
        ]);
    }

    /**
     * Prevent duplicate active registrations
     * for the same passport holder.
     */
    protected function assertNoActiveDuplicate(
        PassportHolder $passportHolder
    ): void {

        $exists = Recruitment::query()
            ->where(
                'passport_holder_id',
                $passportHolder->id
            )
            ->whereIn(
                'current_status',
                self::ACTIVE_STATUSES
            )
            ->exists();

        if ($exists) {

            throw ValidationException::withMessages([
                'passport_holder_id' =>
                    'This passport already has an active registration. '
                    . 'Cancel the active registration before creating a new one.',
            ]);
        }
    }
    
}
