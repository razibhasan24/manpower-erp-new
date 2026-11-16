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
            <label for="training_centre_id" class="form-label">Training Centre</label>
            <select name="training_centre_id" id="training_centre_id" class="form-select" required>
                <option value="">-- { __('Select') } --</option>
                @foreach($trainingCentres ?? [] as $option)
                    <option value="{{ $option->id }}" @selected(old('training_centre_id', $record->training_centre_id ?? null) == $option->id)>{{ $option->name ?? $option->title ?? $option->lot_number }}</option>
                @endforeach
            </select>
            @error('training_centre_id') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
            <label for="course_id" class="form-label">Training Course</label>
            <select name="course_id" id="course_id" class="form-select" required>
                <option value="">-- { __('Select') } --</option>
                @foreach($trainingCourses ?? [] as $option)
                    <option value="{{ $option->id }}" @selected(old('course_id', $record->course_id ?? null) == $option->id)>{{ $option->name ?? $option->title ?? $option->lot_number }}</option>
                @endforeach
            </select>
            @error('course_id') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3"><label for="start_date" class="form-label">Start Date</label><input type="date" name="start_date" id="start_date" class="form-control" value="{{ old('start_date', isset($record) ? $record->start_date : '') }}">@error('start_date') <div class="text-danger small">{{ $message }}</div> @enderror</div>
        <div class="mb-3"><label for="end_date" class="form-label">End Date</label><input type="date" name="end_date" id="end_date" class="form-control" value="{{ old('end_date', isset($record) ? $record->end_date : '') }}">@error('end_date') <div class="text-danger small">{{ $message }}</div> @enderror</div>
        <div class="mb-3"><label for="attendance_percentage" class="form-label">Attendance (%)</label><input type="number" step="0.01" name="attendance_percentage" id="attendance_percentage" class="form-control" value="{{ old('attendance_percentage', $record->attendance_percentage ?? '') }}">@error('attendance_percentage') <div class="text-danger small">{{ $message }}</div> @enderror</div>
        <div class="mb-3"><label for="result" class="form-label">Result</label><select name="result" id="result" class="form-select">@foreach(['pending','passed','failed','cancelled'] as $r)<option value="{{ $r }}" @selected(old('result', $record->result ?? '') == $r)>{{ ucfirst($r) }}</option>@endforeach</select>@error('result') <div class="text-danger small">{{ $message }}</div> @enderror</div>
        <div class="mb-3"><label for="remarks" class="form-label">Remarks</label><textarea name="remarks" id="remarks" class="form-control" rows="3">{{ old('remarks', $record->remarks ?? '') }}</textarea>@error('remarks') <div class="text-danger small">{{ $message }}</div> @enderror</div>
        <div class="mb-3"><label for="certificate_path" class="form-label">Training Certificate</label><input type="file" name="certificate_path" id="certificate_path" class="form-control" accept=".pdf,.jpg,.jpeg,.png">@error('certificate_path') <div class="text-danger small">{{ $message }}</div> @enderror @isset($record->certificate_path)<div class="mt-1"><a href="{{ route('trainings.download', [$record, 'certificate_path']) }}">{{ __('Download current file') }}</a></div>@endisset</div>
<div class="d-flex gap-2">
    <x-button type="submit">{{ __('Save') }}</x-button>
    <x-button href="{{ route('trainings.index') }}" variant="secondary">{{ __('Cancel') }}</x-button>
</div>
