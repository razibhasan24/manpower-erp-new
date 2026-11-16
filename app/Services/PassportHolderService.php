<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\PassportHolder;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Models\PassportHolderDocument;
use Illuminate\Http\UploadedFile;

/**
 * Passport Holder business logic (Phases 08-12).
 *
 * Controllers stay thin: they validate, authorise and delegate here.
 * List queries always start from the model's scopeVisibleTo() so an Agent
 * or Passport Holder can never select another party's rows.
 */
class PassportHolderService
{
    public function list(array $filters, User $user): LengthAwarePaginator
    {
        $query = PassportHolder::query()->visibleTo($user)->with(['agent', 'branch', 'country', 'company', 'position']);

        if (! empty($filters['search'])) {
            $term = '%'.($filters['search']).'%';
            $query->where(function ($q) use ($term) {
                $q->where('full_name', 'like', $term);
                $q->orWhere('passport_number', 'like', $term);
                $q->orWhere('nid', 'like', $term);
                $q->orWhere('phone', 'like', $term);
            });
        }
        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
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

    public function create(array $data, User $user): PassportHolder
    {
        return DB::transaction(function () use ($data, $user) {
            $this->assertPassportNumberAvailable($data['passport_number'] ?? null, null);

            $photo = $data['photo'] ?? null;
            unset($data['photo']);

            $passportHolder = PassportHolder::create($data);
            $passportHolder->forceFill(['created_by' => $user->id, 'updated_by' => $user->id])->save();

            if ($photo instanceof UploadedFile) {
                $passportHolder->update([
                    'photo_path' => $photo->store('passport-holders/'.$passportHolder->id, 'private'),
                ]);
            }

            return $passportHolder->fresh();
        });
    }

    public function update(PassportHolder $passportHolder, array $data, User $user): PassportHolder
    {
        return DB::transaction(function () use ($passportHolder, $data, $user) {
            if (isset($data['passport_number'])) {
                $this->assertPassportNumberAvailable($data['passport_number'], $passportHolder->id);
            }

            $photo = $data['photo'] ?? null;
            unset($data['photo']);

            $passportHolder->update($data);
            $passportHolder->forceFill(['updated_by' => $user->id])->save();

            if ($photo instanceof UploadedFile) {
                $passportHolder->update([
                    'photo_path' => $photo->store('passport-holders/'.$passportHolder->id, 'private'),
                ]);
            }

            return $passportHolder->fresh();
        });
    }

    /**
     * A passport number must be unique among ACTIVE passport holders only
     * (Phase 01 section 6.2). Historical / inactive records keep the
     * number, so a re-registration after archival is possible.
     */
    protected function assertPassportNumberAvailable(?string $passportNumber, ?int $exceptId): void
    {
        if ($passportNumber === null || $passportNumber === '') {
            return;
        }

        $exists = PassportHolder::query()
            ->where('passport_number', $passportNumber)
            ->where('status', 'active')
            ->when($exceptId !== null, fn ($q) => $q->where('id', '!=', $exceptId))
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'passport_number' => 'An active passport holder with this passport number already exists.',
            ]);
        }
    }



    /**
     * Store an uploaded document on the private disk and record it.
     * Files are never placed in public/ and never served by a direct URL;
     * downloads go through PassportHolderController::downloadDocument(),
     * which re-checks the policy.
     */
    public function storeDocument(PassportHolder $passportHolder, UploadedFile $file, string $type, User $user): PassportHolderDocument
    {
        $path = $file->store('passport-holders/'.$passportHolder->id, 'private');

        return PassportHolderDocument::create([
            'passport_holder_id' => $passportHolder->id,
            'type' => $type,
            'file_path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'uploaded_by' => $user->id,
        ]);
    }

    /**
     * Archive instead of hard delete: a passport holder's recruitment
     * history must be preserved (master prompt section 9).
     */
    public function delete(PassportHolder $passportHolder, User $user): PassportHolder
    {
        return DB::transaction(function () use ($passportHolder, $user) {
            $passportHolder->update(['status' => 'inactive']);
            $passportHolder->forceFill(['updated_by' => $user->id])->save();

            return $passportHolder->fresh();
        });
    }
}
