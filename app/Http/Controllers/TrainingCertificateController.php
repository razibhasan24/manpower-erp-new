<?php

namespace App\Http\Controllers;

use App\Models\Training;
use App\Models\TrainingCertificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class TrainingCertificateController extends Controller
{
    public function index(Request $request)
    {
        $records = TrainingCertificate::query()->whereHas('training', fn ($q) => $q->visibleTo($request->user()))
            ->with(['training.recruitment.passportHolder', 'training.course'])->latest('issued_at')
            ->paginate(20)->withQueryString();
        return view('training-certificates.index', compact('records'));
    }

    public function create(Request $request)
    {
        $trainings = $this->trainings($request);
        return view('training-certificates.create', compact('trainings'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $file = $data['file'];
        $data['file_path'] = $file->store('recruitments/'.$data['training_id'].'/certificates', 'private');
        $data['original_name'] = $file->getClientOriginalName();
        $data['mime_type'] = $file->getMimeType();
        $data['size'] = $file->getSize();
        $data['created_by'] = $data['updated_by'] = $request->user()->id;
        unset($data['file']);
        $certificate = TrainingCertificate::create($data);
        return redirect()->route('training-certificates.show', $certificate)->with('success', __('Certificate registered.'));
    }

    public function show(Request $request, TrainingCertificate $trainingCertificate)
    {
        $certificate = $this->visibleRecord($request, $trainingCertificate);
        $certificate->load(['training.recruitment.passportHolder', 'training.course']);
        return view('training-certificates.show', compact('certificate'));
    }

    public function edit(Request $request, TrainingCertificate $trainingCertificate)
    {
        $certificate = $this->visibleRecord($request, $trainingCertificate);
        $trainings = $this->trainings($request);
        return view('training-certificates.edit', compact('certificate', 'trainings'));
    }

    public function update(Request $request, TrainingCertificate $trainingCertificate)
    {
        $certificate = $this->visibleRecord($request, $trainingCertificate);
        $data = $this->validateData($request, $certificate);
        $oldPath = $certificate->file_path;
        if (isset($data['file'])) {
            $file = $data['file'];
            $data['file_path'] = $file->store('recruitments/'.$data['training_id'].'/certificates', 'private');
            $data['original_name'] = $file->getClientOriginalName();
            $data['mime_type'] = $file->getMimeType();
            $data['size'] = $file->getSize();
            unset($data['file']);
        }
        $data['updated_by'] = $request->user()->id;
        $certificate->update($data);
        if ($oldPath !== $certificate->file_path) Storage::disk('private')->delete($oldPath);
        return redirect()->route('training-certificates.show', $certificate)->with('success', __('Certificate updated.'));
    }

    public function destroy(Request $request, TrainingCertificate $trainingCertificate)
    {
        $certificate = $this->visibleRecord($request, $trainingCertificate);
        Storage::disk('private')->delete($certificate->file_path);
        $certificate->delete();
        return redirect()->route('training-certificates.index')->with('success', __('Certificate deleted.'));
    }

    public function download(Request $request, TrainingCertificate $trainingCertificate)
    {
        $certificate = $this->visibleRecord($request, $trainingCertificate);
        abort_unless(Storage::disk('private')->exists($certificate->file_path), 404);
        return Storage::disk('private')->download($certificate->file_path, $certificate->original_name ?? basename($certificate->file_path));
    }

    private function trainings(Request $request)
    {
        return Training::query()->visibleTo($request->user())->with(['recruitment.passportHolder', 'course'])->latest()->get();
    }

    private function visibleRecord(Request $request, TrainingCertificate $certificate): TrainingCertificate
    {
        abort_unless(TrainingCertificate::whereKey($certificate->id)->whereHas('training', fn ($q) => $q->visibleTo($request->user()))->exists(), 404);
        return $certificate;
    }

    private function validateData(Request $request, ?TrainingCertificate $certificate = null): array
    {
        $trainingIds = Training::query()->visibleTo($request->user())->select('id');
        return $request->validate([
            'training_id' => ['required', 'integer', 'exists:trainings,id', function ($attribute, $value, $fail) use ($trainingIds) {
                if (!(clone $trainingIds)->whereKey($value)->exists()) $fail(__('Select a training record you can access.'));
            }],
            'certificate_number' => ['nullable', 'string', 'max:100', Rule::unique('training_certificates')->ignore($certificate?->id)],
            'issued_at' => ['required', 'date'],
            'file' => [$certificate ? 'nullable' : 'required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
        ]);
    }
}
