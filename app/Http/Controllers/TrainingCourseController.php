<?php

namespace App\Http\Controllers;

use App\Models\TrainingCentre;
use App\Models\TrainingCourse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TrainingCourseController extends Controller
{
    public function index(Request $request)
    {
        $courses = TrainingCourse::with('centre')
            ->when($request->filled('search'), fn ($query) => $query->where('title', 'like', '%'.$request->string('search').'%'))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->orderBy('title')->paginate(15)->withQueryString();

        return view('training-courses.index', compact('courses'));
    }

    public function create()
    {
        return view('training-courses.create', [
            'record' => new TrainingCourse,
            'centres' => TrainingCentre::where('status', 'active')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        TrainingCourse::create($this->validated($request));

        return redirect()->route('training-courses.index')->with('success', __('Training course created.'));
    }

    public function edit(TrainingCourse $trainingCourse)
    {
        return view('training-courses.edit', [
            'record' => $trainingCourse,
            'centres' => TrainingCentre::where('status', 'active')->orWhereKey($trainingCourse->training_centre_id)->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, TrainingCourse $trainingCourse)
    {
        $trainingCourse->update($this->validated($request));

        return redirect()->route('training-courses.index')->with('success', __('Training course updated.'));
    }

    public function destroy(TrainingCourse $trainingCourse)
    {
        $trainingCourse->delete();

        return redirect()->route('training-courses.index')->with('success', __('Training course deleted.'));
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'training_centre_id' => ['nullable', 'exists:training_centres,id'],
            'title' => ['required', 'string', 'max:255'],
            'duration_days' => ['nullable', 'integer', 'min:1'],
            'fee' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);
    }
}
