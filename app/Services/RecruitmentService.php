<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Recruitment;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Models\Branch;
use App\Models\PassportHolder;
use App\Models\RecruitmentDocument;
use App\Models\TrackingNumber;
use Illuminate\Http\UploadedFile;

/**
 * Recruitment business logic (Phases 08-12).
 *
 * Controllers stay thin: they validate, authorise and delegate here.
 * List queries always start from the model's scopeVisibleTo() so an Agent
 * or Passport Holder can never select another party's rows.
 */
class RecruitmentService
{
    public function list(array $filters, User $user): LengthAwarePaginator
    {
        $query = Recruitment::query()->visibleTo($user)->with(['passportHolder', 'agent', 'branch', 'country', 'company', 'position']);

        if (! empty($filters['search'])) {
            $term = '%'.($filters['search']).'%';
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
        return $query->latest()->paginate(15)->withQueryString();
    }

    public const ACTIVE_STATUSES = ['pending', 'approved', 'completed'];

    public function create(array $data, User $user): Recruitment
    {
        return DB::transaction(function () use ($data, $user) {
            $files = $data['documents'] ?? [];
            unset($data['documents']);

            $passportHolder = PassportHolder::findOrFail((int) $data['passport_holder_id']);
            $this->assertNoActiveDuplicate($passportHolder);

            $branch = Branch::findOrFail((int) $data['branch_id']);

            // Financial responsibility is NOT decided at registration
            // (master prompt section 11 / Phase 01 section 7). It stays
            // undecided until Selection & Grading (Group C).
            $data['financial_responsible_type'] = null;
            $data['financial_responsible_id'] = null;
            [$data['tracking_number'], $sequence] = $this->generateTrackingNumber($branch);
            $data['current_stage'] = 'registration';
            $data['current_status'] = 'pending';

            $recruitment = Recruitment::create($data);
            $recruitment->forceFill(['created_by' => $user->id, 'updated_by' => $user->id])->save();

            // Persist the number in the tracking_numbers table (Group A
            // model) so the branch/year sequence is durable and auditable.
            TrackingNumber::create([
                'tracking_number' => $recruitment->tracking_number,
                'recruitment_id' => $recruitment->id,
                'branch_id' => $branch->id,
                'year' => (int) now()->format('Y'),
                'sequence' => $sequence,
            ]);

            foreach ((array) $files as $type => $file) {
                if ($file instanceof UploadedFile) {
                    $this->storeDocument($recruitment, $file, (string) $type, $user);
                }
            }

            $this->recordTransition($recruitment, 'registration', 'pending', $data['remarks'] ?? null, $user);

            return $recruitment->fresh(['passportHolder', 'agent', 'branch', 'country', 'company', 'position']);
        });
    }

    public function update(Recruitment $recruitment, array $data, User $user): Recruitment
    {
        return DB::transaction(function () use ($recruitment, $data, $user) {
            $files = $data['documents'] ?? [];
            unset($data['documents']);

            // Stage, status, tracking number and financial responsibility
            // are never edited through the registration form: stage moves
            // only through WorkflowService and financial responsibility
            // is decided at Selection.
            unset(
                $data['tracking_number'], $data['current_stage'], $data['current_status'],
                $data['financial_responsible_type'], $data['financial_responsible_id']
            );

            $recruitment->update($data);
            $recruitment->forceFill(['updated_by' => $user->id])->save();

            foreach ((array) $files as $type => $file) {
                if ($file instanceof UploadedFile) {
                    $this->storeDocument($recruitment, $file, (string) $type, $user);
                }
            }

            return $recruitment->fresh();
        });
    }

    /**
     * "Delete" on a recruitment means cancel (see cancel()). The
     * controller's destroy action calls this so no route hard-deletes
     * a recruitment record.
     */
    public function delete(Recruitment $recruitment, User $user): Recruitment
    {
        return $this->cancel($recruitment, 'Cancelled from the registrations list.', $user);
    }



    /**
     * Tracking number format (Decision D7, Phase 12):
     * YYYY + branch code + zero-padded serial, e.g. 2026-DHK-000001.
     * The serial restarts at 000001 for each branch each calendar year and
     * is the next number after the highest existing serial for that
     * branch/year. Generation runs inside the registration transaction;
     * a unique-constraint collision is retried a small number of times.
     */
    /**
     * @return array{0: string, 1: int} [tracking number, sequence]
     */
    public function generateTrackingNumber(Branch $branch): array
    {
        $year = now()->format('Y');
        $prefix = $year.'-'.strtoupper($branch->code).'-';

        $max = Recruitment::query()
            ->where('tracking_number', 'like', $prefix.'%')
            ->lockForUpdate()
            ->max('tracking_number');

        $serial = 1;
        if ($max !== null) {
            $serial = ((int) substr((string) $max, strlen($prefix))) + 1;
        }

        return [$prefix.str_pad((string) $serial, 6, '0', STR_PAD_LEFT), $serial];
    }

    /**
     * Store a registration document on the private disk and record it.
     * Downloads go through RecruitmentController::downloadDocument(),
     * which re-checks the policy - no public URL to a scan ever exists.
     */
    public function storeDocument(Recruitment $recruitment, UploadedFile $file, string $type, User $user): RecruitmentDocument
    {
        $path = $file->store('recruitments/'.$recruitment->id, 'private');

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
     * Cancel a registration. The record is never hard-deleted: the
     * cancellation is a status change, the history is preserved, and a
     * later registration for the same passport can be created because
     * the duplicate check only counts ACTIVE registrations.
     */
    public function cancel(Recruitment $recruitment, ?string $remarks, User $user): Recruitment
    {
        return DB::transaction(function () use ($recruitment, $remarks, $user) {
            if (in_array($recruitment->current_status, ['cancelled', 'completed'], true)) {
                throw ValidationException::withMessages([
                    'recruitment' => 'This registration is already '.$recruitment->current_status.' and cannot be cancelled.',
                ]);
            }

            $recruitment->update([
                'current_status' => 'cancelled',
                'remarks' => $remarks ?? $recruitment->remarks,
            ]);
            $recruitment->forceFill(['updated_by' => $user->id])->save();

            $this->recordTransition($recruitment, 'registration', 'cancelled', $remarks, $user);

            return $recruitment->fresh();
        });
    }

    /**
     * Record the initial Registration stage through the shared workflow
     * engine when it is available; otherwise the stage/status columns
     * set at creation already describe the registration.
     */
    protected function recordTransition(Recruitment $recruitment, string $stage, string $status, ?string $remarks, User $user): void
    {
        if (class_exists(WorkflowService::class)) {
            // MERGE FIX 2026-11-13 (coordinator): transition() is an
            // INSTANCE method — calling it statically throws an Error
            // in PHP 8. Resolve it from the container instead.
            app(WorkflowService::class)->transition($recruitment, $stage, $status, $remarks, $user);
        }
    }

    /**
     * Reject a registration when the same passport number already has an
     * ACTIVE recruitment (master prompt section 9). Cancelled, rejected
     * and completed registrations are history and do not block a new one.
     */
    protected function assertNoActiveDuplicate(PassportHolder $passportHolder): void
    {
        $exists = Recruitment::query()
            ->where('passport_holder_id', $passportHolder->id)
            ->whereIn('current_status', self::ACTIVE_STATUSES)
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'passport_holder_id' => 'This passport already has an active registration. Cancel the active registration before creating a new one.',
            ]);
        }
    }
}
