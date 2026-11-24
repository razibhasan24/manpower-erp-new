<?php

namespace App\Http\Controllers;

use App\Models\Training;
use App\Models\TrainingAttendance;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TrainingAttendanceController extends Controller
{
    public function index(Request $request)
    {
        $records = TrainingAttendance::query()->whereHas('training', fn ($q) => $q->visibleTo($request->user()))
            ->with(['training.recruitment.passportHolder', 'training.course'])->orderByDesc('attendance_date')
            ->paginate(20)->withQueryString();
        return view('training-attendance.index', compact('records'));
    }

    public function create(Request $request)
    {
        $trainings = $this->trainings($request);
        return view('training-attendance.create', compact('trainings'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['created_by'] = $data['updated_by'] = $request->user()->id;
        $record = TrainingAttendance::create($data);
        $this->syncPercentage($record->training_id);
        return redirect()->route('training-attendance.index')->with('success', __('Attendance recorded.'));
    }

    public function edit(Request $request, TrainingAttendance $trainingAttendance)
    {
        $record = $this->visibleRecord($request, $trainingAttendance);
        $trainings = $this->trainings($request);
        return view('training-attendance.edit', compact('record', 'trainings'));
    }

    public function update(Request $request, TrainingAttendance $trainingAttendance)
    {
        $record = $this->visibleRecord($request, $trainingAttendance);
        $oldTrainingId = $record->training_id;
        $data = $this->validateData($request, $record);
        $data['updated_by'] = $request->user()->id;
        $record->update($data);
        $this->syncPercentage($oldTrainingId);
        if ($oldTrainingId !== (int) $record->training_id) $this->syncPercentage($record->training_id);
        return redirect()->route('training-attendance.index')->with('success', __('Attendance updated.'));
    }

    public function destroy(Request $request, TrainingAttendance $trainingAttendance)
    {
        $record = $this->visibleRecord($request, $trainingAttendance);
        $trainingId = $record->training_id;
        $record->delete();
        $this->syncPercentage($trainingId);
        return redirect()->route('training-attendance.index')->with('success', __('Attendance deleted.'));
    }

    private function trainings(Request $request)
    {
        return Training::query()->visibleTo($request->user())
            ->with(['recruitment.passportHolder', 'course'])->latest()->get();
    }

    private function visibleRecord(Request $request, TrainingAttendance $record): TrainingAttendance
    {
        abort_unless(TrainingAttendance::whereKey($record->id)->whereHas('training', fn ($q) => $q->visibleTo($request->user()))->exists(), 404);
        return $record;
    }

    private function validateData(Request $request, ?TrainingAttendance $record = null): array
    {
        $trainingIds = Training::query()->visibleTo($request->user())->select('id');
        return $request->validate([
            'training_id' => ['required', 'integer', 'exists:trainings,id', function ($attribute, $value, $fail) use ($trainingIds) {
                if (!(clone $trainingIds)->whereKey($value)->exists()) $fail(__('Select a training record you can access.'));
            }],
            'attendance_date' => ['required', 'date', Rule::unique('training_attendances')->where(fn ($q) => $q->where('training_id', $request->input('training_id')))->ignore($record?->id)],
            'status' => ['required', 'in:present,absent,late,excused'],
            'remarks' => ['nullable', 'string', 'max:2000'],
        ]);
    }

    private function syncPercentage(int $trainingId): void
    {
        $training = Training::find($trainingId);
        if (!$training) return;
        $total = $training->attendances()->count();
        $attended = $training->attendances()->whereIn('status', ['present', 'late'])->count();
        $training->update(['attendance_percentage' => $total ? round($attended * 100 / $total, 2) : null]);
    }
}
