@extends('layouts.app')
@section('title', 'Trial Balance')
@section('content')
<h1 class="mb-3">Trial Balance</h1>
<form method="GET" class="row g-2 mb-3">
    <div class="col-md-2"><input type="date" name="from" value="{{ $filters['from'] ?? '' }}" class="form-control" placeholder="From"></div>
    <div class="col-md-2"><input type="date" name="to" value="{{ $filters['to'] ?? '' }}" class="form-control" placeholder="To"></div>
    <div class="col-auto"><button class="btn btn-secondary" type="submit">Filter</button></div>
    <div class="col-auto">
        <a class="btn btn-outline-primary" href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}">Export PDF</a>
        <a class="btn btn-outline-primary" href="{{ request()->fullUrlWithQuery(['export' => 'excel']) }}">Export Excel</a>
    </div>
</form>

@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
<div class="card"><div class="table-responsive">
<table class="table table-striped mb-0">
    <thead><tr><th>Code</th><th>Account</th><th class="text-end">Debit</th><th class="text-end">Credit</th></tr></thead>
    <tbody>
    @forelse($rows as $row)
        <tr><td>{{ $row['code'] }}</td><td>{{ $row['name'] }}</td>
            <td class="text-end">{{ number_format($row['debit'], 2) }}</td><td class="text-end">{{ number_format($row['credit'], 2) }}</td></tr>
    @empty
        <tr><td colspan="4" class="text-center text-muted py-4">No posted entries in this period.</td></tr>
    @endforelse
    </tbody>
    <tfoot><tr><th colspan="2">Total</th><th class="text-end">{{ number_format($total_debit, 2) }}</th><th class="text-end">{{ number_format($total_credit, 2) }}</th></tr></tfoot>
</table>
</div></div>
<p class="mt-2">@if($balanced)<span class="badge bg-success">Balanced</span>@else<span class="badge bg-danger">Out of balance — investigate immediately</span>@endif</p>

@endsection
