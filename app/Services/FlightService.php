<?php

namespace App\Services;

use App\Models\Flight;
use App\Models\Recruitment;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Services\Concerns\WorkflowStepper;

class FlightService
{
    use WorkflowStepper;

    public const STATUSES = ['pending', 'ticket_issued', 'cancelled', 'rescheduled'];

    public function __construct(protected WorkflowService $workflow) {}

    /** Flight records span two workflow stages: booking, then ticket issued. */
    protected function flightWorkflowTarget(string $status, Recruitment $recruitment): array
    {
        if (in_array($status, ['ticket_issued', 'rescheduled'], true)) {
            return ['ticket_issued', $status];
        }
        if ($status === 'cancelled' && in_array($recruitment->current_stage, ['flight_booking', 'ticket_issued'], true)) {
            return [$recruitment->current_stage, 'cancelled'];
        }
        return ['flight_booking', $status === 'pending' ? 'pending' : 'cancelled'];
    }

    /** Scoped list query: isolation is enforced here, never in the view (Phase 01 section 8.3). */
    public function scopedQuery(User $user)
    {
        $query = Flight::query()->with(['recruitment.passportHolder', 'recruitment.agent'])->latest();
        if (method_exists(Flight::class, 'scopeVisibleTo')) {
            $query->visibleTo($user);
        }
        return $query;
    }

    public function create(array $data, ?User $user = null): Flight
    {
        return DB::transaction(function () use ($data, $user) {
            $record = Flight::create($this->attributes($data, $user));
            $this->storeFiles($record, $data);
            $recruitment = $record->recruitment;
            if ($recruitment) {
                [$flightStage, $flightStatus] = $this->flightWorkflowTarget($data['status'], $recruitment);
                $this->stepWorkflow($recruitment, $flightStage, $flightStatus, $data['remarks'] ?? null, $user);
            }
            return $record->fresh();
        });
    }

    public function update(Flight $flight, array $data, ?User $user = null): Flight
    {
        return DB::transaction(function () use ($flight, $data, $user) {
            $flight->update($this->attributes($data, $user, $flight));
            $this->storeFiles($flight, $data);
            if (isset($data['status']) || isset($data['result'])) {
                $recruitment = $flight->recruitment;
                if ($recruitment) {
                    [$flightStage, $flightStatus] = $this->flightWorkflowTarget($data['status'], $recruitment);
                $this->stepWorkflow($recruitment, $flightStage, $flightStatus, $data['remarks'] ?? null, $user);
                }
            }
            return $flight->fresh();
        });
    }

    /** Status-only change: still goes through WorkflowService so history is append-only. */
    public function changeStatus(Flight $flight, string $status, ?string $remarks, ?User $user = null): Flight
    {
        return DB::transaction(function () use ($flight, $status, $remarks, $user) {
            $flight->update(['status' => $status, 'remarks' => $remarks ?? $flight->remarks]);
            $recruitment = $flight->recruitment;
            if ($recruitment) {
                [$flightStage, $flightStatus] = $this->flightWorkflowTarget($status, $recruitment);
                $this->stepWorkflow($recruitment, $flightStage, $flightStatus, $remarks, $user);
            }
            return $flight->fresh();
        });
    }

    /** Cancel/archive only — recruitment records are never hard-deleted (Phase 01 section 7). */
    public function cancel(Flight $flight, ?string $remarks, ?User $user = null): Flight
    {
        return $this->changeStatus($flight, 'cancelled', $remarks, $user);
    }

    protected function attributes(array $data, ?User $user, ?Flight $existing = null): array
    {
        $allowed = ['airline_id', 'flight_number', 'travel_agency_id', 'departure_date', 'departure_airport', 'arrival_airport', 'ticket_number', 'status'];
        $attrs = array_intersect_key($data, array_flip($allowed));
        if ($existing === null && $user) {
            $attrs['created_by'] = $user->id;
        }
        $attrs['updated_by'] = $user?->id;
        return $attrs;
    }

    /** Uploads go to the private disk and are served only through an authorised download (Phase 01 section 13). */
    protected function storeFiles(Flight $flight, array $data): void
    {
        foreach (['ticket_path', 'noc_path'] as $field) {
            if (! empty($data[$field]) && $data[$field] instanceof UploadedFile) {
                $flight->update([$field => $data[$field]->store('recruitments/' . $flight->recruitment_id . '/flight', 'private')]);
            }
        }
    }

    public function downloadPath(Flight $flight, string $field): ?string
    {
        $path = $flight->$field ?? null;
        return $path && Storage::disk('private')->exists($path) ? $path : null;
    }
}
