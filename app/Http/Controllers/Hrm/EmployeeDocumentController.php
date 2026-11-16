<?php

namespace App\Http\Controllers\Hrm;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\EmployeeDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Employee documents (Phase 26). Files live on the private disk and are
 * served only through an authorised download — never a public URL.
 * Note: the employee_documents table is assumed (it is not listed in
 * Phase 01 §6.5); see BUILD-LOG-D for the gap.
 */
class EmployeeDocumentController extends Controller
{
    public function store(Request $request, Employee $employee)
    {
        $this->authorize('update', $employee);

        $data = $request->validate([
            'type' => ['required', 'string', 'max:100'],
            'file' => ['required', 'file', 'max:10240', 'mimes:pdf,jpg,jpeg,png'],
        ]);

        $path = $request->file('file')->store("employees/{$employee->id}", 'private');

        $document = EmployeeDocument::create([
            'employee_id' => $employee->id,
            'type' => $data['type'],
            'file_path' => $path,
            'original_name' => $request->file('file')->getClientOriginalName(),
            'uploaded_by' => auth()->id(),
        ]);

        return back()->with('success', 'Document uploaded.');
    }

    public function download(Employee $employee, EmployeeDocument $document)
    {
        $this->authorize('view', $employee);

        abort_unless($document->employee_id === $employee->id, 404);

        return Storage::disk('private')->download($document->file_path, $document->original_name);
    }

    public function destroy(Employee $employee, EmployeeDocument $document)
    {
        $this->authorize('update', $employee);

        abort_unless($document->employee_id === $employee->id, 404);

        Storage::disk('private')->delete($document->file_path);
        $document->delete();

        return back()->with('success', 'Document deleted.');
    }
}
