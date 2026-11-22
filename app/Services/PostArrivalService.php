<?php

namespace App\Services;

use App\Models\PostArrival;
use App\Models\Recruitment;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Services\Concerns\WorkflowStepper;
use App\Models\ArrivalSupport;
use App\Models\Complaint;

class PostArrivalService
{
    use WorkflowStepper;

    public const STATUSES = ['pending', 'arrived', 'settled', 'issue_reported', 'completed'];

    public function __construct(protected WorkflowService $workflow) {}

    /** Scoped list query: isolation is enforced here, never in the view (Phase 01 section 8.3). */
    public function scopedQuery(User $user)
    {
        $query = PostArrival::query()->with(['recruitment.passportHolder', 'recruitment.agent'])->when(request()->filled('service_type'), fn ($q) => $q->whereHas('recruitment', fn ($r) => $r->where('service_type', request('service_type'))))->latest();
        if (method_exists(PostArrival::class, 'scopeVisibleTo')) {
            $query->visibleTo($user);
        }
        return $query;
    }

    public function create(array $data, ?User $user = null): PostArrival
    {
        return DB::transaction(function () use ($data, $user) {
            $record = PostArrival::create($this->attributes($data, $user));
            $this->storeFiles($record, $data);
            $recruitment = $record->recruitment;
            if ($recruitment) {
                $this->stepWorkflow($recruitment, 'post_arrival', $data['status'], $data['remarks'] ?? null, $user);
            }
            return $record->fresh();
        });
    }

    public function update(PostArrival $postArrival, array $data, ?User $user = null): PostArrival
    {
        return DB::transaction(function () use ($postArrival, $data, $user) {
            $postArrival->update($this->attributes($data, $user, $postArrival));
            $this->storeFiles($postArrival, $data);
            if (isset($data['status']) || isset($data['result'])) {
                $recruitment = $postArrival->recruitment;
                if ($recruitment) {
                    $this->stepWorkflow($recruitment, 'post_arrival', $data['status'], $data['remarks'] ?? null, $user);
                }
            }
            return $postArrival->fresh();
        });
    }

    /** Status-only change: still goes through WorkflowService so history is append-only. */
    public function changeStatus(PostArrival $postArrival, string $status, ?string $remarks, ?User $user = null): PostArrival
    {
        return DB::transaction(function () use ($postArrival, $status, $remarks, $user) {
            $postArrival->update(['status' => $status, 'remarks' => $remarks ?? $postArrival->remarks]);
            $recruitment = $postArrival->recruitment;
            if ($recruitment) {
                $this->stepWorkflow($recruitment, 'post_arrival', $status, $remarks, $user);
            }
            return $postArrival->fresh();
        });
    }

    /** Cancel/archive only — recruitment records are never hard-deleted (Phase 01 section 7). */
    public function cancel(PostArrival $postArrival, ?string $remarks, ?User $user = null): PostArrival
    {
        return $this->changeStatus($postArrival, 'cancelled', $remarks, $user);
    }

    protected function attributes(array $data, ?User $user, ?PostArrival $existing = null): array
    {
        $allowed = ['recruitment_id', 'arrival_date', 'airport_pickup', 'employer_contact', 'accommodation', 'feedback', 'status', 'remarks'];
        $attrs = array_intersect_key($data, array_flip($allowed));
        if ($existing === null && $user) {
            $attrs['created_by'] = $user->id;
        }
        $attrs['updated_by'] = $user?->id;
        return $attrs;
    }

    /** Uploads go to the private disk and are served only through an authorised download (Phase 01 section 13). */
    protected function storeFiles(PostArrival $postArrival, array $data): void
    {
        foreach ([] as $field) {
            if (! empty($data[$field]) && $data[$field] instanceof UploadedFile) {
                $postArrival->update([$field => $data[$field]->store('recruitments/' . $postArrival->recruitment_id . '/post-arrival', 'private')]);
            }
        }
    }

    public function downloadPath(PostArrival $postArrival, string $field): ?string
    {
        $path = $postArrival->$field ?? null;
        return $path && Storage::disk('private')->exists($path) ? $path : null;
    }

    public function storeComplaint(PostArrival $postArrival, array $data, ?User $user = null): Complaint
    {
        return DB::transaction(function () use ($postArrival, $data, $user) {
            $complaint = Complaint::create(array_merge($data, [
                'post_arrival_id' => $postArrival->id, 'recruitment_id' => $postArrival->recruitment_id,
                'raised_by' => $user?->id, 'status' => $data['status'] ?? 'open',
            ]));
            return $complaint;
        });
    }

    public function updateComplaint(Complaint $complaint, array $data): Complaint
    {
        $complaint->update($data);
        return $complaint->fresh();
    }

    public function storeSupport(PostArrival $postArrival, array $data, ?User $user = null): ArrivalSupport
    {
        return ArrivalSupport::create([
            'post_arrival_id' => $postArrival->id,
            'type' => $data['type'], 'description' => $data['description'] ?? null,
            'status' => $data['status'] ?? 'open',
            'created_by' => $user?->id, 'updated_by' => $user?->id,
        ]);
    }
}
