<?php

namespace App\Http\Controllers;

use App\Models\ArrivalSupport;
use App\Models\Complaint;
use App\Models\PostArrival;
use App\Models\Recruitment;
use App\Services\PostArrivalService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PostArrivalRegistersController extends Controller
{
    public function createComplaint(Request $request): View
    {
        $this->authorize('create', PostArrival::class);
        $recruitments = Recruitment::visibleTo($request->user())->with('passportHolder')->orderByDesc('id')->limit(300)->get();

        return view('post-arrival-registers.create-complaint', compact('recruitments'));
    }

    public function storeComplaint(Request $request)
    {
        $this->authorize('create', PostArrival::class);
        $data = $request->validate([
            'recruitment_id' => ['required', 'integer', Rule::exists('recruitments', 'id')],
            'subject' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:5000'],
            'status' => ['required', Rule::in(['open', 'in_progress', 'resolved', 'closed'])],
        ]);
        $recruitment = Recruitment::visibleTo($request->user())->findOrFail($data['recruitment_id']);
        $arrival = PostArrival::visibleTo($request->user())->where('recruitment_id', $recruitment->id)->latest('id')->first();
        $complaint = Complaint::create([
            'post_arrival_id' => $arrival?->id,
            'recruitment_id' => $recruitment->id,
            'subject' => $data['subject'],
            'description' => $data['description'],
            'status' => $data['status'],
            'raised_by' => $request->user()->id,
            'created_by' => $request->user()->id,
            'updated_by' => $request->user()->id,
        ]);

        return redirect()->route('post-arrival-complaints.index')->with('success', __('Complaint created successfully.'));
    }

    public function createFollowUp(Request $request): View
    {
        $this->authorize('create', PostArrival::class);
        $postArrivals = PostArrival::visibleTo($request->user())
            ->with('recruitment.passportHolder')->orderByDesc('id')->limit(300)->get();

        return view('post-arrival-registers.create-follow-up', compact('postArrivals'));
    }

    public function storeFollowUp(Request $request, PostArrivalService $service)
    {
        $this->authorize('create', PostArrival::class);
        $data = $request->validate([
            'post_arrival_id' => ['required', 'integer', Rule::exists('post_arrivals', 'id')],
            'type' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'status' => ['required', Rule::in(['open', 'in_progress', 'resolved', 'closed'])],
        ]);
        $postArrival = PostArrival::visibleTo($request->user())->findOrFail($data['post_arrival_id']);
        $service->storeSupport($postArrival, $data, $request->user());

        return redirect()->route('follow-ups.index')->with('success', __('Follow-up created successfully.'));
    }

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
