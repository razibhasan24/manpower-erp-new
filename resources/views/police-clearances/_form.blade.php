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
                    <option value="cleared" @selected(old('status', $record->status ?? '') == 'cleared')>Cleared</option>
                    <option value="uncleared" @selected(old('status', $record->status ?? '') == 'uncleared')>Uncleared</option>
                    <option value="returned" @selected(old('status', $record->status ?? '') == 'returned')>Returned</option>
                    <option value="cancelled" @selected(old('status', $record->status ?? '') == 'cancelled')>Cancelled</option>
                </select>@error('status') <div class="text-danger small">{{ $message }}</div> @enderror</div>
        <div class="mb-3"><label for="issue_date" class="form-label">Issue Date</label><input type="date" name="issue_date" id="issue_date" class="form-control" value="{{ old('issue_date', isset($record) ? $record->issue_date : '') }}">@error('issue_date') <div class="text-danger small">{{ $message }}</div> @enderror</div>
        <div class="mb-3"><label for="expiry_date" class="form-label">Expiry Date</label><input type="date" name="expiry_date" id="expiry_date" class="form-control" value="{{ old('expiry_date', isset($record) ? $record->expiry_date : '') }}">@error('expiry_date') <div class="text-danger small">{{ $message }}</div> @enderror</div>
        <div class="mb-3"><label for="remarks" class="form-label">Remarks</label><textarea name="remarks" id="remarks" class="form-control" rows="3">{{ old('remarks', $record->remarks ?? '') }}</textarea>@error('remarks') <div class="text-danger small">{{ $message }}</div> @enderror</div>
        <div class="mb-3"><label for="certificate_path" class="form-label">Clearance Certificate</label><input type="file" name="certificate_path" id="certificate_path" class="form-control" accept=".pdf,.jpg,.jpeg,.png">@error('certificate_path') <div class="text-danger small">{{ $message }}</div> @enderror @isset($record->certificate_path)<div class="mt-1"><a href="{{ route('police-clearances.download', [$record, 'certificate_path']) }}">{{ __('Download current file') }}</a></div>@endisset</div>
<div class="d-flex gap-2">
    <x-button type="submit">{{ __('Save') }}</x-button>
    <x-button href="{{ route('police-clearances.index') }}" variant="secondary">{{ __('Cancel') }}</x-button>
</div>
