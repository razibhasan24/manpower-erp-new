<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\RecruitmentDocument;
use App\Models\Visa;
use App\Models\VisaLot;
use App\Models\WorkPermit;
use Illuminate\Http\Request;
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
