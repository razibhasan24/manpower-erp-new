@extends('layouts.app')
@section('title', 'Chart of Accounts')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Chart of Accounts</h1>
    <div class="d-flex gap-2">
        <a href="{{ route('accounting.payment-modes.index') }}" class="btn btn-outline-primary">Payment Modes</a>
        <a href="{{ route('accounting.accounts.create') }}" class="btn btn-primary">Add Account</a>
    </div>
</div>
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
<form method="GET" class="row g-2 mb-3">
    <div class="col-md-4"><input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search..."></div>
    <div class="col-auto"><button class="btn btn-secondary" type="submit">Search</button></div>
</form>
<div class="card"><div class="table-responsive">
<table class="table table-striped mb-0">
    <thead><tr><th>Code</th><th>Name</th><th>Type</th><th>Normal Balance</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
    <tbody>
    @forelse($items as $item)
        <tr><td>{{ $item->code }}</td><td>{{ $item->name }}</td><td>{{ $item->type->name ?? '' }}</td><td>{{ $item->normal_balance }}</td><td><x-badge :status="$item->status" /></td>
            <td class="text-end">
                <a href="{{ route('accounting.accounts.show', $item) }}" class="btn btn-sm btn-outline-primary">View</a>
                <a href="{{ route('accounting.accounts.edit', $item) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
            </td>
        </tr>
    @empty
        <tr><td colspan="99" class="text-center text-muted py-4">No records found.</td></tr>
    @endforelse
    </tbody>
</table>
</div></div>
<div class="mt-3">{{ $items->links() }}</div>
<p class="text-muted small">{{ $items->total() }} record(s) found.</p>
@endsection
