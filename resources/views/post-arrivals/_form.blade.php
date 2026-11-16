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
        <div class="mb-3"><label for="arrival_date" class="form-label">Arrival Date</label><input type="date" name="arrival_date" id="arrival_date" class="form-control" value="{{ old('arrival_date', isset($record) ? $record->arrival_date : '') }}">@error('arrival_date') <div class="text-danger small">{{ $message }}</div> @enderror</div>
        <div class="mb-3 form-check"><input type="checkbox" name="airport_pickup" id="airport_pickup" value="1" class="form-check-input" @checked(old('airport_pickup', $record->airport_pickup ?? false))><label for="airport_pickup" class="form-check-label">Airport Pickup</label></div>
        <div class="mb-3"><label for="employer_contact" class="form-label">Employer Contact</label><input type="text" name="employer_contact" id="employer_contact" class="form-control"  value="{{ old('employer_contact', $record->employer_contact ?? '') }}">@error('employer_contact') <div class="text-danger small">{{ $message }}</div> @enderror</div>
        <div class="mb-3"><label for="accommodation" class="form-label">Accommodation</label><textarea name="accommodation" id="accommodation" class="form-control" rows="3">{{ old('accommodation', $record->accommodation ?? '') }}</textarea>@error('accommodation') <div class="text-danger small">{{ $message }}</div> @enderror</div>
        <div class="mb-3"><label for="feedback" class="form-label">Candidate Feedback</label><textarea name="feedback" id="feedback" class="form-control" rows="3">{{ old('feedback', $record->feedback ?? '') }}</textarea>@error('feedback') <div class="text-danger small">{{ $message }}</div> @enderror</div>
        <div class="mb-3"><label for="status" class="form-label">Status</label><select name="status" id="status" class="form-select">
                    <option value="pending" @selected(old('status', $record->status ?? '') == 'pending')>Pending</option>
                    <option value="arrived" @selected(old('status', $record->status ?? '') == 'arrived')>Arrived</option>
                    <option value="in_progress" @selected(old('status', $record->status ?? '') == 'in_progress')>In Progress</option>
                    <option value="completed" @selected(old('status', $record->status ?? '') == 'completed')>Completed</option>
                    <option value="cancelled" @selected(old('status', $record->status ?? '') == 'cancelled')>Cancelled</option>
                </select>@error('status') <div class="text-danger small">{{ $message }}</div> @enderror</div>
<div class="d-flex gap-2">
    <x-button type="submit">{{ __('Save') }}</x-button>
    <x-button href="{{ route('post-arrivals.index') }}" variant="secondary">{{ __('Cancel') }}</x-button>
</div>
