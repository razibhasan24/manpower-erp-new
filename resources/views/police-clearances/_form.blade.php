@csrf

<div class="row g-4">
    {{-- Recruitment --}}
    <div class="col-md-8">
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

    {{-- Status --}}
    <div class="col-md-4">
        <label for="status" class="form-label fw-semibold">Status</label>
        <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
            <option value="pending" @selected(old('status', $record->status ?? '') == 'pending')>Pending</option>
            <option value="cleared" @selected(old('status', $record->status ?? '') == 'cleared')>Cleared</option>
            <option value="uncleared" @selected(old('status', $record->status ?? '') == 'uncleared')>Uncleared</option>
            <option value="returned" @selected(old('status', $record->status ?? '') == 'returned')>Returned</option>
            <option value="cancelled" @selected(old('status', $record->status ?? '') == 'cancelled')>Cancelled</option>
        </select>
        @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Issue Date --}}
    <div class="col-md-6">
        <label for="issue_date" class="form-label fw-semibold">Issue Date</label>
        <input type="date" name="issue_date" id="issue_date"
            class="form-control @error('issue_date') is-invalid @enderror"
            value="{{ old('issue_date', $record->issue_date ?? '') }}">
        @error('issue_date')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Expiry Date --}}
    <div class="col-md-6">
        <label for="expiry_date" class="form-label fw-semibold">Expiry Date</label>
        <input type="date" name="expiry_date" id="expiry_date"
            class="form-control @error('expiry_date') is-invalid @enderror"
            value="{{ old('expiry_date', $record->expiry_date ?? '') }}">
        @error('expiry_date')
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

    {{-- Clearance Certificate File Upload --}}
    <div class="col-12">
        <label for="certificate_path" class="form-label fw-semibold">Clearance Certificate</label>
        <input type="file" name="certificate_path" id="certificate_path"
            class="form-control @error('certificate_path') is-invalid @enderror" accept=".pdf,.jpg,.jpeg,.png">
        @error('certificate_path')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror

        @isset($record->certificate_path)
            <div class="mt-2">
                <a href="{{ route('police-clearances.download', [$record, 'certificate_path']) }}"
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
    <x-button href="{{ route('police-clearances.index') }}" variant="danger">{{ __('Cancel') }}</x-button>
</div>
