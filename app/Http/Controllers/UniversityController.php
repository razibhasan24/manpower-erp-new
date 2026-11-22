<?php

namespace App\Http\Controllers;

use App\Models\University;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UniversityController extends Controller
{
    public function index(Request $request)
    {
        abort_unless($request->user()->can('student-package.view'), 403);
        $universities = University::query()
            ->when($request->filled('search'), fn ($query) => $query->where('name', 'like', '%'.$request->string('search').'%')->orWhere('code', 'like', '%'.$request->string('search').'%'))
            ->orderBy('name')->paginate(20)->withQueryString();

        return view('universities.index', compact('universities'));
    }

    public function create(Request $request)
    {
        abort_unless($request->user()->can('student-package.create'), 403);
        return view('universities.form', ['university' => new University(['is_active' => true])]);
    }

    public function store(Request $request)
    {
        abort_unless($request->user()->can('student-package.create'), 403);
        University::create($this->validated($request));
        return redirect()->route('universities.index')->with('success', 'University added.');
    }

    public function edit(Request $request, University $university)
    {
        abort_unless($request->user()->can('student-package.edit'), 403);
        return view('universities.form', compact('university'));
    }

    public function update(Request $request, University $university)
    {
        abort_unless($request->user()->can('student-package.edit'), 403);
        $university->update($this->validated($request, $university));
        return redirect()->route('universities.index')->with('success', 'University updated.');
    }

    public function destroy(Request $request, University $university)
    {
        abort_unless($request->user()->can('student-package.delete'), 403);
        $university->delete();
        return redirect()->route('universities.index')->with('success', 'University archived.');
    }

    private function validated(Request $request, ?University $university = null): array
    {
        return $request->validate([
            'code' => ['required', 'string', 'max:40', Rule::unique('universities', 'code')->ignore($university?->id)],
            'name' => ['required', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:120'],
            'city' => ['nullable', 'string', 'max:120'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:50'],
            'is_active' => ['required', 'boolean'],
        ]);
    }
}
