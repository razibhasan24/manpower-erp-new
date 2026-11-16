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
        <div class="mb-3"><label for="status" class="form-label">Status</label><select name="status" id="status" class="form-select">
                    <option value="pending" @selected(old('status', $record->status ?? '') == 'pending')>Pending</option>
                    <option value="approved" @selected(old('status', $record->status ?? '') == 'approved')>Approved</option>
                    <option value="rejected" @selected(old('status', $record->status ?? '') == 'rejected')>Rejected</option>
                    <option value="returned" @selected(old('status', $record->status ?? '') == 'returned')>Returned</option>
                    <option value="cancelled" @selected(old('status', $record->status ?? '') == 'cancelled')>Cancelled</option>
                </select>@error('status') <div class="text-danger small">{{ $message }}</div> @enderror</div>
        <div class="mb-3"><label for="remarks" class="form-label">Remarks</label><textarea name="remarks" id="remarks" class="form-control" rows="3">{{ old('remarks', $record->remarks ?? '') }}</textarea>@error('remarks') <div class="text-danger small">{{ $message }}</div> @enderror</div>
        <div class="mb-3"><label for="document" class="form-label">Delegation Document</label><input type="file" name="document" id="document_path" class="form-control" accept=".pdf,.jpg,.jpeg,.png">@error('document') <div class="text-danger small">{{ $message }}</div> @enderror @isset($record->document_path)<div class="mt-1"><a href="{{ route('delegations.download', [$record, 'document_path']) }}">{{ __('Download current file') }}</a></div>@endisset</div>
        <div class="mb-3"><label for="e_duty_document" class="form-label">E-Duty Document</label><input type="file" name="e_duty_document" id="e_duty_document_path" class="form-control" accept=".pdf,.jpg,.jpeg,.png">@error('e_duty_document') <div class="text-danger small">{{ $message }}</div> @enderror @isset($record->e_duty_document_path)<div class="mt-1"><a href="{{ route('delegations.download', [$record, 'e_duty_document_path']) }}">{{ __('Download current file') }}</a></div>@endisset</div>
<div class="d-flex gap-2">
    <x-button type="submit">{{ __('Save') }}</x-button>
    <x-button href="{{ route('delegations.index') }}" variant="secondary">{{ __('Cancel') }}</x-button>
</div>
