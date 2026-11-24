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

    {{-- Training Centre --}}
    <div class="col-md-6">
        <label for="training_centre_id" class="form-label fw-semibold">Training Centre <span
                class="text-danger">*</span></label>
        <select name="training_centre_id" id="training_centre_id"
            class="form-select @error('training_centre_id') is-invalid @enderror" required>
            <option value="">-- {{ __('Select') }} --</option>
            @foreach ($trainingCentres ?? [] as $option)
                <option value="{{ $option->id }}" @selected(old('training_centre_id', $record->training_centre_id ?? null) == $option->id)>
                    {{ $option->name ?? ($option->title ?? $option->lot_number) }}
                </option>
            @endforeach
        </select>
        @error('training_centre_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Training Course --}}
    <div class="col-md-6">
        <label for="course_id" class="form-label fw-semibold">Training Course <span class="text-danger">*</span></label>
        <select name="course_id" id="course_id" class="form-select @error('course_id') is-invalid @enderror" required>
            <option value="">-- {{ __('Select') }} --</option>
            @foreach ($trainingCourses ?? [] as $option)
                <option value="{{ $option->id }}" @selected(old('course_id', $record->course_id ?? null) == $option->id)>
                    {{ $option->name ?? ($option->title ?? $option->lot_number) }}
                </option>
            @endforeach
        </select>
        @error('course_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Attendance (%) --}}
    <div class="col-md-6">
        <label for="attendance_percentage" class="form-label fw-semibold">Attendance (%)</label>
        <input type="number" step="0.01" min="0" max="100" name="attendance_percentage"
            id="attendance_percentage" class="form-control @error('attendance_percentage') is-invalid @enderror"
            value="{{ old('attendance_percentage', $record->attendance_percentage ?? '') }}" placeholder="e.g. 85.5">
        @error('attendance_percentage')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Start Date --}}
    <div class="col-md-4">
        <label for="start_date" class="form-label fw-semibold">Start Date</label>
        <input type="date" name="start_date" id="start_date"
            class="form-control @error('start_date') is-invalid @enderror"
            value="{{ old('start_date', $record->start_date ?? '') }}">
        @error('start_date')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- End Date --}}
    <div class="col-md-4">
        <label for="end_date" class="form-label fw-semibold">End Date</label>
        <input type="date" name="end_date" id="end_date"
            class="form-control @error('end_date') is-invalid @enderror"
            value="{{ old('end_date', $record->end_date ?? '') }}">
        @error('end_date')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Result --}}
    <div class="col-md-4">
        <label for="result" class="form-label fw-semibold">Result</label>
        <select name="result" id="result" class="form-select @error('result') is-invalid @enderror">
            @foreach (['pending', 'passed', 'failed', 'cancelled'] as $r)
                <option value="{{ $r }}" @selected(old('result', $record->result ?? '') == $r)>
                    {{ ucfirst($r) }}
                </option>
            @endforeach
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

    {{-- Training Certificate File Upload --}}
    <div class="col-12">
        <label for="certificate_path" class="form-label fw-semibold">Training Certificate</label>
        <input type="file" name="certificate_path" id="certificate_path"
            class="form-control @error('certificate_path') is-invalid @enderror" accept=".pdf,.jpg,.jpeg,.png">
        @error('certificate_path')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror

        @isset($record->certificate_path)
            <div class="mt-2">
                <a href="{{ route('trainings.download', [$record, 'certificate_path']) }}"
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
    <x-button href="{{ route('trainings.index') }}" variant="danger">{{ __('Cancel') }}</x-button>
</div>
