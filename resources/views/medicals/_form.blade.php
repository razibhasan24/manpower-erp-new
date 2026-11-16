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
            <label for="medical_centre_id" class="form-label">Medical Centre</label>
            <select name="medical_centre_id" id="medical_centre_id" class="form-select" required>
                <option value="">-- { __('Select') } --</option>
                @foreach($medicalCentres ?? [] as $option)
                    <option value="{{ $option->id }}" @selected(old('medical_centre_id', $record->medical_centre_id ?? null) == $option->id)>{{ $option->name ?? $option->title ?? $option->lot_number }}</option>
                @endforeach
            </select>
            @error('medical_centre_id') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3"><label for="medical_date" class="form-label">Medical Date</label><input type="date" name="medical_date" id="medical_date" class="form-control" value="{{ old('medical_date', isset($record) ? $record->medical_date : '') }}">@error('medical_date') <div class="text-danger small">{{ $message }}</div> @enderror</div>
        <div class="mb-3"><label for="result" class="form-label">Result</label><select name="result" id="result" class="form-select">
                    <option value="pending" @selected(old('result', $record->result ?? '') == 'pending')>Pending</option>
                    <option value="fit" @selected(old('result', $record->result ?? '') == 'fit')>Fit</option>
                    <option value="unfit" @selected(old('result', $record->result ?? '') == 'unfit')>Unfit</option>
                    <option value="expired" @selected(old('result', $record->result ?? '') == 'expired')>Expired</option>
                    <option value="cancelled" @selected(old('result', $record->result ?? '') == 'cancelled')>Cancelled</option>
                </select>@error('result') <div class="text-danger small">{{ $message }}</div> @enderror</div>
        <div class="mb-3"><label for="remarks" class="form-label">Remarks</label><textarea name="remarks" id="remarks" class="form-control" rows="3">{{ old('remarks', $record->remarks ?? '') }}</textarea>@error('remarks') <div class="text-danger small">{{ $message }}</div> @enderror</div>
        <div class="mb-3"><label for="report_path" class="form-label">Medical Report</label><input type="file" name="report_path" id="report_path" class="form-control" accept=".pdf,.jpg,.jpeg,.png">@error('report_path') <div class="text-danger small">{{ $message }}</div> @enderror @isset($record->report_path)<div class="mt-1"><a href="{{ route('medicals.download', [$record, 'report_path']) }}">{{ __('Download current file') }}</a></div>@endisset</div>
<div class="d-flex gap-2">
    <x-button type="submit">{{ __('Save') }}</x-button>
    <x-button href="{{ route('medicals.index') }}" variant="secondary">{{ __('Cancel') }}</x-button>
</div>
