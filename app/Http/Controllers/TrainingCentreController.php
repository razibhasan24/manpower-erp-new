<?php

namespace App\Http\Controllers;

use App\Models\TrainingCentre;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TrainingCentreController extends Controller
{
    public function index(Request $request)
    {
        $centres = TrainingCentre::query()
            ->when($request->filled('search'), fn ($query) => $query->where('name', 'like', '%'.$request->string('search').'%'))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->orderBy('name')->paginate(15)->withQueryString();

        return view('training-centres.index', compact('centres'));
    }

    public function create()
    {
        return view('training-centres.create', ['record' => new TrainingCentre]);
    }

    public function store(Request $request)
    {
        TrainingCentre::create($this->validated($request));

        return redirect()->route('training-centres.index')->with('success', __('Training centre created.'));
    }

    public function edit(TrainingCentre $trainingCentre)
    {
        return view('training-centres.edit', ['record' => $trainingCentre]);
    }

    public function update(Request $request, TrainingCentre $trainingCentre)
    {
        $trainingCentre->update($this->validated($request));

        return redirect()->route('training-centres.index')->with('success', __('Training centre updated.'));
    }

    public function destroy(TrainingCentre $trainingCentre)
    {
        $trainingCentre->delete();

        return redirect()->route('training-centres.index')->with('success', __('Training centre deleted.'));
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:5000'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);
    }
}
