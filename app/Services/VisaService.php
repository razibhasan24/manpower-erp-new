<?php

namespace App\Services;

use App\Models\Visa;
use App\Models\MofaProcess;
use App\Models\Recruitment;
use App\Models\RecruitmentDocument;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Services\Concerns\WorkflowStepper;
use App\Models\Appointment;
use App\Models\VisaLot;
use App\Models\WorkPermit;

class VisaService
{
    use WorkflowStepper;

    public const STATUSES = ['pending', 'submitted', 'approved', 'rejected', 'returned', 'cancelled'];

    public function __construct(protected WorkflowService $workflow) {}

    /** Scoped list query: isolation is enforced here, never in the view (Phase 01 section 8.3). */
    public function scopedQuery(User $user)
    {
        $query = Visa::query()->with(['recruitment.passportHolder', 'recruitment.agent'])->when(request()->filled('service_type'), fn ($q) => $q->whereHas('recruitment', fn ($r) => $r->where('service_type', request('service_type'))))->latest();
        if (method_exists(Visa::class, 'scopeVisibleTo')) {
            $query->visibleTo($user);
        }
        return $query;
    }

    public function create(array $data, ?User $user = null): Visa
    {
        return DB::transaction(function () use ($data, $user) {
            $record = Visa::create($this->attributes($data, $user));
            $this->storeFiles($record, $data);
            $recruitment = $record->recruitment;
            if ($recruitment) {
                $this->stepWorkflow($recruitment, 'visa_processing', $data['status'], $data['remarks'] ?? null, $user);
            }
            return $record->fresh();
        });
    }

    public function update(Visa $visa, array $data, ?User $user = null): Visa
    {
        return DB::transaction(function () use ($visa, $data, $user) {
            $visa->update($this->attributes($data, $user, $visa));
            $this->storeFiles($visa, $data);
            if (isset($data['status']) || isset($data['result'])) {
                $recruitment = $visa->recruitment;
                if ($recruitment) {
                    $this->stepWorkflow($recruitment, 'visa_processing', $data['status'], $data['remarks'] ?? null, $user);
                }
            }
            return $visa->fresh();
        });
    }

    /** Status-only change: still goes through WorkflowService so history is append-only. */
    public function changeStatus(Visa $visa, string $status, ?string $remarks, ?User $user = null): Visa
    {
        return DB::transaction(function () use ($visa, $status, $remarks, $user) {
            $visa->update(['status' => $status, 'remarks' => $remarks ?? $visa->remarks]);
            $recruitment = $visa->recruitment;
            if ($recruitment) {
                $this->stepWorkflow($recruitment, 'visa_processing', $status, $remarks, $user);
            }
            return $visa->fresh();
        });
    }

    /** Cancel/archive only — recruitment records are never hard-deleted (Phase 01 section 7). */
    public function cancel(Visa $visa, ?string $remarks, ?User $user = null): Visa
    {
        return $this->changeStatus($visa, 'cancelled', $remarks, $user);
    }

    protected function attributes(array $data, ?User $user, ?Visa $existing = null): array
    {
        $allowed = ['recruitment_id', 'visa_lot_id', 'submission_date', 'status', 'mofa_status', 'remarks'];
        $attrs = array_intersect_key($data, array_flip($allowed));
        if ($existing === null && $user) {
            $attrs['created_by'] = $user->id;
        }
        $attrs['updated_by'] = $user?->id;
        return $attrs;
    }

    /** Uploads go to the private disk and are served only through an authorised download (Phase 01 section 13). */
    protected function storeFiles(Visa $visa, array $data): void
    {
        // Visas have no file column; the document is a recruitment document (stage visa).
        foreach (['document_path'] as $field) {
            if (! empty($data[$field]) && $data[$field] instanceof UploadedFile) {
                $path = $data[$field]->store('recruitments/' . $visa->recruitment_id . '/visa', 'private');
                RecruitmentDocument::create([
                    'recruitment_id' => $visa->recruitment_id, 'type' => 'visa', 'stage' => 'visa',
                    'file_path' => $path, 'original_name' => $data[$field]->getClientOriginalName(),
                    'mime_type' => $data[$field]->getMimeType(), 'size' => $data[$field]->getSize(),
                    'uploaded_by' => $visa->created_by,
                ]);
            }
        }
    }

    public function downloadPath(Visa $visa, string $field): ?string
    {
        $doc = RecruitmentDocument::where('recruitment_id', $visa->recruitment_id)->where('stage', 'visa')->latest()->first();
        $path = $doc?->file_path;
        return $path && Storage::disk('private')->exists($path) ? $path : null;
    }

    /** MOFA is a stage on the visa (Phase 01 section 6.3), never a separate candidate record. */
    public function updateMofa(Visa $visa, array $data, ?User $user = null): Visa
    {
        if (in_array($visa->recruitment?->service_type, ['student', 'hajj_umrah'], true)) {
            throw \Illuminate\Validation\ValidationException::withMessages(['visa' => 'MOFA is not a separate stage in the Student or Hajj/Umrah workflow.']);
        }
        return DB::transaction(function () use ($visa, $data, $user) {
            // MOFA is its own record (mofa_processes) in Group A; the visa keeps a mirrored mofa_status.
            MofaProcess::create([
                'visa_id' => $visa->id, 'status' => $data['mofa_status'],
                'submitted_at' => $data['mofa_status'] === 'submitted' ? now() : null,
                'completed_at' => in_array($data['mofa_status'], ['approved', 'rejected'], true) ? now() : null,
                'reference_number' => $data['reference_number'] ?? null,
                'remarks' => $data['remarks'] ?? null,
            ]);
            $visa->update(['mofa_status' => $data['mofa_status'], 'remarks' => $data['remarks'] ?? $visa->remarks]);
            $this->stepWorkflow($visa->recruitment, 'mofa_processing', $data['mofa_status'], $data['remarks'] ?? null, $user);
            return $visa->fresh(['mofaProcesses']);
        });
    }

    public function storeWorkPermit(Visa $visa, array $data, ?User $user = null): WorkPermit
    {
        if (in_array($visa->recruitment?->service_type, ['student', 'hajj_umrah'], true)) {
            throw \Illuminate\Validation\ValidationException::withMessages(['visa' => 'Work permits are not part of the Student or Hajj/Umrah workflow.']);
        }
        return DB::transaction(function () use ($visa, $data, $user) {
            $permit = WorkPermit::updateOrCreate(['visa_id' => $visa->id], array_diff_key($data, ['status' => true]));
            $this->stepWorkflow($visa->recruitment, 'work_permit', 'approved', $data['remarks'] ?? null, $user);
            return $permit;
        });
    }

    public function storeAppointment(Visa $visa, array $data, ?User $user = null): Appointment
    {
        if (in_array($visa->recruitment?->service_type, ['student', 'hajj_umrah'], true)) {
            throw \Illuminate\Validation\ValidationException::withMessages(['visa' => 'Appointments are not part of the Student or Hajj/Umrah workflow.']);
        }
        return DB::transaction(function () use ($visa, $data, $user) {
            $appointment = Appointment::create(array_merge($data, ['visa_id' => $visa->id]));
            $this->stepWorkflow($visa->recruitment, 'appointment', $data['status'] ?? 'confirmed', $data['remarks'] ?? null, $user);
            return $appointment;
        });
    }

    public function storeVisaLot(array $data): VisaLot
    {
        return VisaLot::create($data);
    }
}
