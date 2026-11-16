@extends('layouts.app')
@section('title', 'Journal Entries')
@section('content')
<h1 class="mb-3">Journal Entries</h1>
<p class="text-muted">Read-only. Journal entries are created only when a voucher posts.</p>
<div class="card"><div class="table-responsive">
<table class="table table-striped mb-0">
    <thead><tr><th>Date</th><th>Voucher</th><th>Narration</th><th class="text-end">Actions</th></tr></thead>
    <tbody>
    @forelse($items as $item)
        <tr><td>{{ $item->entry_date }}</td><td>{{ $item->voucher->voucher_number ?? '' }}</td><td>{{ $item->narration }}</td>
            <td class="text-end"><a href="{{ route('accounting.journal-entries.show', $item) }}" class="btn btn-sm btn-outline-primary">View</a></td></tr>
    @empty
        <tr><td colspan="4" class="text-center text-muted py-4">No journal entries found.</td></tr>
    @endforelse
    </tbody>
</table>
</div></div>
<div class="mt-3">{{ $items->links() }}</div>
@endsection
