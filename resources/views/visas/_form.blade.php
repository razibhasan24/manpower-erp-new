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
        <div class="mb-3">
            <label for="visa_lot_id" class="form-label">Visa Lot</label>
            <select name="visa_lot_id" id="visa_lot_id" class="form-select" required>
                <option value="">-- { __('Select') } --</option>
                @foreach($visaLots ?? [] as $option)
                    <option value="{{ $option->id }}" @selected(old('visa_lot_id', $record->visa_lot_id ?? null) == $option->id)>{{ $option->name ?? $option->title ?? $option->lot_number }}</option>
                @endforeach
            </select>
            @error('visa_lot_id') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3"><label for="submission_date" class="form-label">Submission Date</label><input type="date" name="submission_date" id="submission_date" class="form-control" value="{{ old('submission_date', isset($record) ? $record->submission_date : '') }}">@error('submission_date') <div class="text-danger small">{{ $message }}</div> @enderror</div>
        <div class="mb-3"><label for="status" class="form-label">Status</label><select name="status" id="status" class="form-select">
                    <option value="pending" @selected(old('status', $record->status ?? '') == 'pending')>Pending</option>
                    <option value="submitted" @selected(old('status', $record->status ?? '') == 'submitted')>Submitted</option>
                    <option value="approved" @selected(old('status', $record->status ?? '') == 'approved')>Approved</option>
                    <option value="rejected" @selected(old('status', $record->status ?? '') == 'rejected')>Rejected</option>
                    <option value="returned" @selected(old('status', $record->status ?? '') == 'returned')>Returned</option>
                    <option value="cancelled" @selected(old('status', $record->status ?? '') == 'cancelled')>Cancelled</option>
                </select>@error('status') <div class="text-danger small">{{ $message }}</div> @enderror</div>
        <div class="mb-3"><label for="mofa_status" class="form-label">MOFA Status</label><select name="mofa_status" id="mofa_status" class="form-select">
                    <option value="pending" @selected(old('mofa_status', $record->mofa_status ?? '') == 'pending')>Pending</option>
                    <option value="submitted" @selected(old('mofa_status', $record->mofa_status ?? '') == 'submitted')>Submitted</option>
                    <option value="approved" @selected(old('mofa_status', $record->mofa_status ?? '') == 'approved')>Approved</option>
                    <option value="rejected" @selected(old('mofa_status', $record->mofa_status ?? '') == 'rejected')>Rejected</option>
                    <option value="returned" @selected(old('mofa_status', $record->mofa_status ?? '') == 'returned')>Returned</option>
                </select>@error('mofa_status') <div class="text-danger small">{{ $message }}</div> @enderror</div>
        <div class="mb-3"><label for="remarks" class="form-label">Remarks</label><textarea name="remarks" id="remarks" class="form-control" rows="3">{{ old('remarks', $record->remarks ?? '') }}</textarea>@error('remarks') <div class="text-danger small">{{ $message }}</div> @enderror</div>
        <div class="mb-3"><label for="document_path" class="form-label">Visa Document</label><input type="file" name="document_path" id="document_path" class="form-control" accept=".pdf,.jpg,.jpeg,.png">@error('document_path') <div class="text-danger small">{{ $message }}</div> @enderror @isset($record->document_path)<div class="mt-1"><a href="{{ route('visas.download', [$record, 'document_path']) }}">{{ __('Download current file') }}</a></div>@endisset</div>
<div class="d-flex gap-2">
    <x-button type="submit">{{ __('Save') }}</x-button>
    <x-button href="{{ route('visas.index') }}" variant="secondary">{{ __('Cancel') }}</x-button>
</div>
