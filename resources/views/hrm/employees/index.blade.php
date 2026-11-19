@extends('layouts.app')
@section('title', 'Employees')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Employees</h1>
    @can('employee.create')<a href="{{ route('hrm.employees.create') }}" class="btn btn-primary">Add Employee</a>@endcan
</div>
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
<form method="GET" class="row g-2 mb-3">
    <div class="col-md-4"><input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Employee code, name or email..."></div>
    <div class="col-md-3"><select name="status" class="form-select"><option value="">All statuses</option>@foreach(['active','inactive','terminated'] as $status)<option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>@endforeach</select></div>
    <div class="col-auto"><button class="btn btn-secondary" type="submit">Search</button></div>
    <div class="col-auto"><a href="{{ route('hrm.employees.index') }}" class="btn btn-outline-secondary">Clear</a></div>
</form>
<div class="card"><div class="table-responsive">
<table class="table table-striped mb-0">
    <thead><tr><th>Code</th><th>Name</th><th>Department</th><th>Designation</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
    <tbody>
    @forelse($items as $item)
        <tr><td>{{ $item->employee_code }}</td><td>{{ $item->name ?: ($item->user?->name ?? '—') }}</td><td>{{ $item->department?->name ?? '—' }}</td><td>{{ $item->designation?->name ?? '—' }}</td><td><x-badge :status="$item->status" /></td>
            <td class="text-end">
                <a href="{{ route('hrm.employees.show', $item) }}" class="btn btn-sm btn-outline-primary">View</a>
                @can('employee.update')<a href="{{ route('hrm.employees.edit', $item) }}" class="btn btn-sm btn-outline-secondary">Edit</a>@endcan
                @can('employee.delete')<form method="POST" action="{{ route('hrm.employees.destroy', $item) }}" class="d-inline" onsubmit="return confirm('Deactivate this employee?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" type="submit">Deactivate</button></form>@endcan
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
