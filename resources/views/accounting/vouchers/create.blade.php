@extends('layouts.app')

@php($editing = isset($item) && $item !== null)
@php($selectedType = old('voucher_type', $item->voucher_type ?? $defaultType))
@php($formLines = old('lines', $item->form_lines ?? []))
@php($standardLines = $selectedType === 'contra' ? [] : ($formLines ?: ($selectedType === 'journal' ? [['account_id' => '', 'entry_type' => 'debit', 'amount' => ''], ['account_id' => '', 'entry_type' => 'credit', 'amount' => '']] : [['account_id' => '', 'amount' => '']])))
@php($contraLines = $selectedType === 'contra' ? ($formLines ?: [['debit_account_id' => '', 'debit_amount' => '', 'credit_account_id' => '', 'credit_amount' => '']]) : [])
@php($selectedLabel = $selectedType === 'receipt' ? 'Receive' : ucfirst($selectedType))

@section('title', $selectedLabel . ' Voucher Create')

@push('styles')
    <style>
        /* Modern Table Styling */
        .voucher-table {
            margin-bottom: 0;
        }

        .voucher-table th {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6b7280;
            background-color: #f8fafc;
            border-bottom: 2px solid #e5e7eb;
            padding: 0.75rem 1rem;
        }

        .voucher-table td {
            padding: 0.5rem 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
        }

        .voucher-table tbody tr:last-child td {
            border-bottom: 2px solid #e5e7eb;
        }

        .voucher-table tfoot th,
        .voucher-table tfoot td {
            padding: 0.75rem 1rem;
            font-size: 0.9rem;
            background-color: #f8fafc;
            border: none;
        }

        .voucher-table .form-control,
        .voucher-table .form-select {
            font-size: 0.875rem;
            border-color: #e5e7eb;
        }

        .voucher-table .form-control:focus,
        .voucher-table .form-select:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.25);
        }

        .fixed-entry-type {
            display: inline-block;
            padding: 0.375rem 0.75rem;
            font-weight: 600;
            color: #4b5563;
            background-color: #f3f4f6;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            text-align: center;
            width: 100%;
        }

        /* ✅ FIX: Remove Line Button Style */
        .remove-line {
            font-size: 1.25rem;
            line-height: 1;
            padding: 0.1rem 0.5rem;
            border-radius: 0.375rem;
            color: #dc3545;
            background: transparent;
            border: 1px solid #dc3545;
            transition: all 0.2s ease-in-out;
        }

        .remove-line:hover {
            background-color: #dc3545;
            color: #fff;
        }
    </style>
@endpush

@section('content')
    {{-- ✅ Header Section --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">{{ $selectedLabel }} Voucher {{ $editing ? 'Edit' : 'Create' }}</h1>
            <p class="text-muted small mb-0">Enter accounting details and ledger lines to save the voucher.</p>
        </div>
        @can('viewAny', \App\Models\Voucher::class)
            <x-button href="{{ route('accounting.vouchers.index') }}" variant="success" class="btn btn-success btn-sm">
                <i class="bi bi-list-ul me-1"></i> Voucher List
            </x-button>
        @endcan
    </div>

    {{-- ✅ Error Summary --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <div class="d-flex align-items-center mb-2">
                <i class="bi bi-exclamation-triangle-fill fs-5 me-2"></i>
                <strong>Please fix the following errors:</strong>
            </div>
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <x-button variant="danger" type="button" class="btn-close" data-bs-dismiss="alert"
                aria-label="Close"></x-button>
        </div>
    @endif

    <form method="POST"
        action="{{ $editing ? route('accounting.vouchers.update', $item) : route('accounting.vouchers.store') }}"
        id="voucher-form" enctype="multipart/form-data">
        @csrf
        @if ($editing)
            @method('PUT')
        @endif
        <input type="hidden" name="voucher_type" id="voucher_type" value="{{ $selectedType }}">

        <div class="row g-4">
            {{-- ✅ Left Column: Voucher Entries --}}
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 h-100">
                    <div
                        class="card-header bg-white border-bottom pt-4 pb-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
                        <h5 class="mb-0 fw-semibold" id="voucher-heading">{{ $selectedLabel }} Voucher Information</h5>
                        <select id="type-switch" class="form-select form-select-sm" style="max-width:220px"
                            @if ($editing) disabled @endif>
                            @foreach ($types as $type)
                                <option value="{{ $type }}" @selected($selectedType === $type)>
                                    {{ $type === 'receipt' ? 'Receive' : ucfirst($type) }} Voucher
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="card-body p-0">
                        {{-- Standard Table --}}
                        <div id="standard-entry-wrap" class="table-responsive">
                            <table class="table voucher-table">
                                <thead>
                                    <tr>
                                        <th style="width:55px" class="ps-4">S/L</th>
                                        <th>Account Head <span class="text-danger">*</span></th>
                                        <th style="width:130px" id="entry-type-heading">Trnx Type <span
                                                class="text-danger">*</span></th>
                                        <th style="width:150px">Amount <span class="text-danger">*</span></th>
                                        <th>Details</th>
                                        <th style="width:65px" class="pe-4 text-end"></th>
                                    </tr>
                                </thead>
                                <tbody id="standard-lines"></tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" class="text-end">Total Debit</th>
                                        <th id="total-debit" class="text-end text-dark fw-bold">0.00</th>
                                        <th colspan="2" class="pe-4"></th>
                                    </tr>
                                    <tr>
                                        <th colspan="3" class="text-end border-0 pt-0">Total Credit</th>
                                        <th id="total-credit" class="text-end text-dark fw-bold border-0 pt-0">0.00</th>
                                        <th colspan="2" class="border-0 pt-0 pe-4"></th>
                                    </tr>
                                    <tr>
                                        <th colspan="3" class="text-end border-top border-danger-subtle">Difference</th>
                                        <th id="difference"
                                            class="text-end text-danger fw-bold border-top border-danger-subtle">0.00</th>
                                        <th colspan="2" id="balance-message"
                                            class="text-danger fw-bold border-top border-danger-subtle">Not balanced</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        {{-- Contra Table --}}
                        <div id="contra-entry-wrap" class="table-responsive d-none">
                            <table class="table voucher-table">
                                <thead>
                                    <tr>
                                        <th style="width:55px" class="ps-4">S/L</th>
                                        <th>Debit Account Head <span class="text-danger">*</span></th>
                                        <th style="width:140px">Dr. Amount <span class="text-danger">*</span></th>
                                        <th>Credit Account Head <span class="text-danger">*</span></th>
                                        <th style="width:140px">Cr. Amount <span class="text-danger">*</span></th>
                                        <th style="width:65px" class="pe-4 text-end"></th>
                                    </tr>
                                </thead>
                                <tbody id="contra-lines"></tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="2" class="text-end">Total Debit</th>
                                        <th id="contra-debit-total" class="text-end text-dark fw-bold">0.00</th>
                                        <th class="text-end border-0 pt-0">Total Credit</th>
                                        <th id="contra-credit-total" class="text-end text-dark fw-bold border-0 pt-0">0.00
                                        </th>
                                        <th class="pe-4 border-0 pt-0"></th>
                                    </tr>
                                    <tr>
                                        <th colspan="2" class="text-end border-top border-danger-subtle">Difference</th>
                                        <th colspan="3" id="contra-balance-message"
                                            class="text-danger fw-bold border-top border-danger-subtle">Not balanced</th>
                                        <th class="pe-4 border-top border-danger-subtle"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        {{-- Add Row Button --}}
                        <div class="p-3 bg-light border-top d-flex justify-content-end rounded-bottom">
                            <x-button type="button" variant="primary" class="btn btn-sm btn-primary fw-semibold"
                                id="add-line">
                                <i class="bi bi-plus-lg me-1"></i> Add Entry Line
                            </x-button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ✅ Right Column: Basic Information --}}
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white border-bottom pt-4 pb-3">
                        <h5 class="mb-0 fw-semibold"><i class="bi bi-info-circle me-2 text-primary"></i> Basic Information
                        </h5>
                    </div>
                    <div class="card-body">
                        {{-- Branch --}}
                        <div class="mb-3">
                            <label for="branch_id" class="form-label small fw-semibold text-muted">Branch <span
                                    class="text-danger">*</span></label>
                            <select id="branch_id" name="branch_id" class="form-select form-select-sm" required>
                                <option value="">Select Branch</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}" @selected(old('branch_id', $item->branch_id ?? auth()->user()->branch_id) == $branch->id)>
                                        {{ $branch->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Voucher Date --}}
                        <div class="mb-3">
                            <label for="voucher_date" class="form-label small fw-semibold text-muted">Date <span
                                    class="text-danger">*</span></label>
                            <input id="voucher_date" type="date" name="voucher_date"
                                value="{{ old('voucher_date', $item?->voucher_date?->format('Y-m-d') ?? now()->toDateString()) }}"
                                class="form-control form-control-sm" required>
                        </div>

                        {{-- Payment Mode --}}
                        <div class="mb-3 payment-info">
                            <label for="payment_mode_id" class="form-label small fw-semibold text-muted">Payment Mode
                                <span class="text-danger">*</span></label>
                            <select id="payment_mode_id" name="payment_mode_id" class="form-select form-select-sm">
                                <option value="">Select Cash / Bank</option>
                                @foreach ($paymentModes as $mode)
                                    <option value="{{ $mode->id }}" data-account-id="{{ $mode->account_id }}"
                                        @selected(old('payment_mode_id', $item->payment_mode_id ?? '') == $mode->id)>
                                        {{ $mode->name }} ({{ $mode->account?->code }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Payee --}}
                        <div class="mb-3 payee-info">
                            <label for="payee" class="form-label small fw-semibold text-muted"
                                id="payee-label">Payee</label>
                            <input id="payee" name="payee" value="{{ old('payee', $item->payee ?? '') }}"
                                class="form-control form-control-sm" placeholder="Enter payee name">
                        </div>

                        {{-- Journal Party --}}
                        <div class="mb-3 journal-party">
                            <label class="form-label small fw-semibold text-muted">Party Type</label>
                            <select name="party_type" class="form-select form-select-sm mb-2">
                                <option value="">Select Party Type</option>
                                @foreach (['agent' => 'Agent', 'passport_holder' => 'Passport Holder', 'vendor' => 'Vendor', 'employee' => 'Employee'] as $value => $label)
                                    <option value="{{ $value }}" @selected(old('party_type', $item->form_party_type ?? '') === $value)>{{ $label }}
                                    </option>
                                @endforeach
                            </select>

                            <label class="form-label small fw-semibold text-muted">Party ID</label>
                            <input name="party_id" type="number" min="1"
                                value="{{ old('party_id', $item->form_party_id ?? '') }}"
                                class="form-control form-control-sm" placeholder="Enter party ID">
                        </div>

                        {{-- Reference --}}
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label for="reference_type" class="form-label small fw-semibold text-muted">Reference
                                    Type</label>
                                <input id="reference_type" name="reference_type"
                                    value="{{ old('reference_type', $item->reference_type ?? '') }}"
                                    class="form-control form-control-sm" placeholder="Bill / Invoice">
                            </div>
                            <div class="col-6">
                                <label for="reference_id" class="form-label small fw-semibold text-muted">Reference
                                    No.</label>
                                <input id="reference_id" name="reference_id" type="number" min="1"
                                    value="{{ old('reference_id', $item->reference_id ?? '') }}"
                                    class="form-control form-control-sm">
                            </div>
                        </div>

                        {{-- Narration --}}
                        <div class="mb-3">
                            <label for="narration" class="form-label small fw-semibold text-muted">Notes / Narration <span
                                    class="text-danger">*</span></label>
                            <textarea id="narration" name="narration" rows="3" maxlength="500" class="form-control form-control-sm"
                                required placeholder="Enter voucher notes...">{{ old('narration', $item->narration ?? '') }}</textarea>
                        </div>

                        {{-- Attachment --}}
                        <div class="mb-0">
                            <label for="attachment" class="form-label small fw-semibold text-muted">Upload
                                Document</label>
                            <input id="attachment" name="attachment" type="file"
                                accept="image/jpeg,image/png,application/pdf" class="form-control form-control-sm">
                            <div class="form-text" style="font-size: 0.75rem;">
                                PDF, JPG or PNG (Max 5 MB).
                                @if ($editing && $item->attachment_path)
                                    <a href="{{ route('accounting.vouchers.attachment', $item) }}"
                                        class="text-decoration-none fw-medium">Download current</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ✅ Action Buttons --}}
        <div class="d-flex justify-content-between mt-4 pt-3 border-top">
            <x-button variant="danger" href="{{ route('accounting.vouchers.index') }}" class="btn btn-danger px-4">
                Cancel
            </x-button>
            <x-button type="submit" id="save-voucher" variant="success" class="btn btn-success px-4 shadow-sm">
                <i class="bi bi-check-lg me-1"></i> {{ $editing ? 'Update Draft' : 'Save as Draft' }}
            </x-button>
        </div>
    </form>

    {{-- ✅ Templates (Updated with fixed remove button) --}}
    <template id="standard-template">
        <tr>
            <td class="line-sl fw-bold text-muted ps-4"></td>
            <td>
                <select data-field="account_id" class="form-select form-select-sm" required>
                    <option value="">Search Account Head</option>
                    @foreach ($accounts as $account)
                        <option value="{{ $account->id }}">{{ $account->name }} - {{ $account->code }}</option>
                    @endforeach
                </select>
            </td>
            <td class="entry-type-cell">
                <select data-field="entry_type" class="form-select form-select-sm">
                    <option value="debit">Debit</option>
                    <option value="credit">Credit</option>
                </select>
                <span class="fixed-entry-type d-none"></span>
            </td>
            <td>
                <input data-field="amount" type="number" min="0.01" step="0.01"
                    class="form-control form-control-sm amount text-end" placeholder="0.00" required>
            </td>
            <td>
                <input data-field="narration" class="form-control form-control-sm" placeholder="Details">
            </td>
            <td class="pe-4 text-end">
                {{-- ✅ Fixed Button --}}
                <x-button variant="danger" type="button" class="remove-line" title="Remove line">&times;</x-button>
            </td>
        </tr>
    </template>

    <template id="contra-template">
        <tr>
            <td class="line-sl fw-bold text-muted ps-4"></td>
            <td>
                <select data-field="debit_account_id" class="form-select form-select-sm" required>
                    <option value="">Search Debit Account</option>
                    @foreach ($accounts as $account)
                        <option value="{{ $account->id }}">{{ $account->name }} - {{ $account->code }}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <input data-field="debit_amount" type="number" min="0.01" step="0.01"
                    class="form-control form-control-sm dr-amount text-end" placeholder="0.00" required>
            </td>
            <td>
                <select data-field="credit_account_id" class="form-select form-select-sm" required>
                    <option value="">Search Credit Account</option>
                    @foreach ($accounts as $account)
                        <option value="{{ $account->id }}">{{ $account->name }} - {{ $account->code }}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <input data-field="credit_amount" type="number" min="0.01" step="0.01"
                    class="form-control form-control-sm cr-amount text-end" placeholder="0.00" required>
            </td>
            <td class="pe-4 text-end">
                {{-- ✅ Fixed Button --}}
                <x-button variant="danger" type="button" class="remove-line" title="Remove line">&times;</x-button>
                <input data-field="narration" type="hidden">
            </td>
        </tr>
    </template>

    {{-- ✅ JavaScript Logic (Untouched, just formatted) --}}
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
                    const fixedSpan = cell.querySelector('.fixed-entry-type');
                    fixedSpan.textContent = value === 'payment' ? 'Debit' : (value === 'receipt' ?
                        'Credit' : '');
                    fixedSpan.classList.toggle('d-none', !['payment', 'receipt'].includes(value));
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
                        });
                    } else addStandard();
                }
                document.getElementById('save-voucher').innerHTML =
                    '<i class="bi bi-check-lg me-1"></i> Save as Draft';
                recalc();
            }

            function recalc() {
                let dr = 0,
                    cr = 0;
                if (type.value === 'contra') {
                    contraBody.querySelectorAll('tr').forEach(r => {
                        dr += parseFloat(r.querySelector('.dr-amount').value) || 0;
                        cr += parseFloat(r.querySelector('.cr-amount').value) || 0;
                    });
                    document.getElementById('contra-debit-total').textContent = dr.toFixed(2);
                    document.getElementById('contra-credit-total').textContent = cr.toFixed(2);
                    const diff = Math.abs(dr - cr);
                    document.getElementById('contra-balance-message').textContent = diff < .005 && dr > 0 ?
                        'Balanced' : 'Difference: ' + diff.toFixed(2);
                    document.getElementById('contra-balance-message').className = diff < .005 && dr > 0 ?
                        'text-success fw-bold' : 'text-danger fw-bold';
                } else {
                    standardBody.querySelectorAll('tr').forEach(r => {
                        const amount = parseFloat(r.querySelector('.amount').value) || 0;
                        if (type.value === 'payment') {
                            dr += amount;
                            cr += amount;
                        } else if (type.value === 'receipt') {
                            dr += amount;
                            cr += amount;
                        } else if (r.querySelector('[data-field="entry_type"]').value === 'debit') dr +=
                            amount;
                        else cr += amount;
                    });
                    document.getElementById('total-debit').textContent = dr.toFixed(2);
                    document.getElementById('total-credit').textContent = cr.toFixed(2);
                    document.getElementById('difference').textContent = Math.abs(dr - cr).toFixed(2);
                    document.getElementById('balance-message').textContent = dr > 0 && Math.abs(dr - cr) < .005 ?
                        'Balanced' : 'Not balanced';
                    document.getElementById('balance-message').className = dr > 0 && Math.abs(dr - cr) < .005 ?
                        'text-success fw-bold' : 'text-danger fw-bold';
                }

                const good = type.value === 'contra' ? dr > 0 && Math.abs(dr - cr) < .005 :
                    (type.value === 'journal' ? dr > 0 && Math.abs(dr - cr) < .005 :
                        standardBody.querySelectorAll('tr').length > 0 && [...standardBody.querySelectorAll(
                            '.amount')].every(i => parseFloat(i.value) > 0));

                document.getElementById('save-voucher').disabled = !good;
            }

            document.getElementById('add-line').addEventListener('click', () => {
                type.value === 'contra' ? addContra() : addStandard();
                setType(type.value, false);
            });

            switcher.addEventListener('change', () => setType(switcher.value, true));
            document.getElementById('voucher-form').addEventListener('input', recalc);
            document.getElementById('voucher-form').addEventListener('change', recalc);

            document.getElementById('voucher-form').addEventListener('click', e => {
                if (e.target.closest('.remove-line')) {
                    const body = e.target.closest('tbody');
                    if (body.querySelectorAll('tr').length > (type.value === 'journal' ? 2 : 1)) {
                        e.target.closest('tr').remove();
                        [...body.querySelectorAll('.line-sl')].forEach((el, i) => el.textContent = i + 1);
                        recalc();
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
