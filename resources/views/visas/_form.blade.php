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

    {{-- Visa Lot --}}
    <div class="col-md-6">
        <label for="visa_lot_id" class="form-label fw-semibold">Visa Lot <span class="text-danger">*</span></label>
        <select name="visa_lot_id" id="visa_lot_id" class="form-select @error('visa_lot_id') is-invalid @enderror"
            required>
            <option value="">-- {{ __('Select') }} --</option>
            @foreach ($visaLots ?? [] as $option)
                <option value="{{ $option->id }}" @selected(old('visa_lot_id', $record->visa_lot_id ?? null) == $option->id)>
                    {{ $option->name ?? ($option->title ?? $option->lot_number) }}
                </option>
            @endforeach
        </select>
        @error('visa_lot_id')
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
            <option value="submitted" @selected(old('status', $record->status ?? '') == 'submitted')>Submitted</option>
            <option value="approved" @selected(old('status', $record->status ?? '') == 'approved')>Approved</option>
            <option value="rejected" @selected(old('status', $record->status ?? '') == 'rejected')>Rejected</option>
            <option value="returned" @selected(old('status', $record->status ?? '') == 'returned')>Returned</option>
            <option value="cancelled" @selected(old('status', $record->status ?? '') == 'cancelled')>Cancelled</option>
        </select>
        @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- MOFA Status --}}
    <div class="col-md-4">
        <label for="mofa_status" class="form-label fw-semibold">MOFA Status</label>
        <select name="mofa_status" id="mofa_status" class="form-select @error('mofa_status') is-invalid @enderror">
            <option value="pending" @selected(old('mofa_status', $record->mofa_status ?? '') == 'pending')>Pending</option>
            <option value="submitted" @selected(old('mofa_status', $record->mofa_status ?? '') == 'submitted')>Submitted</option>
            <option value="approved" @selected(old('mofa_status', $record->mofa_status ?? '') == 'approved')>Approved</option>
            <option value="rejected" @selected(old('mofa_status', $record->mofa_status ?? '') == 'rejected')>Rejected</option>
            <option value="returned" @selected(old('mofa_status', $record->mofa_status ?? '') == 'returned')>Returned</option>
        </select>
        @error('mofa_status')
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

    {{-- Visa Document File Upload --}}
    <div class="col-12">
        <label for="document_path" class="form-label fw-semibold">Visa Document</label>
        <input type="file" name="document_path" id="document_path"
            class="form-control @error('document_path') is-invalid @enderror" accept=".pdf,.jpg,.jpeg,.png">
        @error('document_path')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror

        @isset($record->document_path)
            <div class="mt-2">
                <a href="{{ route('visas.download', [$record, 'document_path']) }}"
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
    <x-button href="{{ route('visas.index') }}" variant="secondary">{{ __('Cancel') }}</x-button>
</div>
