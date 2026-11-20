@extends('layouts.app')
@section('title', 'New Voucher')
@section('content')
<h1 class="mb-3">New Voucher</h1>
@if($errors->any())
    <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
@endif
<div class="row">
    <div class="col-lg-6">
        <div class="card mb-3"><div class="card-header">Financial Operation (§21.G)</div><div class="card-body">
            <form method="POST" action="{{ route('accounting.vouchers.operation') }}">
                @csrf
                <div class="mb-3"><label class="form-label">Operation</label>
                    <select name="operation" class="form-select" required>
                        @foreach($operations as $op)<option value="{{ $op }}">{{ ucwords(str_replace('_', ' ', $op)) }}</option>@endforeach
                    </select></div>
                <div class="mb-3"><label class="form-label">Amount</label><input type="number" step="0.01" min="0.01" name="amount" class="form-control" required></div>
                <div class="mb-3"><label class="form-label">Date</label><input type="date" name="voucher_date" value="{{ now()->toDateString() }}" class="form-control" required></div>
                <div class="mb-3"><label class="form-label">Payment Mode</label>
                    <select id="payment_mode_id" class="form-select">
                        <option value="">Choose Cash / Bank mode</option>
                        @foreach($paymentModes as $mode)<option value="{{ $mode->id }}" data-account-id="{{ $mode->account_id }}">{{ $mode->name }} ({{ ucfirst($mode->mode_type) }})</option>@endforeach
                    </select>
                    <div class="form-text"><a href="{{ route('accounting.payment-modes.index') }}">Manage payment modes</a></div>
                </div>
                <div class="mb-3"><label class="form-label">Debit Account</label>
                    <select id="operation_debit_account" name="debit_account_id" class="form-select">@foreach($accounts as $a)<option value="{{ $a->id }}">{{ $a->code }} — {{ $a->name }}</option>@endforeach</select></div>
                <div class="mb-3"><label class="form-label">Credit Account</label>
                    <select id="operation_credit_account" name="credit_account_id" class="form-select">@foreach($accounts as $a)<option value="{{ $a->id }}">{{ $a->code }} — {{ $a->name }}</option>@endforeach</select></div>
                <div class="mb-3"><label class="form-label">Narration</label><textarea name="narration" class="form-control" rows="2"></textarea></div>
                <button type="submit" class="btn btn-primary">Post Operation</button>
            </form>
        </div></div>
    </div>
    <div class="col-lg-6">
        <div class="card"><div class="card-header">Manual Journal Voucher</div><div class="card-body">
            <form method="POST" action="{{ route('accounting.vouchers.store') }}">
                @csrf
                <div class="mb-3"><label class="form-label">Voucher Type</label>
                    <select name="voucher_type" class="form-select" required>
                        @foreach($types as $t)<option value="{{ $t }}">{{ ucfirst($t) }}</option>@endforeach
                    </select></div>
                <div class="mb-3"><label class="form-label">Date</label><input type="date" name="voucher_date" value="{{ now()->toDateString() }}" class="form-control" required></div>
                <div class="mb-3"><label class="form-label">Narration</label><textarea name="narration" class="form-control" rows="2"></textarea></div>
                <p class="text-muted small">Add at least two lines. Total debit must equal total credit.</p>
                @for($i = 0; $i < 2; $i++)
                <div class="row g-2 mb-2">
                    <div class="col-6"><select name="lines[{{ $i }}][account_id]" class="form-select" required><option value="">— Account —</option>@foreach($accounts as $a)<option value="{{ $a->id }}">{{ $a->code }} — {{ $a->name }}</option>@endforeach</select></div>
                    <div class="col-3"><input type="number" step="0.01" min="0" name="lines[{{ $i }}][debit]" class="form-control" placeholder="Debit"></div>
                    <div class="col-3"><input type="number" step="0.01" min="0" name="lines[{{ $i }}][credit]" class="form-control" placeholder="Credit"></div>
                </div>
                @endfor
                <button type="submit" class="btn btn-primary">Post Voucher</button>
            </form>
        </div></div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const mode = document.getElementById('payment_mode_id');
    const operation = document.querySelector('select[name="operation"]');
    const debit = document.getElementById('operation_debit_account');
    const credit = document.getElementById('operation_credit_account');
    const debitOperations = ['daily_income', 'agent_due_collection', 'candidate_payment'];

    mode?.addEventListener('change', function () {
        const accountId = this.options[this.selectedIndex]?.dataset.accountId;
        if (!accountId) return;
        (debitOperations.includes(operation.value) ? debit : credit).value = accountId;
    });
});
</script>
@endsection
