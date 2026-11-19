<?php

namespace App\Http\Controllers;

use App\Models\ArrivalSupport;
use App\Models\Complaint;
use App\Models\PostArrival;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PostArrivalRegistersController extends Controller
{
    public function followUps(Request $request): View
    {
        $this->authorize('viewAny', PostArrival::class);
        $records = ArrivalSupport::visibleTo($request->user())
            ->with('postArrival.recruitment.passportHolder')
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->when($request->filled('search'), fn ($q) => $q->where(function ($filter) use ($request) {
                $search = '%'.$request->string('search').'%';
                $filter->where('type', 'like', $search)->orWhere('description', 'like', $search)
                    ->orWhereHas('postArrival.recruitment', fn ($r) => $r->where('tracking_number', 'like', $search)
                        ->orWhereHas('passportHolder', fn ($h) => $h->where('full_name', 'like', $search)));
            }))
            ->latest()->paginate(15)->withQueryString();

        return view('post-arrival-registers.follow-ups', compact('records'));
    }

    public function feedback(Request $request): View
    {
        $this->authorize('viewAny', PostArrival::class);
        $records = PostArrival::visibleTo($request->user())->whereNotNull('feedback')->where('feedback', '<>', '')
            ->with('recruitment.passportHolder')
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->when($request->filled('search'), fn ($q) => $q->where(function ($filter) use ($request) {
                $search = '%'.$request->string('search').'%';
                $filter->where('feedback', 'like', $search)
                    ->orWhereHas('recruitment', fn ($r) => $r->where('tracking_number', 'like', $search)
                        ->orWhereHas('passportHolder', fn ($h) => $h->where('full_name', 'like', $search)));
            }))
            ->latest()->paginate(15)->withQueryString();

        return view('post-arrival-registers.feedback', compact('records'));
    }

    public function complaints(Request $request): View
    {
        $this->authorize('viewAny', PostArrival::class);
        $records = Complaint::visibleTo($request->user())
            ->with(['postArrival', 'recruitment.passportHolder'])
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->when($request->filled('search'), fn ($q) => $q->where(function ($filter) use ($request) {
                $search = '%'.$request->string('search').'%';
                $filter->where('subject', 'like', $search)->orWhere('description', 'like', $search)
                    ->orWhereHas('recruitment', fn ($r) => $r->where('tracking_number', 'like', $search)
                        ->orWhereHas('passportHolder', fn ($h) => $h->where('full_name', 'like', $search)));
            }))
            ->latest()->paginate(15)->withQueryString();

        return view('post-arrival-registers.complaints', compact('records'));
    }
}
