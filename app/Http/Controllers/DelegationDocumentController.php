<?php

namespace App\Http\Controllers;

use App\Models\Delegation;
use App\Models\DelegationDocument;
use App\Models\Recruitment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DelegationDocumentController extends Controller
{
    public function index(Request $request)
    {
        $records = DelegationDocument::query()
            ->visibleTo($request->user())
            ->with(['delegation.recruitment.passportHolder', 'uploader'])
            ->latest()
            ->paginate(15)->withQueryString();

        return view('delegation-documents.index', compact('records'));
    }

    public function create(Request $request)
    {
        $delegations = Delegation::visibleTo($request->user())
            ->with('recruitment.passportHolder')->latest()->get();

        return view('delegation-documents.create', compact('delegations'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $file = $data['file'];
        $path = $file->store('recruitments/'.$data['delegation_id'].'/delegation', 'private');
        $document = DelegationDocument::create([
            'delegation_id' => $data['delegation_id'],
            'type' => $data['type'],
            'file_path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'uploaded_by' => $request->user()->id,
            'created_by' => $request->user()->id,
            'updated_by' => $request->user()->id,
        ]);

        return redirect()->route('delegation-documents.show', $document)->with('success', __('Document uploaded.'));
    }

    public function show(Request $request, DelegationDocument $delegationDocument)
    {
        $document = $this->visibleDocument($request, $delegationDocument);
        $document->load(['delegation.recruitment.passportHolder', 'uploader']);

        return view('delegation-documents.show', compact('document'));
    }

    public function edit(Request $request, DelegationDocument $delegationDocument)
    {
        $document = $this->visibleDocument($request, $delegationDocument);
        $delegations = Delegation::visibleTo($request->user())
            ->with('recruitment.passportHolder')->latest()->get();

        return view('delegation-documents.edit', compact('document', 'delegations'));
    }

    public function update(Request $request, DelegationDocument $delegationDocument)
    {
        $document = $this->visibleDocument($request, $delegationDocument);
        $data = $this->validated($request, true);
        $oldPath = $document->file_path;
        $document->delegation_id = $data['delegation_id'];
        $document->type = $data['type'];
        $document->updated_by = $request->user()->id;

        if (isset($data['file'])) {
            $file = $data['file'];
            $document->file_path = $file->store('recruitments/'.$data['delegation_id'].'/delegation', 'private');
            $document->original_name = $file->getClientOriginalName();
            $document->mime_type = $file->getMimeType();
            $document->size = $file->getSize();
        }

        $document->save();
        if (isset($data['file']) && $oldPath !== $document->file_path) {
            Storage::disk('private')->delete($oldPath);
        }

        return redirect()->route('delegation-documents.show', $document)->with('success', __('Document updated.'));
    }

    public function destroy(Request $request, DelegationDocument $delegationDocument)
    {
        $document = $this->visibleDocument($request, $delegationDocument);
        $document->delete();

        return redirect()->route('delegation-documents.index')->with('success', __('Document archived.'));
    }

    public function download(Request $request, DelegationDocument $delegationDocument)
    {
        $document = $this->visibleDocument($request, $delegationDocument);
        abort_unless(Storage::disk('private')->exists($document->file_path), 404);

        return Storage::disk('private')->download($document->file_path, $document->original_name ?? basename($document->file_path));
    }

    private function visibleDocument(Request $request, DelegationDocument $document): DelegationDocument
    {
        abort_unless(DelegationDocument::query()->visibleTo($request->user())->whereKey($document->id)->exists(), 404);
        return $document;
    }

    private function validated(Request $request, bool $fileOptional = false): array
    {
        $delegationIds = Delegation::visibleTo($request->user())->select('id');

        return $request->validate([
            'delegation_id' => ['required', 'integer', 'exists:delegations,id', function ($attribute, $value, $fail) use ($delegationIds) {
                if (!(clone $delegationIds)->whereKey($value)->exists()) {
                    $fail(__('Select a delegation record you can access.'));
                }
            }],
            'type' => ['required', 'in:document,e_duty'],
            'file' => [$fileOptional ? 'nullable' : 'required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
        ]);
    }
}
