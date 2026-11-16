@extends('layouts.app')
@section('title', 'Voucher')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>{{ $item->voucher_number }}</h1>
    <a href="{{ route('accounting.vouchers.index') }}" class="btn btn-secondary">Back to list</a>
</div>
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
<div class="card mb-3"><div class="card-body">
<table class="table table-bordered mb-0">
    <tr><th style="width:200px">Voucher Number</th><td>{{ $item->voucher_number }}</td></tr>
    <tr><th>Type</th><td>{{ ucfirst($item->voucher_type) }}</td></tr>
    <tr><th>Date</th><td>{{ $item->voucher_date }}</td></tr>
    <tr><th>Status</th><td><x-badge :status="$item->status" /></td></tr>
    <tr><th>Total Debit</th><td>{{ number_format($item->total_debit, 2) }}</td></tr>
    <tr><th>Total Credit</th><td>{{ number_format($item->total_credit, 2) }}</td></tr>
    <tr><th>Narration</th><td>{{ $item->narration }}</td></tr>
    @if($item->reversal_voucher_id)
    <tr><th>Reversed By</th><td><a href="{{ route('accounting.vouchers.show', $item->reversal_voucher_id) }}">View reversal voucher</a></td></tr>
    @endif
</table>
</div></div>
<div class="card mb-3"><div class="card-header">Journal Lines</div><div class="table-responsive">
<table class="table mb-0">
    <thead><tr><th>Account</th><th>Narration</th><th class="text-end">Debit</th><th class="text-end">Credit</th></tr></thead>
    <tbody>
    @foreach($item->journalEntry->lines ?? [] as $line)
        <tr><td>{{ $line->account->code ?? '' }} — {{ $line->account->name ?? '' }}</td><td>{{ $line->narration }}</td>
            <td class="text-end">{{ number_format($line->debit, 2) }}</td><td class="text-end">{{ number_format($line->credit, 2) }}</td></tr>
    @endforeach
    </tbody>
</table>
</div></div>
@if($item->status === 'posted')
@can('reverse', $item)
<div class="card"><div class="card-header">Reverse this voucher</div><div class="card-body">
    <p class="text-muted">Posted vouchers cannot be edited or deleted. A reversal creates a mirror-image voucher and marks this one reversed.</p>
    <form method="POST" action="{{ route('accounting.vouchers.reverse', $item) }}" onsubmit="return confirm('Reverse voucher {{ $item->voucher_number }}? This cannot be undone.')">
        @csrf
        <div class="mb-3"><label class="form-label">Reason (required)</label><input type="text" name="reason" class="form-control" required maxlength="500"></div>
        <button type="submit" class="btn btn-danger">Reverse Voucher</button>
    </form>
</div></div>
@endcan
@endif
@endsection
