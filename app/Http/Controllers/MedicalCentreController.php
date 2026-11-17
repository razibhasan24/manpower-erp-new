<?php

namespace App\Http\Controllers;

use App\Models\MedicalCentre;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MedicalCentreController extends Controller
{
    public function index(Request $request)
    {
        $centres = MedicalCentre::query()
            ->when($request->filled('search'), fn ($query) => $query->where('name', 'like', '%'.$request->string('search').'%'))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->orderBy('name')->paginate(15)->withQueryString();

        return view('medical-centres.index', compact('centres'));
    }

    public function create()
    {
        return view('medical-centres.create', ['record' => new MedicalCentre]);
    }

    public function store(Request $request)
    {
        $record = MedicalCentre::create($this->validated($request));

        return redirect()->route('medical-centres.index')->with('success', __('Medical centre created.'));
    }

    public function edit(MedicalCentre $medicalCentre)
    {
        return view('medical-centres.edit', ['record' => $medicalCentre]);
    }

    public function update(Request $request, MedicalCentre $medicalCentre)
    {
        $medicalCentre->update($this->validated($request));

        return redirect()->route('medical-centres.index')->with('success', __('Medical centre updated.'));
    }

    public function destroy(MedicalCentre $medicalCentre)
    {
        $medicalCentre->delete();

        return redirect()->route('medical-centres.index')->with('success', __('Medical centre deleted.'));
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
