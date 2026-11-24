<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\RecruitmentDocument;
use App\Models\Visa;
use App\Models\VisaLot;
use App\Models\WorkPermit;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class VisaRegisterController extends Controller
{
    public function lots(Request $request): View
    {
        $this->authorize('viewAny', Visa::class);
        $records = VisaLot::visibleTo($request->user())
            ->with('country')->withCount('visas')
            ->when($request->filled('search'), fn ($q) => $q->where('lot_number', 'like', '%'.$request->string('search').'%'))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->orderByDesc('id')->paginate(15)->withQueryString();

        return view('visa-register.lots', compact('records'));
    }

    public function createLot(Request $request): View
    {
        $this->authorize('create', Visa::class);
        $countries = \App\Models\Country::visibleTo($request->user())->where('status', 'active')->orderBy('name')->get();

        return view('visa-register.lots-create', compact('countries'));
    }

    public function storeLot(Request $request)
    {
        $this->authorize('create', Visa::class);

        $data = $request->validate([
            'lot_number' => ['required', 'string', 'max:255', Rule::unique('visa_lots', 'lot_number')],
            'country_id' => ['nullable', 'integer', 'exists:countries,id'],
            'status' => ['required', Rule::in(['open', 'submitted', 'closed', 'cancelled'])],
            'remarks' => ['nullable', 'string', 'max:5000'],
        ]);

        $lot = VisaLot::create($data);
        $lot->forceFill(['created_by' => $request->user()->id, 'updated_by' => $request->user()->id])->save();

        return redirect()->route('visa-register.lots')->with('success', __('Visa lot created successfully.'));
    }

    public function workPermits(Request $request): View
    {
        $this->authorize('viewAny', Visa::class);
        $records = WorkPermit::visibleTo($request->user())->with('visa.recruitment.passportHolder')
            ->when($request->filled('search'), fn ($q) => $q->where(function ($filter) use ($request) {
                $filter->where('permit_number', 'like', '%'.$request->string('search').'%')
                    ->orWhereHas('visa.recruitment', fn ($r) => $r->where('tracking_number', 'like', '%'.$request->string('search').'%'));
            }))
            ->orderByDesc('id')->paginate(15)->withQueryString();

        return view('visa-register.work-permits', compact('records'));
    }

    public function appointments(Request $request): View
    {
        $this->authorize('viewAny', Visa::class);
        $records = Appointment::visibleTo($request->user())->with('visa.recruitment.passportHolder')
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->when($request->filled('search'), fn ($q) => $q->whereHas('visa.recruitment', fn ($r) => $r->where('tracking_number', 'like', '%'.$request->string('search').'%')))
            ->orderByDesc('appointment_date')->paginate(15)->withQueryString();

        return view('visa-register.appointments', compact('records'));
    }

    public function createWorkPermit(Request $request): View
    {
        abort_unless($request->user()->can('visa.edit'), 403);
        $visas = $this->eligibleVisas($request)->whereDoesntHave('workPermit')->get();
        return view('visa-register.work-permit-form', ['visas' => $visas]);
    }

    public function storeWorkPermit(Request $request)
    {
        abort_unless($request->user()->can('visa.edit'), 403);
        $data = $this->validateWorkPermit($request);
        $visa = $this->eligibleVisas($request)->findOrFail($data['visa_id']);
        $this->authorize('update', $visa);
        $permit = $visa->workPermit()->create($data);
        $permit->forceFill(['created_by' => $request->user()->id, 'updated_by' => $request->user()->id])->save();
        return redirect()->route('visa-register.work-permits')->with('success', __('Work permit created.'));
    }

    public function editWorkPermit(Request $request, WorkPermit $workPermit): View
    {
        $workPermit = $this->visibleWorkPermit($request, $workPermit);
        $this->authorize('update', $workPermit->visa);
        $visas = $this->eligibleVisas($request)->where(function ($query) use ($workPermit) {
            $query->whereDoesntHave('workPermit')->orWhereKey($workPermit->visa_id);
        })->get();
        return view('visa-register.work-permit-form', compact('visas', 'workPermit'));
    }

    public function updateWorkPermit(Request $request, WorkPermit $workPermit)
    {
        $workPermit = $this->visibleWorkPermit($request, $workPermit);
        $this->authorize('update', $workPermit->visa);
        $data = $this->validateWorkPermit($request, $workPermit);
        $visa = $this->eligibleVisas($request)->findOrFail($data['visa_id']);
        $this->authorize('update', $visa);
        $workPermit->update($data);
        $workPermit->forceFill(['updated_by' => $request->user()->id])->save();
        return redirect()->route('visa-register.work-permits')->with('success', __('Work permit updated.'));
    }

    public function destroyWorkPermit(Request $request, WorkPermit $workPermit)
    {
        $workPermit = $this->visibleWorkPermit($request, $workPermit);
        $this->authorize('update', $workPermit->visa);
        $workPermit->delete();
        return redirect()->route('visa-register.work-permits')->with('success', __('Work permit archived.'));
    }

    public function createAppointment(Request $request): View
    {
        abort_unless($request->user()->can('visa.edit'), 403);
        $visas = $this->eligibleVisas($request)->get();
        return view('visa-register.appointment-form', ['visas' => $visas]);
    }

    public function storeAppointment(Request $request)
    {
        abort_unless($request->user()->can('visa.edit'), 403);
        $data = $this->validateAppointment($request);
        $visa = $this->eligibleVisas($request)->findOrFail($data['visa_id']);
        $this->authorize('update', $visa);
        $appointment = $visa->appointments()->create($data);
        $appointment->forceFill(['created_by' => $request->user()->id, 'updated_by' => $request->user()->id])->save();
        return redirect()->route('visa-register.appointments')->with('success', __('Appointment created.'));
    }

    public function editAppointment(Request $request, Appointment $appointment): View
    {
        $appointment = $this->visibleAppointment($request, $appointment);
        $this->authorize('update', $appointment->visa);
        $visas = $this->eligibleVisas($request)->get();
        return view('visa-register.appointment-form', compact('visas', 'appointment'));
    }

    public function updateAppointment(Request $request, Appointment $appointment)
    {
        $appointment = $this->visibleAppointment($request, $appointment);
        $this->authorize('update', $appointment->visa);
        $data = $this->validateAppointment($request);
        $visa = $this->eligibleVisas($request)->findOrFail($data['visa_id']);
        $this->authorize('update', $visa);
        $appointment->update($data);
        $appointment->forceFill(['updated_by' => $request->user()->id])->save();
        return redirect()->route('visa-register.appointments')->with('success', __('Appointment updated.'));
    }

    public function destroyAppointment(Request $request, Appointment $appointment)
    {
        $appointment = $this->visibleAppointment($request, $appointment);
        $this->authorize('update', $appointment->visa);
        $appointment->delete();
        return redirect()->route('visa-register.appointments')->with('success', __('Appointment cancelled and archived.'));
    }

    private function eligibleVisas(Request $request)
    {
        return Visa::visibleTo($request->user())->whereHas('recruitment', fn ($query) => $query->where('service_type', 'overseas'))
            ->with('recruitment.passportHolder')->latest();
    }

    private function visibleWorkPermit(Request $request, WorkPermit $permit): WorkPermit
    {
        abort_unless(WorkPermit::visibleTo($request->user())->whereKey($permit->id)->exists(), 404);
        return $permit->load('visa.recruitment');
    }

    private function visibleAppointment(Request $request, Appointment $appointment): Appointment
    {
        abort_unless(Appointment::visibleTo($request->user())->whereKey($appointment->id)->exists(), 404);
        return $appointment->load('visa.recruitment');
    }

    private function validateWorkPermit(Request $request, ?WorkPermit $permit = null): array
    {
        $visaIds = $this->eligibleVisas($request)->select('visas.id');
        return $request->validate([
            'visa_id' => ['required', 'integer', 'exists:visas,id', Rule::unique('work_permits', 'visa_id')->ignore($permit?->id)->whereNull('deleted_at'), function ($attribute, $value, $fail) use ($visaIds) {
                if (!(clone $visaIds)->whereKey($value)->exists()) $fail(__('Select an overseas visa record you can access.'));
            }],
            'permit_number' => ['nullable', 'string', 'max:255'],
            'issue_date' => ['nullable', 'date'],
            'expiry_date' => ['nullable', 'date', function ($attribute, $value, $fail) use ($request) {
                $issueDate = $request->input('issue_date');
                if ($value && $issueDate && strtotime($value) < strtotime($issueDate)) {
                    $fail(__('Expiry date must be on or after the issue date.'));
                }
            }],
            'remarks' => ['nullable', 'string', 'max:5000'],
        ]);
    }

    private function validateAppointment(Request $request): array
    {
        $visaIds = $this->eligibleVisas($request)->select('visas.id');
        return $request->validate([
            'visa_id' => ['required', 'integer', 'exists:visas,id', function ($attribute, $value, $fail) use ($visaIds) {
                if (!(clone $visaIds)->whereKey($value)->exists()) $fail(__('Select an overseas visa record you can access.'));
            }],
            'appointment_date' => ['required', 'date'],
            'location' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:pending,confirmed,completed,cancelled'],
            'remarks' => ['nullable', 'string', 'max:5000'],
        ]);
    }

    public function documents(Request $request): View
    {
        $this->authorize('viewAny', Visa::class);
        $records = RecruitmentDocument::visibleTo($request->user())->where('stage', 'visa')
            ->with('recruitment.passportHolder')
            ->when($request->filled('search'), fn ($q) => $q->where(function ($filter) use ($request) {
                $filter->where('original_name', 'like', '%'.$request->string('search').'%')
                    ->orWhereHas('recruitment', fn ($r) => $r->where('tracking_number', 'like', '%'.$request->string('search').'%'));
            }))
            ->with('recruitment.visa')
            ->orderByDesc('id')->paginate(15)->withQueryString();

        return view('visa-register.documents', compact('records'));
    }
}
