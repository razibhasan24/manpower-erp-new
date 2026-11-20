@extends('layouts.app')
@section('title', 'Payment Modes')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h1 class="mb-1">Payment Modes</h1>
        <p class="text-muted mb-0">Set up Cash and Bank options and link each one to its accounting ledger.</p>
    </div>
    <a href="{{ route('accounting.payment-modes.create') }}" class="btn btn-primary">Add Payment Mode</a>
</div>
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
<div class="card"><div class="table-responsive">
    <table class="table table-striped mb-0">
        <thead><tr><th>Name</th><th>Type</th><th>Linked Account</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
        <tbody>
        @forelse($items as $item)
            <tr>
                <td>{{ $item->name }}</td>
                <td>{{ ucfirst($item->mode_type) }}</td>
                <td>{{ $item->account?->code }} — {{ $item->account?->name }}</td>
                <td><x-badge :status="$item->status" /></td>
                <td class="text-end">
                    <a href="{{ route('accounting.payment-modes.edit', $item) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                    <form action="{{ route('accounting.payment-modes.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Remove this payment mode?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger" type="submit">Remove</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="5" class="text-center text-muted py-4">No payment modes added yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div></div>
<div class="mt-3">{{ $items->links() }}</div>
@endsection
