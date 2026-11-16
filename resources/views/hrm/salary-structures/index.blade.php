@extends('layouts.app')
@section('title', 'Salary Structures')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Salary Structures</h1>
    <a href="{{ route('hrm.salary-structures.create') }}" class="btn btn-primary">Add Salary Structure</a>
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
    <thead><tr><th>Employee</th><th>Basic</th><th>Effective From</th><th class="text-end">Actions</th></tr></thead>
    <tbody>
    @forelse($items as $item)
        <tr><td>{{ $item->employee->user->name ?? '' }}</td><td>{{ number_format($item->basic, 2) }}</td><td>{{ $item->effective_from }}</td>
            <td class="text-end">
                <a href="{{ route('hrm.salary-structures.show', $item) }}" class="btn btn-sm btn-outline-primary">View</a>
                <a href="{{ route('hrm.salary-structures.edit', $item) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
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
