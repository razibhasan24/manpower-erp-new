<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostArrival\ChangePostArrivalStatusRequest;
use App\Http\Requests\PostArrival\StorePostArrivalRequest;
use App\Http\Requests\PostArrival\UpdatePostArrivalRequest;
use App\Models\PostArrival;
use App\Services\PostArrivalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostArrivalController extends Controller
{
    public function __construct(protected PostArrivalService $service) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', PostArrival::class);
        $records = $this->service->scopedQuery($request->user())
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->when($request->filled('search'), fn ($q) => $q->whereHas('recruitment', fn ($r) => $r->where('tracking_number', 'like', '%' . $request->string('search') . '%')))
            ->paginate(15)->withQueryString();
        return view('post-arrivals.index', ['records' => $records, 'statuses' => PostArrivalService::STATUSES]);
    }

    public function create(Request $request)
    {
        $this->authorize('create', PostArrival::class);
        $recruitments = \App\Models\Recruitment::visibleTo($request->user())->orderByDesc('id')->limit(200)->get();
        return view('post-arrivals.create', compact('recruitments'));
    }

    public function store(StorePostArrivalRequest $request)
    {
        $record = $this->service->create($request->validated(), $request->user());
        if ($request->wantsJson()) {
            return response()->json(new \App\Http\Resources\PostArrivalResource($record), 201);
        }
        return redirect()->route('post-arrivals.show', $record)->with('success', __('Record created.'));
    }

    public function show(PostArrival $postArrival)
    {
        $this->authorize('view', $postArrival);
        $postArrival->load(['recruitment.passportHolder', 'recruitment.stageHistories']);
        return view('post-arrivals.show', ['record' => $postArrival, 'recruitment' => $postArrival->recruitment, 'statuses' => PostArrivalService::STATUSES]);
    }

    public function edit(PostArrival $postArrival)
    {
        $this->authorize('update', $postArrival);
        $recruitments = \App\Models\Recruitment::visibleTo(auth()->user())->orderByDesc('id')->limit(200)->get();
        return view('post-arrivals.edit', ['record' => $postArrival, 'recruitments' => $recruitments]);
    }

    public function update(UpdatePostArrivalRequest $request, PostArrival $postArrival)
    {
        $record = $this->service->update($postArrival, $request->validated(), $request->user());
        if ($request->wantsJson()) {
            return response()->json(new \App\Http\Resources\PostArrivalResource($record));
        }
        return redirect()->route('post-arrivals.show', $postArrival)->with('success', __('Record updated.'));
    }

    public function changeStatus(ChangePostArrivalStatusRequest $request, PostArrival $postArrival)
    {
        $this->authorize('changeStatus', $postArrival);
        $record = $this->service->changeStatus($postArrival, $request->validated()['status'], $request->validated()['remarks'] ?? null, $request->user());
        if ($request->wantsJson()) {
            return response()->json(new \App\Http\Resources\PostArrivalResource($record));
        }
        return redirect()->route('post-arrivals.show', $postArrival)->with('success', __('Status updated.'));
    }

    public function download(Request $request, PostArrival $postArrival, string $field)
    {
        $this->authorize('download', $postArrival);
        $path = $this->service->downloadPath($postArrival, $field);
        abort_if($path === null, 404);
        return Storage::disk('private')->download($path);
    }

    /** Cancel/archive only — never a hard delete of a pipeline record. */
    public function destroy(Request $request, PostArrival $postArrival)
    {
        $this->authorize('delete', $postArrival);
        $this->service->cancel($postArrival, $request->string('remarks') ?: 'Cancelled', $request->user());
        return redirect()->route('post-arrivals.index')->with('success', __('Record cancelled.'));
    }

    public function storeComplaint(Request $request, PostArrival $postArrival)
    {
        $this->authorize('update', $postArrival);
        $data = $request->validate(['subject' => ['required', 'string', 'max:255'], 'description' => ['required', 'string'], 'status' => ['nullable', 'in:open,in_progress,resolved,closed']]);
        $this->service->storeComplaint($postArrival, $data, $request->user());
        return redirect()->route('post-arrivals.show', $postArrival)->with('success', __('Complaint recorded.'));
    }

    public function storeSupport(Request $request, PostArrival $postArrival)
    {
        $this->authorize('update', $postArrival);
        $data = $request->validate(['type' => ['required', 'string', 'max:255'], 'description' => ['nullable', 'string'], 'status' => ['nullable', 'in:open,in_progress,resolved,closed']]);
        $this->service->storeSupport($postArrival, $data, $request->user());
        return redirect()->route('post-arrivals.show', $postArrival)->with('success', __('Support follow-up recorded.'));
    }
}
