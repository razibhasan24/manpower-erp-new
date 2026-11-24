<div class="row g-4">
    {{-- Course Title --}}
    <div class="col-md-6">
        <label class="form-label fw-semibold" for="title">{{ __('Course Title') }} <span
                class="text-danger">*</span></label>
        <input id="title" name="title" required maxlength="255" value="{{ old('title', $record->title) }}"
            class="form-control @error('title') is-invalid @enderror" placeholder="e.g. Basic Computer Skills">
        @error('title')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Training Center --}}
    <div class="col-md-6">
        <label class="form-label fw-semibold" for="training_centre_id">{{ __('Training Center') }}</label>
        <select id="training_centre_id" name="training_centre_id"
            class="form-select @error('training_centre_id') is-invalid @enderror">
            <option value="">{{ __('Select a center (optional)') }}</option>
            @foreach ($centres as $centre)
                <option value="{{ $centre->id }}" @selected((string) old('training_centre_id', $record->training_centre_id) === (string) $centre->id)>
                    {{ $centre->name }}
                </option>
            @endforeach
        </select>
        @error('training_centre_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Duration --}}
    <div class="col-md-4">
        <label class="form-label fw-semibold" for="duration_days">{{ __('Duration (days)') }}</label>
        <input id="duration_days" type="number" min="1" name="duration_days"
            value="{{ old('duration_days', $record->duration_days) }}"
            class="form-control @error('duration_days') is-invalid @enderror" placeholder="e.g. 30">
        @error('duration_days')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Fee --}}
    <div class="col-md-4">
        <label class="form-label fw-semibold" for="fee">{{ __('Fee') }}</label>
        <input id="fee" type="number" min="0" step="0.01" name="fee"
            value="{{ old('fee', $record->fee) }}" class="form-control @error('fee') is-invalid @enderror"
            placeholder="e.g. 5000.00">
        @error('fee')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Status --}}
    <div class="col-md-4">
        <label class="form-label fw-semibold" for="status">{{ __('Status') }}</label>
        <select id="status" name="status" class="form-select @error('status') is-invalid @enderror">
            @foreach (['active' => __('Active'), 'inactive' => __('Inactive')] as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $record->status ?: 'active') === $value)>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
