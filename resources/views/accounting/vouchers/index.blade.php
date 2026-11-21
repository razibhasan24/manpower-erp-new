@extends('layouts.app')
@section('title', 'Vouchers')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Vouchers</h1>
    @can('create', \App\Models\Voucher::class)<a href="{{ route('accounting.vouchers.create') }}" class="btn btn-primary">New Voucher</a>@endcan
</div>
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
<form method="GET" class="row g-2 mb-3">
    <div class="col-md-3"><input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Voucher number..."></div>
    <div class="col-md-2"><select name="voucher_type" class="form-select"><option value="">All types</option>@foreach(['payment','receipt','journal','contra'] as $t)<option value="{{ $t }}" {{ request('voucher_type') === $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>@endforeach</select></div>
    <div class="col-md-2"><select name="status" class="form-select"><option value="">All statuses</option>@foreach(['draft','posted','reversed','cancelled'] as $s)<option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>@endforeach</select></div>
    <div class="col-md-2"><input type="date" name="from" value="{{ request('from') }}" class="form-control"></div>
    <div class="col-md-2"><input type="date" name="to" value="{{ request('to') }}" class="form-control"></div>
    <div class="col-auto"><button class="btn btn-secondary" type="submit">Filter</button></div>
</form>
<div class="card"><div class="table-responsive">
<table class="table table-striped mb-0">
    <thead><tr><th>Voucher No.</th><th>Type</th><th>Date</th><th class="text-end">Debit</th><th class="text-end">Credit</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
    <tbody>
    @forelse($items as $item)
        <tr>
            <td>{{ $item->voucher_number }}</td><td>{{ ucfirst($item->voucher_type) }}</td><td>{{ $item->voucher_date }}</td>
            <td class="text-end">{{ number_format($item->total_debit, 2) }}</td>
            <td class="text-end">{{ number_format($item->total_credit, 2) }}</td>
            <td><x-badge :status="$item->status" /></td>
            <td class="text-end"><a href="{{ route('accounting.vouchers.show', $item) }}" class="btn btn-sm btn-outline-primary">View</a></td>
        </tr>
    @empty
        <tr><td colspan="7" class="text-center text-muted py-4">No vouchers found.</td></tr>
    @endforelse
    </tbody>
</table>
</div></div>
<div class="mt-3">{{ $items->links() }}</div>
<p class="text-muted small">{{ $items->total() }} voucher(s) found.</p>
@endsection
