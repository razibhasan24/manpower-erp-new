<?php

namespace App\Http\Controllers;

use App\Http\Requests\Training\ChangeTrainingStatusRequest;
use App\Http\Requests\Training\StoreTrainingRequest;
use App\Http\Requests\Training\UpdateTrainingRequest;
use App\Models\Training;
use App\Services\TrainingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TrainingController extends Controller
{
    public function __construct(protected TrainingService $service) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', Training::class);
        $records = $this->service->scopedQuery($request->user())
            ->when($request->filled('status'), fn ($q) => $q->where('result', $request->string('status')))
            ->when($request->boolean('results'), fn ($q) => $q->whereIn('result', ['passed', 'failed']))
            ->when($request->filled('search'), fn ($q) => $q->whereHas('recruitment', fn ($r) => $r->where('tracking_number', 'like', '%' . $request->string('search') . '%')))
            ->paginate(15)->withQueryString();
        return view('trainings.index', ['records' => $records, 'statuses' => TrainingService::STATUSES]);
    }

    public function create(Request $request)
    {
        $this->authorize('create', Training::class);
        $recruitments = \App\Models\Recruitment::visibleTo($request->user())->orderByDesc('id')->limit(200)->get();
        $trainingCentres = \App\Models\TrainingCentre::orderBy('name')->get();
        $trainingCourses = \App\Models\TrainingCourse::orderBy('id')->get();
        return view('trainings.create', array_merge(['recruitments' => $recruitments], ['trainingCentres' => $trainingCentres, 'trainingCourses' => $trainingCourses]));
    }

    public function store(StoreTrainingRequest $request)
    {
        $record = $this->service->create($request->validated(), $request->user());
        if ($request->wantsJson()) {
            return response()->json(new \App\Http\Resources\TrainingResource($record), 201);
        }
        return redirect()->route('trainings.show', $record)->with('success', __('Record created.'));
    }

    public function show(Training $training)
    {
        $this->authorize('view', $training);
        $training->load(['recruitment.passportHolder', 'recruitment.stageHistories']);
        return view('trainings.show', ['record' => $training, 'recruitment' => $training->recruitment, 'statuses' => TrainingService::STATUSES]);
    }

    public function edit(Training $training)
    {
        $this->authorize('update', $training);
        $recruitments = \App\Models\Recruitment::visibleTo(auth()->user())->orderByDesc('id')->limit(200)->get();
        $trainingCentres = \App\Models\TrainingCentre::orderBy('name')->get();
        $trainingCourses = \App\Models\TrainingCourse::orderBy('id')->get();
        return view('trainings.edit', array_merge(['record' => $training, 'recruitments' => $recruitments], ['trainingCentres' => $trainingCentres, 'trainingCourses' => $trainingCourses]));
    }

    public function update(UpdateTrainingRequest $request, Training $training)
    {
        $record = $this->service->update($training, $request->validated(), $request->user());
        if ($request->wantsJson()) {
            return response()->json(new \App\Http\Resources\TrainingResource($record));
        }
        return redirect()->route('trainings.show', $training)->with('success', __('Record updated.'));
    }

    public function changeStatus(ChangeTrainingStatusRequest $request, Training $training)
    {
        $this->authorize('changeStatus', $training);
        $record = $this->service->changeStatus($training, $request->validated()['status'], $request->validated()['remarks'] ?? null, $request->user());
        if ($request->wantsJson()) {
            return response()->json(new \App\Http\Resources\TrainingResource($record));
        }
        return redirect()->route('trainings.show', $training)->with('success', __('Status updated.'));
    }

    public function download(Request $request, Training $training, string $field)
    {
        $this->authorize('download', $training);
        $path = $this->service->downloadPath($training, $field);
        abort_if($path === null, 404);
        return Storage::disk('private')->download($path);
    }

    /** Cancel/archive only — never a hard delete of a pipeline record. */
    public function destroy(Request $request, Training $training)
    {
        $this->authorize('delete', $training);
        $this->service->cancel($training, $request->string('remarks') ?: 'Cancelled', $request->user());
        return redirect()->route('trainings.index')->with('success', __('Record cancelled.'));
    }
}
