@extends('layouts.app')
@section('title', 'Day Book')
@section('content')
<h1 class="mb-3">Day Book</h1>
<form method="GET" class="row g-2 mb-3">
    <div class="col-md-2"><input type="date" name="from" value="{{ $filters['from'] ?? '' }}" class="form-control" placeholder="From"></div>
    <div class="col-md-2"><input type="date" name="to" value="{{ $filters['to'] ?? '' }}" class="form-control" placeholder="To"></div>
    <div class="col-auto"><button class="btn btn-secondary" type="submit">Filter</button></div>
    <div class="col-auto">
        <a class="btn btn-outline-primary" href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}">Export PDF</a>
        <a class="btn btn-outline-primary" href="{{ request()->fullUrlWithQuery(['export' => 'excel']) }}">Export Excel</a>
    </div>
</form>

<div class="card"><div class="table-responsive">
<table class="table table-striped mb-0">
    <thead><tr><th>Date</th><th>Voucher</th><th>Type</th><th>Narration</th><th class="text-end">Debit</th><th class="text-end">Credit</th></tr></thead>
    <tbody>
    @forelse($vouchers as $v)
        <tr><td>{{ $v['voucher_date'] }}</td><td>{{ $v['voucher_number'] }}</td><td>{{ ucfirst($v['voucher_type']) }}</td><td>{{ $v['narration'] }}</td>
            <td class="text-end">{{ number_format($v['total_debit'], 2) }}</td><td class="text-end">{{ number_format($v['total_credit'], 2) }}</td></tr>
    @empty
        <tr><td colspan="6" class="text-center text-muted py-4">No posted vouchers in this period.</td></tr>
    @endforelse
    </tbody>
    <tfoot><tr><th colspan="4">Total ({{ $count }} voucher(s))</th><th class="text-end">{{ number_format($total_debit, 2) }}</th><th class="text-end">{{ number_format($total_credit, 2) }}</th></tr></tfoot>
</table>
</div></div>

@endsection
