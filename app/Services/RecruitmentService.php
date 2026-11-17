<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Recruitment;
use App\Models\User;
use App\Models\Branch;
use App\Models\PassportHolder;
use App\Models\RecruitmentDocument;
use App\Models\TrackingNumber;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RecruitmentService
{
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

            $passportHolder = PassportHolder::query()
                ->whereKey((int) $data['passport_holder_id'])
                ->firstOrFail();

            $this->assertNoActiveDuplicate($passportHolder);

            $branch = Branch::query()
                ->whereKey((int) $data['branch_id'])
                ->firstOrFail();

            // Financial responsibility is decided during Selection.
            $data['financial_responsible_type'] = null;
            $data['financial_responsible_id'] = null;

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
        User $user
    ): RecruitmentDocument {

        $path = $file->store(
            'recruitments/' . $recruitment->id,
            'private'
        );

        return RecruitmentDocument::create([
            'recruitment_id' => $recruitment->id,
            'type' => $type,
            'stage' => $recruitment->current_stage ?? 'registration',
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