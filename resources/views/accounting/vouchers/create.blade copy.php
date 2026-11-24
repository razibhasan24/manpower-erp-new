@extends('layouts.app')
@php($editing = isset($item) && $item !== null)
@php($selectedType = old('voucher_type', $item->voucher_type ?? $defaultType))
@php($formLines = old('lines', $item->form_lines ?? []))
@php($standardLines = $selectedType === 'contra' ? [] : ($formLines ?: ($selectedType === 'journal' ? [['account_id' => '', 'entry_type' => 'debit', 'amount' => ''], ['account_id' => '', 'entry_type' => 'credit', 'amount' => '']] : [['account_id' => '', 'amount' => '']])))
@php($contraLines = $selectedType === 'contra' ? ($formLines ?: [['debit_account_id' => '', 'debit_amount' => '', 'credit_account_id' => '', 'credit_amount' => '']]) : [])
@php($selectedLabel = $selectedType === 'receipt' ? 'Receive' : ucfirst($selectedType))
@section('title', $selectedLabel . ' Voucher Create')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>{{ $selectedLabel }} Voucher Create</h1>
        @can('viewAny', \App\Models\Voucher::class)
            <a href="{{ route('accounting.vouchers.index') }}" class="btn btn-outline-secondary">Voucher List</a>
        @endcan
    </div>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="POST"
        action="{{ $editing ? route('accounting.vouchers.update', $item) : route('accounting.vouchers.store') }}"
        id="voucher-form" enctype="multipart/form-data">
        @csrf @if ($editing)
            @method('PUT')
        @endif
        <input type="hidden" name="voucher_type" id="voucher_type" value="{{ $selectedType }}">
        <div class="row g-3">
            <div class="col-lg-8">
                <div class="card mb-3">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center"><strong
                                id="voucher-heading">{{ $selectedLabel }} Voucher Information</strong><select
                                id="type-switch" class="form-select" style="max-width:220px"
                                @if ($editing) disabled @endif>
                                @foreach ($types as $type)
                                    <option value="{{ $type }}" @selected($selectedType === $type)>
                                        {{ $type === 'receipt' ? 'Receive' : ucfirst($type) }} Voucher</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="standard-entry-wrap" class="table-responsive">
                            <table class="table table-bordered align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width:55px">S/L</th>
                                        <th>Account Head *</th>
                                        <th style="width:125px" id="entry-type-heading">Trnx Type *</th>
                                        <th style="width:150px">Amount *</th>
                                        <th>Details</th>
                                        <th style="width:65px"></th>
                                    </tr>
                                </thead>
                                <tbody id="standard-lines"></tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" class="text-end">Total Debit</th>
                                        <th id="total-debit" class="text-end">0.00</th>
                                        <th colspan="2"></th>
                                    </tr>
                                    <tr>
                                        <th colspan="3" class="text-end">Total Credit</th>
                                        <th id="total-credit" class="text-end">0.00</th>
                                        <th colspan="2"></th>
                                    </tr>
                                    <tr>
                                        <th colspan="3" class="text-end">Difference</th>
                                        <th id="difference" class="text-end text-danger">0.00</th>
                                        <th colspan="2" id="balance-message">Not balanced</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div id="contra-entry-wrap" class="table-responsive d-none">
                            <table class="table table-bordered align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width:55px">S/L</th>
                                        <th>Debit Account Head *</th>
                                        <th style="width:140px">Dr. Amount *</th>
                                        <th>Credit Account Head *</th>
                                        <th style="width:140px">Cr. Amount *</th>
                                        <th style="width:65px"></th>
                                    </tr>
                                </thead>
                                <tbody id="contra-lines"></tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="2" class="text-end">Total Debit</th>
                                        <th id="contra-debit-total" class="text-end">0.00</th>
                                        <th class="text-end">Total Credit</th>
                                        <th id="contra-credit-total" class="text-end">0.00</th>
                                        <th></th>
                                    </tr>
                                    <tr>
                                        <th colspan="2" class="text-end">Difference</th>
                                        <th colspan="3" id="contra-balance-message" class="text-danger">Not balanced</th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="d-flex justify-content-end mt-2"><button type="button" class="btn btn-danger"
                                id="add-line">+ Add Entry</button></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header"><strong>Basic Information</strong></div>
                    <div class="card-body">
                        <div class="mb-3"><label for="branch_id" class="form-label">Branch *</label><select id="branch_id"
                                name="branch_id" class="form-select" required>
                                <option value="">Select Branch</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}" @selected(old('branch_id', $item->branch_id ?? auth()->user()->branch_id) == $branch->id)>{{ $branch->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3 payment-info"><label for="payment_mode_id" class="form-label">Payment Mode
                                *</label><select id="payment_mode_id" name="payment_mode_id" class="form-select">
                                <option value="">Select Cash / Bank</option>
                                @foreach ($paymentModes as $mode)
                                    <option value="{{ $mode->id }}" data-account-id="{{ $mode->account_id }}"
                                        @selected(old('payment_mode_id', $item->payment_mode_id ?? '') == $mode->id)>{{ $mode->name }} ({{ $mode->account?->code }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3 payee-info"><label for="payee" class="form-label"
                                id="payee-label">Payee</label><input id="payee" name="payee"
                                value="{{ old('payee', $item->payee ?? '') }}" class="form-control"
                                placeholder="Enter payee name"></div>
                        <div class="mb-3 journal-party"><label class="form-label">Party Type</label><select
                                name="party_type" class="form-select">
                                <option value="">Select Party Type</option>
                                @foreach (['agent' => 'Agent', 'passport_holder' => 'Passport Holder', 'vendor' => 'Vendor', 'employee' => 'Employee'] as $value => $label)
                                    <option value="{{ $value }}" @selected(old('party_type', $item->form_party_type ?? '') === $value)>{{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            <label class="form-label mt-2">Party ID</label><input name="party_id" type="number"
                                min="1" value="{{ old('party_id', $item->form_party_id ?? '') }}"
                                class="form-control" placeholder="Enter party ID">
                        </div>
                        <div class="mb-3"><label for="voucher_date" class="form-label">Date *</label><input
                                id="voucher_date" type="date" name="voucher_date"
                                value="{{ old('voucher_date', $item?->voucher_date?->format('Y-m-d') ?? now()->toDateString()) }}"
                                class="form-control" required></div>
                        <div class="mb-3"><label for="reference_type" class="form-label">Reference Type</label><input
                                id="reference_type" name="reference_type"
                                value="{{ old('reference_type', $item->reference_type ?? '') }}" class="form-control"
                                placeholder="Bill / Invoice"></div>
                        <div class="mb-3"><label for="reference_id" class="form-label">Reference No.</label><input
                                id="reference_id" name="reference_id" type="number" min="1"
                                value="{{ old('reference_id', $item->reference_id ?? '') }}" class="form-control"></div>
                        <div class="mb-3"><label for="narration" class="form-label">Notes</label>
                            <textarea id="narration" name="narration" rows="3" maxlength="500" class="form-control" required>{{ old('narration', $item->narration ?? '') }}</textarea>
                        </div>
                        <div class="mb-3"><label for="attachment" class="form-label">Upload Image /
                                Document</label><input id="attachment" name="attachment" type="file"
                                accept="image/jpeg,image/png,application/pdf" class="form-control">
                            <div class="form-text">PDF, JPG or PNG; max 5 MB. @if ($editing && $item->attachment_path)
                                    <a href="{{ route('accounting.vouchers.attachment', $item) }}">Download current
                                        file</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-between mt-3"><a href="{{ route('accounting.vouchers.index') }}"
                class="btn btn-outline-secondary">Cancel</a><button type="submit" id="save-voucher"
                class="btn btn-primary">{{ $editing ? 'Update Draft' : 'Save as Draft' }}</button></div>
    </form>
    <template id="standard-template">
        <tr>
            <td class="line-sl"></td>
            <td><select data-field="account_id" class="form-select" required>
                    <option value="">Search Account Head</option>
                    @foreach ($accounts as $account)
                        <option value="{{ $account->id }}">{{ $account->name }} - {{ $account->code }}</option>
                    @endforeach
                </select>
            </td>
            <td class="entry-type-cell"><select data-field="entry_type" class="form-select">
                    <option value="debit">Debit</option>
                    <option value="credit">Credit</option>
                </select><span class="fixed-entry-type"></span></td>
            <td><input data-field="amount" type="number" min="0.01" step="0.01" class="form-control amount"
                    placeholder="Amount" required></td>
            <td><input data-field="narration" class="form-control" placeholder="Line details"></td>
            <td><button type="button" class="btn btn-sm btn-danger remove-line">×</button></td>
        </tr>
    </template>
    <template id="contra-template">
        <tr>
            <td class="line-sl"></td>
            <td><select data-field="debit_account_id" class="form-select" required>
                    <option value="">Search Debit Account</option>
                    @foreach ($accounts as $account)
                        <option value="{{ $account->id }}">{{ $account->name }} - {{ $account->code }}</option>
                    @endforeach
                </select>
            </td>
            <td><input data-field="debit_amount" type="number" min="0.01" step="0.01"
                    class="form-control dr-amount" placeholder="Dr. Amount" required></td>
            <td><select data-field="credit_account_id" class="form-select" required>
                    <option value="">Search Credit Account</option>
                    @foreach ($accounts as $account)
                        <option value="{{ $account->id }}">{{ $account->name }} - {{ $account->code }}</option>
                    @endforeach
                </select></td>
            <td><input data-field="credit_amount" type="number" min="0.01" step="0.01"
                    class="form-control cr-amount" placeholder="Cr. Amount" required></td>
            <td><button type="button" class="btn btn-sm btn-danger remove-line">×</button><input data-field="narration"
                    type="hidden"></td>
        </tr>
    </template>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const type = document.getElementById('voucher_type'),
                switcher = document.getElementById('type-switch'),
                standardBody = document.getElementById('standard-lines'),
                contraBody = document.getElementById('contra-lines');
            const standardSeed = @json($standardLines),
                contraSeed = @json($contraLines);
            let index = 0;

            function addStandard(line = {}) {
                const row = document.getElementById('standard-template').content.firstElementChild.cloneNode(true);
                row.querySelectorAll('[data-field]').forEach(el => {
                    const field = el.dataset.field;
                    el.name = `lines[${index}][${field}]`;
                    if (line[field] !== undefined) el.value = line[field]
                });
                row.querySelector('.line-sl').textContent = index + 1;
                standardBody.append(row);
                index++
            }

            function addContra(line = {}) {
                const row = document.getElementById('contra-template').content.firstElementChild.cloneNode(true);
                row.querySelectorAll('[data-field]').forEach(el => {
                    const field = el.dataset.field;
                    el.name = `lines[${index}][${field}]`;
                    if (line[field] !== undefined) el.value = line[field]
                });
                row.querySelector('.line-sl').textContent = index + 1;
                contraBody.append(row);
                index++
            }

            function setType(value, reset = false) {
                type.value = value;
                const label = value === 'receipt' ? 'Receive' : value[0].toUpperCase() + value.slice(1);
                document.getElementById('voucher-heading').textContent = label + ' Voucher Information';
                document.getElementById('standard-entry-wrap').classList.toggle('d-none', value === 'contra');
                document.getElementById('contra-entry-wrap').classList.toggle('d-none', value !== 'contra');
                document.querySelectorAll('.payment-info,.payee-info').forEach(e => e.classList.toggle('d-none', ![
                    'payment', 'receipt'
                ].includes(value)));
                document.querySelectorAll('.journal-party').forEach(e => e.classList.toggle('d-none', value !==
                    'journal'));
                document.getElementById('payment_mode_id').required = ['payment', 'receipt'].includes(value);
                document.getElementById('entry-type-heading').classList.toggle('d-none', ['payment', 'receipt']
                    .includes(value));
                document.querySelectorAll('.entry-type-cell').forEach(cell => {
                    cell.querySelector('select').classList.toggle('d-none', ['payment', 'receipt'].includes(
                        value));
                    cell.querySelector('.fixed-entry-type').textContent = value === 'payment' ? 'Debit' : (
                        value === 'receipt' ? 'Credit' : '')
                });
                document.getElementById('payee-label').textContent = value === 'receipt' ? 'Received From' :
                'Payee';
                if (reset) {
                    standardBody.innerHTML = '';
                    contraBody.innerHTML = '';
                    index = 0;
                    if (value === 'contra') addContra();
                    else if (value === 'journal') {
                        addStandard({
                            entry_type: 'debit'
                        });
                        addStandard({
                            entry_type: 'credit'
                        })
                    } else addStandard()
                }
                document.getElementById('save-voucher').textContent = 'Save as Draft';
                recalc()
            }

            function recalc() {
                let dr = 0,
                    cr = 0;
                if (type.value === 'contra') {
                    contraBody.querySelectorAll('tr').forEach(r => {
                        dr += parseFloat(r.querySelector('.dr-amount').value) || 0;
                        cr += parseFloat(r.querySelector('.cr-amount').value) || 0
                    });
                    document.getElementById('contra-debit-total').textContent = dr.toFixed(2);
                    document.getElementById('contra-credit-total').textContent = cr.toFixed(2);
                    const diff = Math.abs(dr - cr);
                    document.getElementById('contra-balance-message').textContent = diff < .005 && dr > 0 ?
                        'Balanced' : 'Difference: ' + diff.toFixed(2);
                    document.getElementById('contra-balance-message').className = diff < .005 && dr > 0 ?
                        'text-success' : 'text-danger'
                } else {
                    standardBody.querySelectorAll('tr').forEach(r => {
                        const amount = parseFloat(r.querySelector('.amount').value) || 0;
                        if (type.value === 'payment') {
                            dr += amount;
                            cr += amount
                        } else if (type.value === 'receipt') {
                            dr += amount;
                            cr += amount
                        } else if (r.querySelector('[data-field="entry_type"]').value === 'debit') dr +=
                            amount;
                        else cr += amount
                    });
                    document.getElementById('total-debit').textContent = dr.toFixed(2);
                    document.getElementById('total-credit').textContent = cr.toFixed(2);
                    document.getElementById('difference').textContent = Math.abs(dr - cr).toFixed(2);
                    document.getElementById('balance-message').textContent = dr > 0 && Math.abs(dr - cr) < .005 ?
                        'Balanced' : 'Not balanced';
                    document.getElementById('balance-message').className = dr > 0 && Math.abs(dr - cr) < .005 ?
                        'text-success' : 'text-danger'
                }
                const good = type.value === 'contra' ? dr > 0 && Math.abs(dr - cr) < .005 : (type.value ===
                    'journal' ? dr > 0 && Math.abs(dr - cr) < .005 : standardBody.querySelectorAll('tr')
                    .length > 0 && [...standardBody.querySelectorAll('.amount')].every(i => parseFloat(i
                        .value) > 0));
                document.getElementById('save-voucher').disabled = !good
            }
            document.getElementById('add-line').addEventListener('click', () => {
                type.value === 'contra' ? addContra() : addStandard();
                setType(type.value, false)
            });
            switcher.addEventListener('change', () => setType(switcher.value, true));
            document.getElementById('voucher-form').addEventListener('input', recalc);
            document.getElementById('voucher-form').addEventListener('change', recalc);
            document.getElementById('voucher-form').addEventListener('click', e => {
                if (e.target.classList.contains('remove-line')) {
                    const body = e.target.closest('tbody');
                    if (body.querySelectorAll('tr').length > (type.value === 'journal' ? 2 : 1)) {
                        e.target.closest('tr').remove();
                        [...body.querySelectorAll('.line-sl')].forEach((el, i) => el.textContent = i + 1);
                        recalc()
                    }
                }
            });
            const current = type.value;
            if (current === 'contra') contraSeed.forEach(addContra);
            else standardSeed.forEach(addStandard);
            if (!standardSeed.length && !contraSeed.length) setType(current, true);
            setType(current, false);
            if (switcher.value !== current) switcher.value = current;
            recalc();
        });
    </script>
@endsection
