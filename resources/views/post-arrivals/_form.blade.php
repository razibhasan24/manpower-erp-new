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

    {{-- Arrival Date --}}
    <div class="col-md-4">
        <label for="arrival_date" class="form-label fw-semibold">Arrival Date</label>
        <input type="date" name="arrival_date" id="arrival_date"
            class="form-control @error('arrival_date') is-invalid @enderror"
            value="{{ old('arrival_date', isset($record) ? $record->arrival_date : '') }}">
        @error('arrival_date')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Airport Pickup (Modern Switch Style) --}}
    <div class="col-md-6">
        <label class="form-label fw-semibold d-block">Airport Pickup</label>
        <div class="form-check form-switch border rounded p-3 ps-5 bg-light h-100 d-flex align-items-center">
            <input type="checkbox" name="airport_pickup" id="airport_pickup" value="1"
                class="form-check-input mt-0 @error('airport_pickup') is-invalid @enderror"
                @checked(old('airport_pickup', $record->airport_pickup ?? false))>
            <label class="form-check-label fw-medium ms-2" for="airport_pickup">
                {{ __('Provide Airport Pickup') }}
            </label>
            @error('airport_pickup')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    </div>

    {{-- Status --}}
    <div class="col-md-6">
        <label for="status" class="form-label fw-semibold">Status</label>
        <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
            <option value="pending" @selected(old('status', $record->status ?? '') == 'pending')>Pending</option>
            <option value="arrived" @selected(old('status', $record->status ?? '') == 'arrived')>Arrived</option>
            <option value="in_progress" @selected(old('status', $record->status ?? '') == 'in_progress')>In Progress</option>
            <option value="completed" @selected(old('status', $record->status ?? '') == 'completed')>Completed</option>
            <option value="cancelled" @selected(old('status', $record->status ?? '') == 'cancelled')>Cancelled</option>
        </select>
        @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Employer Contact --}}
    <div class="col-md-6">
        <label for="employer_contact" class="form-label fw-semibold">Employer Contact</label>
        <input type="text" name="employer_contact" id="employer_contact"
            class="form-control @error('employer_contact') is-invalid @enderror"
            value="{{ old('employer_contact', $record->employer_contact ?? '') }}" placeholder="e.g. +971 50 123 4567">
        @error('employer_contact')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Accommodation --}}
    <div class="col-md-6">
        <label for="accommodation" class="form-label fw-semibold">Accommodation</label>
        <textarea name="accommodation" id="accommodation" rows="3"
            class="form-control @error('accommodation') is-invalid @enderror"
            placeholder="{{ __('Provide accommodation details...') }}">{{ old('accommodation', $record->accommodation ?? '') }}</textarea>
        @error('accommodation')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Candidate Feedback --}}
    <div class="col-12">
        <label for="feedback" class="form-label fw-semibold">Candidate Feedback</label>
        <textarea name="feedback" id="feedback" rows="3" class="form-control @error('feedback') is-invalid @enderror"
            placeholder="{{ __('Any feedback from the candidate after arrival...') }}">{{ old('feedback', $record->feedback ?? '') }}</textarea>
        @error('feedback')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

{{-- Action Buttons --}}
<div class="d-flex gap-2 mt-4 pt-3 border-top">
    <x-button type="submit" variant="primary" class="px-4">{{ __('Save') }}</x-button>
    <x-button href="{{ route('post-arrivals.index') }}" variant="danger">{{ __('Cancel') }}</x-button>
</div>
