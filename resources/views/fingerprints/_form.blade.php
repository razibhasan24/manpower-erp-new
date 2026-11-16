@csrf
        <div class="mb-3">
            <label for="recruitment_id" class="form-label">Recruitment</label>
            <select name="recruitment_id" id="recruitment_id" class="form-select" required>
                <option value="">-- { __('Select recruitment') } --</option>
                @foreach($recruitments as $recruitment)
                    <option value="{{ $recruitment->id }}" @selected(old('recruitment_id', $record->recruitment_id ?? null) == $recruitment->id)>{{ $recruitment->tracking_number }} — {{ $recruitment->passportHolder?->full_name }}</option>
                @endforeach
            </select>
            @error('recruitment_id') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3"><label for="appointment_date" class="form-label">Appointment Date</label><input type="date" name="appointment_date" id="appointment_date" class="form-control" value="{{ old('appointment_date', isset($record) ? $record->appointment_date : '') }}">@error('appointment_date') <div class="text-danger small">{{ $message }}</div> @enderror</div>
        <div class="mb-3"><label for="submission_date" class="form-label">Submission Date</label><input type="date" name="submission_date" id="submission_date" class="form-control" value="{{ old('submission_date', isset($record) ? $record->submission_date : '') }}">@error('submission_date') <div class="text-danger small">{{ $message }}</div> @enderror</div>
        <div class="mb-3"><label for="status" class="form-label">Status</label><select name="status" id="status" class="form-select">
                    <option value="pending" @selected(old('status', $record->status ?? '') == 'pending')>Pending</option>
                    <option value="appointed" @selected(old('status', $record->status ?? '') == 'appointed')>Appointed</option>
                    <option value="submitted" @selected(old('status', $record->status ?? '') == 'submitted')>Submitted</option>
                    <option value="completed" @selected(old('status', $record->status ?? '') == 'completed')>Completed</option>
                    <option value="rejected" @selected(old('status', $record->status ?? '') == 'rejected')>Rejected</option>
                    <option value="cancelled" @selected(old('status', $record->status ?? '') == 'cancelled')>Cancelled</option>
                </select>@error('status') <div class="text-danger small">{{ $message }}</div> @enderror</div>
        <div class="mb-3"><label for="reference_number" class="form-label">Reference Number</label><input type="text" name="reference_number" id="reference_number" class="form-control" required value="{{ old('reference_number', $record->reference_number ?? '') }}">@error('reference_number') <div class="text-danger small">{{ $message }}</div> @enderror</div>
        <div class="mb-3"><label for="remarks" class="form-label">Remarks</label><textarea name="remarks" id="remarks" class="form-control" rows="3">{{ old('remarks', $record->remarks ?? '') }}</textarea>@error('remarks') <div class="text-danger small">{{ $message }}</div> @enderror</div>
        <div class="mb-3"><label for="document_path" class="form-label">Fingerprint Document</label><input type="file" name="document_path" id="document_path" class="form-control" accept=".pdf,.jpg,.jpeg,.png">@error('document_path') <div class="text-danger small">{{ $message }}</div> @enderror @isset($record->document_path)<div class="mt-1"><a href="{{ route('fingerprints.download', [$record, 'document_path']) }}">{{ __('Download current file') }}</a></div>@endisset</div>
<div class="d-flex gap-2">
    <x-button type="submit">{{ __('Save') }}</x-button>
    <x-button href="{{ route('fingerprints.index') }}" variant="secondary">{{ __('Cancel') }}</x-button>
</div>
