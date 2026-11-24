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

    {{-- Return Date --}}
    <div class="col-md-4">
        <label for="return_date" class="form-label fw-semibold">Return Date</label>
        <input type="date" name="return_date" id="return_date"
            class="form-control @error('return_date') is-invalid @enderror"
            value="{{ old('return_date', isset($record) ? $record->return_date : '') }}">
        @error('return_date')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Return Type --}}
    <div class="col-md-4">
        <label for="return_type" class="form-label fw-semibold">Return Type</label>
        <select name="return_type" id="return_type" class="form-select @error('return_type') is-invalid @enderror">
            @foreach (['handover', 'courier', 'agent_collection', 'other'] as $t)
                <option value="{{ $t }}" @selected(old('return_type', $record->return_type ?? 'handover') == $t)>
                    {{ ucfirst(str_replace('_', ' ', $t)) }}
                </option>
            @endforeach
        </select>
        @error('return_type')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Responsible Person --}}
    <div class="col-md-4">
        <label for="responsible_person" class="form-label fw-semibold">Responsible Person <span
                class="text-danger">*</span></label>
        <input type="text" name="responsible_person" id="responsible_person"
            class="form-control @error('responsible_person') is-invalid @enderror" required
            value="{{ old('responsible_person', $record->responsible_person ?? '') }}" placeholder="e.g. John Doe">
        @error('responsible_person')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Received By --}}
    <div class="col-md-4">
        <label for="received_by" class="form-label fw-semibold">Received By <span class="text-danger">*</span></label>
        <input type="text" name="received_by" id="received_by"
            class="form-control @error('received_by') is-invalid @enderror" required
            value="{{ old('received_by', $record->received_by ?? '') }}" placeholder="Name of receiver">
        @error('received_by')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Delivered To --}}
    <div class="col-md-6">
        <label for="delivered_to" class="form-label fw-semibold">Delivered To</label>
        <input type="text" name="delivered_to" id="delivered_to"
            class="form-control @error('delivered_to') is-invalid @enderror"
            value="{{ old('delivered_to', $record->delivered_to ?? '') }}"
            placeholder="Name of the person delivered to">
        @error('delivered_to')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Identity Verified By --}}
    <div class="col-md-6">
        <label for="identity_verified_by" class="form-label fw-semibold">Identity Verified By</label>
        <input type="text" name="identity_verified_by" id="identity_verified_by"
            class="form-control @error('identity_verified_by') is-invalid @enderror"
            value="{{ old('identity_verified_by', $record->identity_verified_by ?? '') }}"
            placeholder="Name of verifier">
        @error('identity_verified_by')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Return Reason --}}
    <div class="col-12">
        <label for="return_reason" class="form-label fw-semibold">Return Reason</label>
        <textarea name="return_reason" id="return_reason" rows="2"
            class="form-control @error('return_reason') is-invalid @enderror"
            placeholder="{{ __('Why is the passport being returned?') }}">{{ old('return_reason', $record->return_reason ?? '') }}</textarea>
        @error('return_reason')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Remarks --}}
    <div class="col-12">
        <label for="remarks" class="form-label fw-semibold">Remarks</label>
        <textarea name="remarks" id="remarks" rows="2" class="form-control @error('remarks') is-invalid @enderror"
            placeholder="{{ __('Write any additional notes here...') }}">{{ old('remarks', $record->remarks ?? '') }}</textarea>
        @error('remarks')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Acknowledgement File Upload --}}
    <div class="col-12">
        <label for="acknowledgement_path" class="form-label fw-semibold">Acknowledgement Document</label>
        <input type="file" name="acknowledgement_path" id="acknowledgement_path"
            class="form-control @error('acknowledgement_path') is-invalid @enderror" accept=".pdf,.jpg,.jpeg,.png">
        @error('acknowledgement_path')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror

        @isset($record->acknowledgement_path)
            <div class="mt-2">
                <a href="{{ route('passport-returns.download', [$record, 'acknowledgement_path']) }}"
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
    <x-button href="{{ route('passport-returns.index') }}" variant="danger">{{ __('Cancel') }}</x-button>
</div>
