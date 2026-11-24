@csrf

<div class="row g-4">
    {{-- Recruitment --}}
    <div class="col-md-7">
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

    {{-- Medical Centre --}}
    <div class="col-md-5">
        <label for="medical_centre_id" class="form-label fw-semibold">Medical Centre <span
                class="text-danger">*</span></label>
        <select name="medical_centre_id" id="medical_centre_id"
            class="form-select @error('medical_centre_id') is-invalid @enderror" required>
            <option value="">-- {{ __('Select') }} --</option>
            @foreach ($medicalCentres ?? [] as $option)
                <option value="{{ $option->id }}" @selected(old('medical_centre_id', $record->medical_centre_id ?? null) == $option->id)>
                    {{ $option->name ?? ($option->title ?? $option->lot_number) }}
                </option>
            @endforeach
        </select>
        @error('medical_centre_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Medical Date --}}
    <div class="col-md-6">
        <label for="medical_date" class="form-label fw-semibold">Medical Date</label>
        <input type="date" name="medical_date" id="medical_date"
            class="form-control @error('medical_date') is-invalid @enderror"
            value="{{ old('medical_date', $record->medical_date ?? '') }}">
        @error('medical_date')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Result --}}
    <div class="col-md-6">
        <label for="result" class="form-label fw-semibold">Result</label>
        <select name="result" id="result" class="form-select @error('result') is-invalid @enderror">
            <option value="pending" @selected(old('result', $record->result ?? '') == 'pending')>Pending</option>
            <option value="fit" @selected(old('result', $record->result ?? '') == 'fit')>Fit</option>
            <option value="unfit" @selected(old('result', $record->result ?? '') == 'unfit')>Unfit</option>
            <option value="expired" @selected(old('result', $record->result ?? '') == 'expired')>Expired</option>
            <option value="cancelled" @selected(old('result', $record->result ?? '') == 'cancelled')>Cancelled</option>
        </select>
        @error('result')
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

    {{-- Medical Report File Upload --}}
    <div class="col-12">
        <label for="report_path" class="form-label fw-semibold">Medical Report</label>
        <input type="file" name="report_path" id="report_path"
            class="form-control @error('report_path') is-invalid @enderror" accept=".pdf,.jpg,.jpeg,.png">
        @error('report_path')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror

        @isset($record->report_path)
            <div class="mt-2">
                <a href="{{ route('medicals.download', [$record, 'report_path']) }}"
                    class="text-decoration-none small fw-medium">
                    <i class="bi bi-download me-1"></i> {{ __('Download current file') }}
                </a>
            </div>
        @endisset
    </div>
</div>

{{-- Action Buttons --}}
<div class="d-flex gap-2 mt-4 pt-3 border-top">
    <x-button type="submit" variant="success" class="px-4">{{ __('Save') }}</x-button>
    <x-button href="{{ route('medicals.index') }}" variant="danger">{{ __('Cancel') }}</x-button>
</div>
