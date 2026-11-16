@extends('layouts.app')
@section('title', 'Departments')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Departments</h1>
    <a href="{{ route('hrm.departments.create') }}" class="btn btn-primary">Add Department</a>
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
    <thead><tr><th>Name</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
    <tbody>
    @forelse($items as $item)
        <tr><td>{{ $item->name }}</td><td><x-badge :status="$item->status" /></td>
            <td class="text-end">
                <a href="{{ route('hrm.departments.show', $item) }}" class="btn btn-sm btn-outline-primary">View</a>
                <a href="{{ route('hrm.departments.edit', $item) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
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
