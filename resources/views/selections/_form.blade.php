@php
    $record = $record ?? null;
    $recruitmentData = $record?->recruitment;

    $grades = [
        'A+' => 'Grade A+',
        'A' => 'Grade A',
        'B' => 'Grade B',
        'C' => 'Grade C',
        'D' => 'Grade D',
    ];
@endphp

@csrf

<div class="row g-3">

    {{-- Recruitment --}}
    <div class="col-xl-3 col-lg-3 col-md-6">
        <label for="recruitment_id" class="form-label">Recruitment</label>
        <select name="recruitment_id" id="recruitment_id" class="form-select" required>
            <option value="">-- {{ __('Select recruitment') }} --</option>

            @foreach ($recruitments as $recruitment)
                <option value="{{ $recruitment->id }}" @selected(old('recruitment_id', $record?->recruitment_id) == $recruitment->id)>
                    {{ $recruitment->tracking_number }}
                    — {{ $recruitment->passportHolder?->full_name ?? 'N/A' }}
                </option>
            @endforeach
        </select>
        @error('recruitment_id')
            <div class="text-danger small">{{ $message }}</div>
        @enderror
    </div>

    {{-- Grade --}}
    <div class="col-xl-3 col-lg-3 col-md-6">
        <label for="grade" class="form-label">Grade</label>
        <select name="grade" id="grade" class="form-select" required>
            <option value="">-- Select Grade --</option>

            @foreach ($grades as $value => $label)
                <option value="{{ $value }}" @selected(old('grade', $record?->grade ?? '') == $value)>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        @error('grade')
            <div class="text-danger small">{{ $message }}</div>
        @enderror
    </div>

    {{-- Status --}}
    <div class="col-xl-3 col-lg-3 col-md-6">
        <label for="status" class="form-label">Status</label>
        <select name="status" id="status" class="form-select">
            @foreach ([
        'pending' => 'Pending',
        'selected' => 'Selected',
        'waiting' => 'Waiting',
        'rejected' => 'Rejected',
        'cancelled' => 'Cancelled',
    ] as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $record?->status ?? 'pending') == $value)>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        @error('status')
            <div class="text-danger small">{{ $message }}</div>
        @enderror
    </div>

    {{-- Decided At --}}
    <div class="col-xl-3 col-lg-3 col-md-6">
        <label for="decided_at" class="form-label">Decided At</label>
        <input type="date" name="decided_at" id="decided_at" class="form-control"
            value="{{ old('decided_at', $record?->decided_at ? \Illuminate\Support\Carbon::parse($record->decided_at)->format('Y-m-d') : '') }}">
        @error('decided_at')
            <div class="text-danger small">{{ $message }}</div>
        @enderror
    </div>

    {{-- Remarks --}}
    <div class="col-xl-3 col-lg-3 col-md-6">
        <label for="remarks" class="form-label">Remarks</label>
        <textarea name="remarks" id="remarks" class="form-control" rows="3">{{ old('remarks', $record?->remarks ?? '') }}</textarea>
        @error('remarks')
            <div class="text-danger small">{{ $message }}</div>
        @enderror
    </div>

</div>

<hr>

<h5>{{ __('Financial Responsibility (decided at Selection)') }}</h5>

<div class="row g-3">

    <div class="col-md-6">
        <label for="financial_responsible_type" class="form-label">
            {{ __('Responsible Party') }}
        </label>

        <select name="financial_responsible_type" id="financial_responsible_type" class="form-select">
            <option value="agent" @selected(old('financial_responsible_type', $recruitmentData?->financial_responsible_type ?? 'agent') == 'agent')>
                {{ __('Agent') }}
            </option>

            <option value="passport_holder" @selected(old('financial_responsible_type', $recruitmentData?->financial_responsible_type ?? '') == 'passport_holder')>
                {{ __('Passport Holder') }}
            </option>
        </select>
    </div>

    <div class="col-md-6">
        <label for="financial_responsible_id" class="form-label">
            {{ __('Responsible Party ID') }}
        </label>

        <input type="number" name="financial_responsible_id" id="financial_responsible_id" class="form-control"
            value="{{ old('financial_responsible_id', $recruitmentData?->financial_responsible_id ?? '') }}">
    </div>

</div>

<div class="row g-3 mt-1">

    @foreach ([
        'salary' => 'Salary',
        'company_service_charge' => 'Company Service Charge',
        'agent_charge' => 'Agent Charge',
        'medical_cost' => 'Medical Cost',
        'training_cost' => 'Training Cost',
        'visa_cost' => 'Visa Cost',
        'other_cost' => 'Other Costs',
        'advance_amount' => 'Advance',
    ] as $field => $label)
        <div class="col-xl-3 col-lg-3 col-md-6">
            <label for="{{ $field }}" class="form-label">
                {{ __($label) }}
            </label>

            <input type="number" step="0.01" min="0" name="{{ $field }}" id="{{ $field }}"
                class="form-control" value="{{ old($field, $recruitmentData?->$field ?? '') }}">
        </div>
    @endforeach

</div>

<hr>

<div class="d-flex gap-2">
    <x-button type="submit" variant="primary">
        {{ __('Save') }}
    </x-button>

    <x-button href="{{ route('selections.index') }}" variant="secondary">
        {{ __('Cancel') }}
    </x-button>
</div>
