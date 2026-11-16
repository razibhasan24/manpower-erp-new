@extends('layouts.app')
@section('title', 'General Ledger')
@section('content')
<h1 class="mb-3">General Ledger</h1>
<form method="GET" class="row g-2 mb-3">
    <div class="col-md-2"><input type="date" name="from" value="{{ $filters['from'] ?? '' }}" class="form-control" placeholder="From"></div>
    <div class="col-md-2"><input type="date" name="to" value="{{ $filters['to'] ?? '' }}" class="form-control" placeholder="To"></div>
    <div class="col-auto"><button class="btn btn-secondary" type="submit">Filter</button></div>
    <div class="col-auto">
        <a class="btn btn-outline-primary" href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}">Export PDF</a>
        <a class="btn btn-outline-primary" href="{{ request()->fullUrlWithQuery(['export' => 'excel']) }}">Export Excel</a>
    </div>
</form>

@forelse($accounts as $account)
<div class="card mb-3"><div class="card-header">{{ $account['code'] }} — {{ $account['name'] }}</div><div class="table-responsive">
<table class="table mb-0">
    <thead><tr><th>Date</th><th>Voucher</th><th>Narration</th><th class="text-end">Debit</th><th class="text-end">Credit</th><th class="text-end">Balance</th></tr></thead>
    <tbody>
    @foreach($account['lines'] as $line)
        <tr><td>{{ $line['date'] }}</td><td>{{ $line['voucher_number'] }}</td><td>{{ $line['narration'] }}</td>
            <td class="text-end">{{ number_format($line['debit'], 2) }}</td><td class="text-end">{{ number_format($line['credit'], 2) }}</td>
            <td class="text-end">{{ number_format($line['balance'], 2) }}</td></tr>
    @endforeach
    </tbody>
    <tfoot><tr><th colspan="3">Total</th><th class="text-end">{{ number_format($account['total_debit'], 2) }}</th><th class="text-end">{{ number_format($account['total_credit'], 2) }}</th><th class="text-end">{{ number_format($account['closing_balance'], 2) }}</th></tr></tfoot>
</table>
</div></div>
@empty
<p class="text-muted">No posted entries in this period.</p>
@endforelse

@endsection
