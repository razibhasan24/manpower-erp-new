@extends('layouts.app')
@section('title', 'Account Types')
@section('content')
<h1 class="mb-3">Account Types</h1>
<p class="text-muted">The five account types are seeded and not user-editable.</p>
<div class="card"><div class="table-responsive">
<table class="table table-striped mb-0">
    <thead><tr><th>Name</th><th>Normal Balance</th><th class="text-end">Actions</th></tr></thead>
    <tbody>
    @forelse($items as $item)
        <tr><td>{{ $item->name }}</td><td>{{ $item->normal_balance }}</td>
            <td class="text-end"><a href="{{ route('accounting.account-types.show', $item) }}" class="btn btn-sm btn-outline-primary">View</a></td></tr>
    @empty
        <tr><td colspan="3" class="text-center text-muted py-4">No account types found. Run the AccountTypeSeeder.</td></tr>
    @endforelse
    </tbody>
</table>
</div></div>
<div class="mt-3">{{ $items->links() }}</div>
@endsection
