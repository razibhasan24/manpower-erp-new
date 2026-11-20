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
                <option value="{{ $recruitment->id }}" data-agent-id="{{ $recruitment->agent_id }}" data-agent-name="{{ $recruitment->agent?->name }}" data-holder-id="{{ $recruitment->passport_holder_id }}" data-holder-name="{{ $recruitment->passportHolder?->full_name }}" @selected(old('recruitment_id', $record?->recruitment_id) == $recruitment->id)>
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
        'approved' => 'Selected',
        'returned' => 'Waiting',
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

<div class="row g-3 mt-1">
    <div class="col-md-3">
        <label for="total_amount" class="form-label">{{ __('Total Due Before Advance') }}</label>
        <input type="number" id="total_amount" class="form-control" value="0.00" readonly>
    </div>
    <div class="col-md-3">
        <label for="remaining_due_display" class="form-label">{{ __('Remaining Due After Advance') }}</label>
        <input type="number" id="remaining_due_display" class="form-control" value="0.00" readonly>
    </div>
    <div class="col-md-6">
        <label for="advance_account_id" class="form-label">{{ __('Advance Received In') }}</label>
        <select name="advance_account_id" id="advance_account_id" class="form-select">
            @foreach (($cashAccounts ?? collect()) as $cashAccount)
                <option value="{{ $cashAccount->id }}" @selected(old('advance_account_id', $cashAccount->code === '1110' ? $cashAccount->id : null) == $cashAccount->id)>
                    {{ $cashAccount->code }} — {{ $cashAccount->name }}
                </option>
            @endforeach
        </select>
        @error('financial_responsible_type')<div class="text-danger small">{{ $message }}</div>@enderror
        @error('advance_amount')<div class="text-danger small">{{ $message }}</div>@enderror
        @error('advance_account_id')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const recruitment = document.getElementById('recruitment_id');
    const partyType = document.getElementById('financial_responsible_type');
    const partyId = document.getElementById('financial_responsible_id');
    const partyName = document.getElementById('responsible_party_name');
    const costFields = ['company_service_charge', 'agent_charge', 'visa_cost', 'medical_cost', 'training_cost', 'other_cost'];
    const advance = document.getElementById('advance_amount');
    const total = document.getElementById('total_amount');
    const due = document.getElementById('remaining_due_display');

    function updateParty() {
        const option = recruitment.options[recruitment.selectedIndex];
        if (option && partyType.value === 'agent' && !option.dataset.agentId && option.dataset.holderId) {
            partyType.value = 'passport_holder';
        }
        const isAgent = partyType.value === 'agent';
        partyId.value = option ? (isAgent ? option.dataset.agentId : option.dataset.holderId) || '' : '';
        partyName.value = option ? (isAgent ? option.dataset.agentName : option.dataset.holderName) || '' : '';
    }
    function updateAmounts() {
        const sum = costFields.reduce((value, id) => value + (parseFloat(document.getElementById(id)?.value) || 0), 0);
        const advanceValue = parseFloat(advance.value) || 0;
        total.value = sum.toFixed(2);
        due.value = Math.max(0, sum - advanceValue).toFixed(2);
        advance.max = sum.toFixed(2);
    }
    recruitment.addEventListener('change', updateParty);
    partyType.addEventListener('change', updateParty);
    [...costFields, 'advance_amount'].forEach(id => document.getElementById(id)?.addEventListener('input', updateAmounts));
    updateParty();
    updateAmounts();
});
</script>

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
        <label for="responsible_party_name" class="form-label">{{ __('Agent / Passport Holder Name') }}</label>
        <input type="text" id="responsible_party_name" class="form-control" readonly>
        <input type="hidden" name="financial_responsible_id" id="financial_responsible_id"
            value="{{ old('financial_responsible_id', $recruitmentData?->financial_responsible_id ?? '') }}">
    </div>

</div>

<div class="row g-3 mt-1">

    @foreach ([
        'salary' => 'Overseas Salary (not included in due)',
        'company_service_charge' => 'Company Service Charge',
        'agent_charge' => 'Agent Charge',
        'medical_cost' => 'Medical Cost',
        'training_cost' => 'Training Cost',
        'visa_cost' => 'Visa Cost',
        'other_cost' => 'Other Costs',
        'advance_amount' => 'Advance Amount',
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
<p class="form-text">Agent / Passport Holder due = Company Service Charge + Agent Charge + Visa Cost + Medical Cost + Training Cost + Other Costs − Advance. Overseas salary is shown separately and is not added to due.</p>

<hr>

<div class="d-flex gap-2">
    <x-button type="submit" variant="primary">
        {{ __('Save') }}
    </x-button>

    <x-button href="{{ route('selections.index') }}" variant="secondary">
        {{ __('Cancel') }}
    </x-button>
</div>
