@extends('layouts.app')
@section('title', 'Visitors')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Visitors</h1>
    <div>
        <a href="{{ route('visitors.daily-report') }}" class="btn btn-outline-secondary">Daily Report</a>
        <a href="{{ route('visitors.monthly-report') }}" class="btn btn-outline-secondary">Monthly Report</a>
        <a href="{{ route('visitors.create') }}" class="btn btn-primary">Register Visitor</a>
    </div>
</div>
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
<form method="GET" class="row g-2 mb-3">
    <div class="col-md-3"><input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Name, phone or pass number..."></div>
    <div class="col-md-2"><input type="date" name="date" value="{{ request('date') }}" class="form-control"></div>
    <div class="col-auto"><button class="btn btn-secondary" type="submit">Search</button></div>
</form>
<div class="card"><div class="table-responsive">
<table class="table table-striped mb-0">
    <thead><tr><th>Pass</th><th>Name</th><th>Phone</th><th>Purpose</th><th>Person to Meet</th><th>Entry</th><th>Exit</th><th class="text-end">Actions</th></tr></thead>
    <tbody>
    @forelse($items as $item)
        <tr><td>{{ $item->pass_number }}</td><td>{{ $item->name }}</td><td>{{ $item->phone }}</td>
            <td>{{ $item->purpose }}</td><td>{{ $item->person_to_meet }}</td>
            <td>{{ $item->entry_time }}</td><td>{{ $item->exit_time ?? 'Inside' }}</td>
            <td class="text-end">
                <a href="{{ route('visitors.show', $item) }}" class="btn btn-sm btn-outline-primary">View</a>
                <a href="{{ route('visitors.pass', $item) }}" class="btn btn-sm btn-outline-secondary">Pass</a>
            </td></tr>
    @empty
        <tr><td colspan="8" class="text-center text-muted py-4">No visitors found.</td></tr>
    @endforelse
    </tbody>
</table>
</div></div>
<div class="mt-3">{{ $items->links() }}</div>
@endsection
