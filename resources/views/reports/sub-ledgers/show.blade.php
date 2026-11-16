@extends('layouts.app')
@section('title', 'Sub-Ledger')
@section('content')
<h1 class="mb-3">{{ ucwords(str_replace('_', ' ', $type)) }} Ledger — #{{ $id }}</h1>
<form method="GET" class="row g-2 mb-3">
    <div class="col-md-2"><input type="date" name="from" value="{{ $filters['from'] ?? '' }}" class="form-control"></div>
    <div class="col-md-2"><input type="date" name="to" value="{{ $filters['to'] ?? '' }}" class="form-control"></div>
    <div class="col-auto"><button class="btn btn-secondary" type="submit">Filter</button></div>
</form>
<div class="card mb-3"><div class="card-body d-flex justify-content-between">
    <span>Opening: <strong>{{ number_format($ledger['opening_balance'], 2) }}</strong></span>
    <span>Debit: <strong>{{ number_format($ledger['total_debit'], 2) }}</strong></span>
    <span>Credit: <strong>{{ number_format($ledger['total_credit'], 2) }}</strong></span>
    <span>Closing: <strong>{{ number_format($ledger['closing_balance'], 2) }}</strong></span>
</div></div>
<div class="card"><div class="table-responsive">
<table class="table mb-0">
    <thead><tr><th>Date</th><th>Voucher</th><th>Account</th><th>Narration</th><th class="text-end">Debit</th><th class="text-end">Credit</th><th class="text-end">Balance</th></tr></thead>
    <tbody>
    @forelse($ledger['lines'] as $line)
        <tr><td>{{ $line['date'] }}</td><td>{{ $line['voucher_number'] }}</td><td>{{ $line['account'] }}</td><td>{{ $line['narration'] }}</td>
            <td class="text-end">{{ number_format($line['debit'], 2) }}</td><td class="text-end">{{ number_format($line['credit'], 2) }}</td>
            <td class="text-end">{{ number_format($line['balance'], 2) }}</td></tr>
    @empty
        <tr><td colspan="7" class="text-center text-muted py-4">No ledger entries in this period.</td></tr>
    @endforelse
    </tbody>
</table>
</div></div>
@endsection
