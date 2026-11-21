@extends('layouts.app')
@php($isReceipt = $action === 'receive')
@php($dueAmount = $isReceipt ? max((float) $balance, 0) : max((float) -$balance, 0))
@php($partyTypeLabel = ['agent' => 'Agent', 'passport_holder' => 'Passport Holder', 'vendor' => 'Vendor'][$type] ?? 'Party')
@php($partyName = $type === 'passport_holder' ? $party->full_name : $party->name)
@section('title', ($isReceipt ? 'Receive from ' : 'Pay ') . $partyTypeLabel)
@section('content')
<div class="d-flex justify-content-between align-items-start mb-3">
    <div>
        <h1 class="mb-1">{{ $isReceipt ? 'Receive from ' : 'Pay ' }}{{ $partyTypeLabel }}</h1>
        <div class="text-muted">{{ $partyName }} · Current balance: {{ number_format($balance, 2) }}</div>
    </div>
    <a href="{{ route('reports.financial.sub-ledger.due-list', ['type' => $type]) }}" class="btn btn-outline-secondary">Back to Due List</a>
</div>
@if($errors->any())
    <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
@endif
<div class="card"><div class="card-body">
    <form method="POST" action="{{ route('reports.financial.sub-ledger.transaction.store', ['type' => $type, 'id' => $id, 'action' => $action]) }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">{{ $partyTypeLabel }}</label>
            <input class="form-control" value="{{ $partyName }}" readonly>
        </div>
        <div class="mb-3">
            <label class="form-label">{{ $isReceipt ? 'Current Due Amount' : 'Current Credit Balance' }}</label>
            <input id="current_due" type="text" class="form-control" value="{{ number_format($dueAmount, 2, '.', '') }}" readonly>
        </div>
        <div class="mb-3">
            <label for="amount" class="form-label">{{ $isReceipt ? 'Received Amount' : 'Paid Amount' }}</label>
            <input id="amount" name="amount" type="number" min="0.01" max="{{ number_format($amountAvailable, 2, '.', '') }}" step="0.01" class="form-control" value="{{ old('amount') }}" required>
            <div class="form-text">Maximum amount: {{ number_format($amountAvailable, 2) }}</div>
        </div>
        @if($isReceipt)
        <div class="mb-3">
            <label class="form-label">Due After Receipt</label>
            <input id="due_after_receipt" type="text" class="form-control" value="{{ number_format($dueAmount, 2, '.', '') }}" readonly>
        </div>
        @endif
        <div class="mb-3">
            <label for="payment_mode_id" class="form-label">{{ $isReceipt ? 'Received Into (Account / Payment Mode)' : 'Paid From (Account / Payment Mode)' }}</label>
            <select id="payment_mode_id" name="payment_mode_id" class="form-select" required>
                <option value="">Select payment mode</option>
                @foreach($paymentModes as $mode)
                    <option value="{{ $mode->id }}" @selected(old('payment_mode_id') == $mode->id)>{{ $mode->name }} ({{ ucfirst($mode->mode_type) }}) — {{ $mode->account?->code }} {{ $mode->account?->name }}</option>
                @endforeach
            </select>
            @if($paymentModes->isEmpty())
                <div class="form-text">No active payment mode found. Add one from <a href="{{ route('accounting.payment-modes.index') }}">Accounting → Payment Modes</a>.</div>
            @endif
        </div>
        <div class="mb-3">
            <label for="voucher_date" class="form-label">{{ $isReceipt ? 'Receipt Date' : 'Payment Date' }}</label>
            <input id="voucher_date" name="voucher_date" type="date" class="form-control" value="{{ old('voucher_date', now()->toDateString()) }}" required>
        </div>
        <div class="mb-3">
            <label for="narration" class="form-label">Text / Narration</label>
            <textarea id="narration" name="narration" class="form-control" rows="2">{{ old('narration') }}</textarea>
        </div>
        <button class="btn {{ $isReceipt ? 'btn-success' : 'btn-danger' }}" type="submit">{{ $isReceipt ? 'Create Receipt Voucher' : 'Create Payment Voucher' }}</button>
        <a href="{{ route('reports.financial.sub-ledger.due-list', ['type' => $type]) }}" class="btn btn-secondary">Cancel</a>
    </form>
</div></div>
@if($isReceipt)
<script>
document.addEventListener('DOMContentLoaded', function () {
    const amount = document.getElementById('amount');
    const due = parseFloat(document.getElementById('current_due').value) || 0;
    const remaining = document.getElementById('due_after_receipt');
    amount.addEventListener('input', function () {
        remaining.value = Math.max(0, due - (parseFloat(this.value) || 0)).toFixed(2);
    });
});
</script>
@endif
@endsection
