@csrf
        <div class="mb-3">
            <label for="visa_id" class="form-label">Visa</label>
            <select name="visa_id" id="visa_id" class="form-select" required>
                <option value="">-- { __('Select visa') } --</option>
                @foreach($visas ?? [] as $visa)
                    <option value="{{ $visa->id }}" @selected(old('visa_id', $record->visa_id ?? null) == $visa->id)>{{ $visa->recruitment?->tracking_number }} ({{ $visa->status }})</option>
                @endforeach
            </select>
            @error('visa_id') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3"><label for="return_date" class="form-label">Return Date</label><input type="date" name="return_date" id="return_date" class="form-control" value="{{ old('return_date', isset($record) ? $record->return_date : '') }}">@error('return_date') <div class="text-danger small">{{ $message }}</div> @enderror</div>
        <div class="mb-3"><label for="return_reason" class="form-label">Return Reason</label><textarea name="return_reason" id="return_reason" class="form-control" rows="3">{{ old('return_reason', $record->return_reason ?? '') }}</textarea>@error('return_reason') <div class="text-danger small">{{ $message }}</div> @enderror</div>
        <div class="mb-3"><label for="return_type" class="form-label">Return Type</label><select name="return_type" id="return_type" class="form-select">@foreach(['handover','courier','agent_collection','other'] as $t)<option value="{{ $t }}" @selected(old('return_type', $record->return_type ?? 'handover') == $t)>{{ ucfirst(str_replace('_', ' ', $t)) }}</option>@endforeach</select></div>
        <div class="mb-3"><label for="responsible_person" class="form-label">Responsible Person</label><input type="text" name="responsible_person" id="responsible_person" class="form-control" required value="{{ old('responsible_person', $record->responsible_person ?? '') }}">@error('responsible_person') <div class="text-danger small">{{ $message }}</div> @enderror</div>
        <div class="mb-3"><label for="received_by" class="form-label">Received By (Required)</label><input type="text" name="received_by" id="received_by" class="form-control" required value="{{ old('received_by', $record->received_by ?? '') }}">@error('received_by') <div class="text-danger small">{{ $message }}</div> @enderror</div>
        <div class="mb-3"><label for="remarks" class="form-label">Remarks</label><textarea name="remarks" id="remarks" class="form-control" rows="3">{{ old('remarks', $record->remarks ?? '') }}</textarea>@error('remarks') <div class="text-danger small">{{ $message }}</div> @enderror</div>
        <div class="mb-3"><label for="document_path" class="form-label">Return Document</label><input type="file" name="document_path" id="document_path" class="form-control" accept=".pdf,.jpg,.jpeg,.png">@error('document_path') <div class="text-danger small">{{ $message }}</div> @enderror @isset($record->document_path)<div class="mt-1"><a href="{{ route('visa-returns.download', [$record, 'document_path']) }}">{{ __('Download current file') }}</a></div>@endisset</div>
<div class="d-flex gap-2">
    <x-button type="submit">{{ __('Save') }}</x-button>
    <x-button href="{{ route('visa-returns.index') }}" variant="secondary">{{ __('Cancel') }}</x-button>
</div>
