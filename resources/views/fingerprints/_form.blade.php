@csrf

<div class="row g-4">
    {{-- Recruitment --}}
    <div class="col-md-6">
        <label for="recruitment_id" class="form-label fw-semibold">Recruitment <span class="text-danger">*</span></label>
        <select name="recruitment_id" id="recruitment_id" class="form-select @error('recruitment_id') is-invalid @enderror"
            required>
            <option value="">-- {{ __('Select recruitment') }} --</option>
            @foreach ($recruitments as $recruitment)
                <option value="{{ $recruitment->id }}" @selected(old('recruitment_id', $record->recruitment_id ?? null) == $recruitment->id)>
                    {{ $recruitment->tracking_number }} — {{ $recruitment->passportHolder?->full_name }}
                </option>
            @endforeach
        </select>
        @error('recruitment_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Reference Number --}}
    <div class="col-md-6">
        <label for="reference_number" class="form-label fw-semibold">Reference Number <span
                class="text-danger">*</span></label>
        <input type="text" name="reference_number" id="reference_number"
            class="form-control @error('reference_number') is-invalid @enderror" required
            value="{{ old('reference_number', $record->reference_number ?? '') }}" placeholder="e.g. REF-123456">
        @error('reference_number')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Appointment Date --}}
    <div class="col-md-4">
        <label for="appointment_date" class="form-label fw-semibold">Appointment Date</label>
        <input type="date" name="appointment_date" id="appointment_date"
            class="form-control @error('appointment_date') is-invalid @enderror"
            value="{{ old('appointment_date', $record->appointment_date ?? '') }}">
        @error('appointment_date')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Submission Date --}}
    <div class="col-md-4">
        <label for="submission_date" class="form-label fw-semibold">Submission Date</label>
        <input type="date" name="submission_date" id="submission_date"
            class="form-control @error('submission_date') is-invalid @enderror"
            value="{{ old('submission_date', $record->submission_date ?? '') }}">
        @error('submission_date')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Status --}}
    <div class="col-md-4">
        <label for="status" class="form-label fw-semibold">Status</label>
        <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
            <option value="pending" @selected(old('status', $record->status ?? '') == 'pending')>Pending</option>
            <option value="appointed" @selected(old('status', $record->status ?? '') == 'appointed')>Appointed</option>
            <option value="submitted" @selected(old('status', $record->status ?? '') == 'submitted')>Submitted</option>
            <option value="completed" @selected(old('status', $record->status ?? '') == 'completed')>Completed</option>
            <option value="rejected" @selected(old('status', $record->status ?? '') == 'rejected')>Rejected</option>
            <option value="cancelled" @selected(old('status', $record->status ?? '') == 'cancelled')>Cancelled</option>
        </select>
        @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Remarks --}}
    <div class="col-12">
        <label for="remarks" class="form-label fw-semibold">Remarks</label>
        <textarea name="remarks" id="remarks" rows="3" class="form-control @error('remarks') is-invalid @enderror"
            placeholder="{{ __('Write any additional notes here...') }}">{{ old('remarks', $record->remarks ?? '') }}</textarea>
        @error('remarks')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Fingerprint Document --}}
    <div class="col-12">
        <label for="document_path" class="form-label fw-semibold">Fingerprint Document</label>
        <input type="file" name="document_path" id="document_path"
            class="form-control @error('document_path') is-invalid @enderror" accept=".pdf,.jpg,.jpeg,.png">
        @error('document_path')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror

        @isset($record->document_path)
            <div class="mt-2">
                <a href="{{ route('fingerprints.download', [$record, 'document_path']) }}"
                    class="text-decoration-none small fw-medium">
                    <i class="bi bi-download me-1"></i> {{ __('Download current file') }}
                </a>
            </div>
        @endisset
    </div>
</div>

{{-- Action Buttons --}}
<div class="d-flex gap-2 mt-4 pt-3 border-top">
    <x-button type="submit" variant="primary" class="px-4">{{ __('Save') }}</x-button>
    <x-button href="{{ route('fingerprints.index') }}" variant="danger">{{ __('Cancel') }}</x-button>
</div>
