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
        <div class="mb-3"><label for="grade" class="form-label">Grade</label><input type="text" name="grade" id="grade" class="form-control" required value="{{ old('grade', $record->grade ?? '') }}">@error('grade') <div class="text-danger small">{{ $message }}</div> @enderror</div>
        <div class="mb-3"><label for="status" class="form-label">Status</label><select name="status" id="status" class="form-select">
                    <option value="pending" @selected(old('status', $record->status ?? '') == 'pending')>Pending</option>
                    <option value="selected" @selected(old('status', $record->status ?? '') == 'selected')>Selected</option>
                    <option value="waiting" @selected(old('status', $record->status ?? '') == 'waiting')>Waiting</option>
                    <option value="rejected" @selected(old('status', $record->status ?? '') == 'rejected')>Rejected</option>
                    <option value="cancelled" @selected(old('status', $record->status ?? '') == 'cancelled')>Cancelled</option>
                </select>@error('status') <div class="text-danger small">{{ $message }}</div> @enderror</div>
        <div class="mb-3"><label for="decided_at" class="form-label">Decided At</label><input type="date" name="decided_at" id="decided_at" class="form-control" value="{{ old('decided_at', isset($record) ? $record->decided_at : '') }}">@error('decided_at') <div class="text-danger small">{{ $message }}</div> @enderror</div>
        <div class="mb-3"><label for="remarks" class="form-label">Remarks</label><textarea name="remarks" id="remarks" class="form-control" rows="3">{{ old('remarks', $record->remarks ?? '') }}</textarea>@error('remarks') <div class="text-danger small">{{ $message }}</div> @enderror</div>
        <hr><h5>{{ __('Financial Responsibility (decided at Selection)') }}</h5>
        <div class="row">
            <div class="col-md-6 mb-3"><label for="financial_responsible_type" class="form-label">{{ __('Responsible Party') }}</label><select name="financial_responsible_type" id="financial_responsible_type" class="form-select"><option value="agent" @selected(old('financial_responsible_type', $record->recruitment?->financial_responsible_type) == 'agent')>{{ __('Agent') }}</option><option value="passport_holder" @selected(old('financial_responsible_type', $record->recruitment?->financial_responsible_type) == 'passport_holder')>{{ __('Passport Holder') }}</option></select></div>
            <div class="col-md-6 mb-3"><label for="financial_responsible_id" class="form-label">{{ __('Responsible Party ID') }}</label><input type="number" name="financial_responsible_id" id="financial_responsible_id" class="form-control" value="{{ old('financial_responsible_id', $record->recruitment?->financial_responsible_id) }}"></div>
        </div>
        <div class="row">
            @foreach(['salary' => 'Salary', 'company_service_charge' => 'Company Service Charge', 'agent_charge' => 'Agent Charge', 'medical_cost' => 'Medical Cost', 'training_cost' => 'Training Cost', 'visa_cost' => 'Visa Cost', 'other_cost' => 'Other Costs', 'advance_amount' => 'Advance'] as $field => $label)
            <div class="col-md-3 mb-3"><label for="{{ $field }}" class="form-label">{{ __($label) }}</label><input type="number" step="0.01" min="0" name="{{ $field }}" id="{{ $field }}" class="form-control" value="{{ old($field, $record->recruitment?->$field) }}"></div>
            @endforeach
        </div>
<div class="d-flex gap-2">
    <x-button type="submit">{{ __('Save') }}</x-button>
    <x-button href="{{ route('selections.index') }}" variant="secondary">{{ __('Cancel') }}</x-button>
</div>
