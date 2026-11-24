@extends('layouts.app')

@php($isReceipt = $action === 'receive')
@php($dueAmount = $isReceipt ? max((float) $balance, 0) : max((float) -$balance, 0))
@php($partyTypeLabel = ['agent' => 'Agent', 'passport_holder' => 'Passport Holder', 'vendor' => 'Vendor'][$type] ?? 'Party')
@php($partyName = $type === 'passport_holder' ? $party->full_name : $party->name)

@section('title', ($isReceipt ? 'Receive from ' : 'Pay ') . $partyTypeLabel)

@section('content')
    {{-- ✅ Header Section --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">{{ $isReceipt ? 'Receive from ' : 'Pay ' }}{{ $partyTypeLabel }}</h1>
            <p class="text-muted small mb-0">
                <i class="bi bi-person me-1"></i> {{ $partyName }}
                <span class="mx-2">·</span>
                Current balance: <span
                    class="fw-semibold {{ $balance > 0 ? 'text-danger' : 'text-success' }}">{{ number_format($balance, 2) }}</span>
            </p>
        </div>
        <x-button href="{{ route('reports.financial.sub-ledger.due-list', ['type' => $type]) }}" variant='dark'
            class="btn btn-dark btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to Due List
        </x-button>
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
            <x-button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></x-button>
        </div>
    @endif

    {{-- ✅ Form Card --}}
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form method="POST"
                action="{{ route('reports.financial.sub-ledger.transaction.store', ['type' => $type, 'id' => $id, 'action' => $action]) }}">
                @csrf

                <div class="row g-4">
                    {{-- Party Name (Readonly) --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-muted small text-uppercase">{{ $partyTypeLabel }}</label>
                        <input class="form-control bg-light" value="{{ $partyName }}" readonly>
                    </div>

                    {{-- Current Due (Readonly) --}}
                    <div class="col-md-6">
                        <label
                            class="form-label fw-semibold text-muted small text-uppercase">{{ $isReceipt ? 'Current Due Amount' : 'Current Credit Balance' }}</label>
                        <input id="current_due" type="text"
                            class="form-control bg-light fw-bold {{ $dueAmount > 0 ? 'text-danger' : 'text-dark' }}"
                            value="{{ number_format($dueAmount, 2, '.', '') }}" readonly>
                    </div>

                    {{-- Amount Input --}}
                    <div class="col-md-6">
                        <label for="amount" class="form-label fw-semibold small text-uppercase">
                            {{ $isReceipt ? 'Received Amount' : 'Paid Amount' }} <span class="text-danger">*</span>
                        </label>
                        <div class="input-group has-validation">
                            <span class="input-group-text bg-white fw-bold">৳</span>
                            <input id="amount" name="amount" type="number" min="0.01"
                                max="{{ number_format($amountAvailable, 2, '.', '') }}" step="0.01"
                                class="form-control form-control-lg fw-bold @error('amount') is-invalid @enderror"
                                value="{{ old('amount') }}" placeholder="0.00" required>
                            @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-text small">Maximum allowed:
                            <strong>{{ number_format($amountAvailable, 2) }}</strong>
                        </div>
                    </div>

                    {{-- Due After Receipt (Only for Receipt) --}}
                    @if ($isReceipt)
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-muted small text-uppercase">Due After Receipt</label>
                            <input id="due_after_receipt" type="text" class="form-control bg-light fw-bold text-success"
                                value="{{ number_format($dueAmount, 2, '.', '') }}" readonly>
                        </div>
                    @endif

                    {{-- Payment Mode --}}
                    <div class="col-md-6">
                        <label for="payment_mode_id" class="form-label fw-semibold small text-uppercase">
                            {{ $isReceipt ? 'Received Into (Account)' : 'Paid From (Account)' }} <span
                                class="text-danger">*</span>
                        </label>
                        <select id="payment_mode_id" name="payment_mode_id"
                            class="form-select @error('payment_mode_id') is-invalid @enderror" required>
                            <option value="">-- Select payment mode --</option>
                            @foreach ($paymentModes as $mode)
                                <option value="{{ $mode->id }}" @selected(old('payment_mode_id') == $mode->id)>
                                    {{ $mode->name }} ({{ ucfirst($mode->mode_type) }}) — {{ $mode->account?->code }}
                                    {{ $mode->account?->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('payment_mode_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        @if ($paymentModes->isEmpty())
                            <div class="form-text small text-danger">
                                <i class="bi bi-exclamation-circle me-1"></i> No active payment mode found. Add one from <a
                                    href="{{ route('accounting.payment-modes.index') }}" class="fw-medium">Accounting →
                                    Payment Modes</a>.
                            </div>
                        @endif
                    </div>

                    {{-- Voucher Date --}}
                    <div class="col-md-6">
                        <label for="voucher_date" class="form-label fw-semibold small text-uppercase">
                            {{ $isReceipt ? 'Receipt Date' : 'Payment Date' }} <span class="text-danger">*</span>
                        </label>
                        <input id="voucher_date" name="voucher_date" type="date"
                            class="form-control @error('voucher_date') is-invalid @enderror"
                            value="{{ old('voucher_date', now()->toDateString()) }}" required>
                        @error('voucher_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Narration --}}
                    <div class="col-12">
                        <label for="narration" class="form-label fw-semibold small text-uppercase">Text / Narration</label>
                        <textarea id="narration" name="narration" rows="2" class="form-control @error('narration') is-invalid @enderror"
                            placeholder="Enter any additional notes (optional)...">{{ old('narration') }}</textarea>
                        @error('narration')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- ✅ Action Buttons --}}
                <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                    <x-button href="{{ route('reports.financial.sub-ledger.due-list', ['type' => $type]) }}"
                        variant="danger" class="btn btn-danger px-4">
                        Cancel
                    </x-button>
                    <x-button variant="{{ $isReceipt ? 'success' : 'danger' }}"
                        class="btn {{ $isReceipt ? 'btn-success' : 'btn-danger' }} px-4 shadow-sm" type="submit">
                        <i class="bi {{ $isReceipt ? 'bi-box-arrow-in-down' : 'bi-box-arrow-up' }} me-1"></i>
                        {{ $isReceipt ? 'Create Receipt Voucher' : 'Create Payment Voucher' }}
                    </x-button>
                </div>
            </form>
        </div>
    </div>

    {{-- ✅ JavaScript for Due Calculation --}}
    @if ($isReceipt)
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const amount = document.getElementById('amount');
                const due = parseFloat(document.getElementById('current_due').value) || 0;
                const remaining = document.getElementById('due_after_receipt');

                if (amount && remaining) {
                    amount.addEventListener('input', function() {
                        remaining.value = Math.max(0, due - (parseFloat(this.value) || 0)).toFixed(2);
                    });
                }
            });
        </script>
    @endif
@endsection
