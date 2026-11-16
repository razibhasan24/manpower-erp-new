<?php

namespace App\Http\Controllers;

use App\Http\Requests\PassportReturn\ChangePassportReturnStatusRequest;
use App\Http\Requests\PassportReturn\StorePassportReturnRequest;
use App\Http\Requests\PassportReturn\UpdatePassportReturnRequest;
use App\Models\PassportReturn;
use App\Services\PassportReturnService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PassportReturnController extends Controller
{
    public function __construct(protected PassportReturnService $service) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', PassportReturn::class);
        $records = $this->service->scopedQuery($request->user())
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->when($request->filled('search'), fn ($q) => $q->whereHas('recruitment', fn ($r) => $r->where('tracking_number', 'like', '%' . $request->string('search') . '%')))
            ->paginate(15)->withQueryString();
        return view('passport-returns.index', ['records' => $records, 'statuses' => PassportReturnService::STATUSES]);
    }

    public function create(Request $request)
    {
        $this->authorize('create', PassportReturn::class);
        $recruitments = \App\Models\Recruitment::visibleTo($request->user())->orderByDesc('id')->limit(200)->get();
        return view('passport-returns.create', compact('recruitments'));
    }

    public function store(StorePassportReturnRequest $request)
    {
        $record = $this->service->create($request->validated(), $request->user());
        if ($request->wantsJson()) {
            return response()->json(new \App\Http\Resources\PassportReturnResource($record), 201);
        }
        return redirect()->route('passport-returns.show', $record)->with('success', __('Record created.'));
    }

    public function show(PassportReturn $passportReturn)
    {
        $this->authorize('view', $passportReturn);
        $passportReturn->load(['recruitment.passportHolder', 'recruitment.stageHistories']);
        return view('passport-returns.show', ['record' => $passportReturn, 'recruitment' => $passportReturn->recruitment, 'statuses' => PassportReturnService::STATUSES]);
    }

    public function edit(PassportReturn $passportReturn)
    {
        $this->authorize('update', $passportReturn);
        $recruitments = \App\Models\Recruitment::visibleTo(auth()->user())->orderByDesc('id')->limit(200)->get();
        return view('passport-returns.edit', ['record' => $passportReturn, 'recruitments' => $recruitments]);
    }

    public function update(UpdatePassportReturnRequest $request, PassportReturn $passportReturn)
    {
        $record = $this->service->update($passportReturn, $request->validated(), $request->user());
        if ($request->wantsJson()) {
            return response()->json(new \App\Http\Resources\PassportReturnResource($record));
        }
        return redirect()->route('passport-returns.show', $passportReturn)->with('success', __('Record updated.'));
    }

    public function changeStatus(ChangePassportReturnStatusRequest $request, PassportReturn $passportReturn)
    {
        $this->authorize('changeStatus', $passportReturn);
        $record = $this->service->changeStatus($passportReturn, $request->validated()['status'], $request->validated()['remarks'] ?? null, $request->user());
        if ($request->wantsJson()) {
            return response()->json(new \App\Http\Resources\PassportReturnResource($record));
        }
        return redirect()->route('passport-returns.show', $passportReturn)->with('success', __('Status updated.'));
    }

    public function download(Request $request, PassportReturn $passportReturn, string $field)
    {
        $this->authorize('download', $passportReturn);
        $path = $this->service->downloadPath($passportReturn, $field);
        abort_if($path === null, 404);
        return Storage::disk('private')->download($path);
    }

    /** Cancel/archive only — never a hard delete of a pipeline record. */
    public function destroy(Request $request, PassportReturn $passportReturn)
    {
        $this->authorize('delete', $passportReturn);
        $this->service->cancel($passportReturn, $request->string('remarks') ?: 'Cancelled', $request->user());
        return redirect()->route('passport-returns.index')->with('success', __('Record cancelled.'));
    }
}
