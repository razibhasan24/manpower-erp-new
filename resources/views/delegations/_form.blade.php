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
            <option value="approved" @selected(old('status', $record->status ?? '') == 'approved')>Approved</option>
            <option value="rejected" @selected(old('status', $record->status ?? '') == 'rejected')>Rejected</option>
            <option value="returned" @selected(old('status', $record->status ?? '') == 'returned')>Returned</option>
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

    {{-- Delegation Document --}}
    <div class="col-md-6">
        <label for="document_path" class="form-label fw-semibold">Delegation Document</label>
        <input type="file" name="document" id="document_path"
            class="form-control @error('document') is-invalid @enderror" accept=".pdf,.jpg,.jpeg,.png">
        @error('document')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror

        @isset($record->document_path)
            <div class="mt-2">
                <a href="{{ route('delegations.download', [$record, 'document_path']) }}"
                    class="text-decoration-none small fw-medium">
                    <i class="bi bi-download me-1"></i> {{ __('Download current file') }}
                </a>
            </div>
        @endisset
    </div>

    {{-- E-Duty Document --}}
    <div class="col-md-6">
        <label for="e_duty_document_path" class="form-label fw-semibold">E-Duty Document</label>
        <input type="file" name="e_duty_document" id="e_duty_document_path"
            class="form-control @error('e_duty_document') is-invalid @enderror" accept=".pdf,.jpg,.jpeg,.png">
        @error('e_duty_document')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror

        @isset($record->e_duty_document_path)
            <div class="mt-2">
                <a href="{{ route('delegations.download', [$record, 'e_duty_document_path']) }}"
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
    <x-button href="{{ route('delegations.index') }}" variant="danger">{{ __('Cancel') }}</x-button>
</div>
