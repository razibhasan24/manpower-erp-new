@extends('layouts.app')
@section('title', 'Employee')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Employee</h1>
    <div>
        <a href="{{ route('hrm.employees.index') }}" class="btn btn-secondary">Back to list</a>
        @can('employee.update')<a href="{{ route('hrm.employees.edit', $item) }}" class="btn btn-outline-primary">Edit</a>@endcan
    </div>
</div>
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
<div class="card"><div class="card-body">
<table class="table table-bordered mb-0">
<tr><th style="width:200px">Name</th><td>{{ $item->name ?: ($item->user?->name ?? '—') }}</td></tr><tr><th style="width:200px">Employee Code</th><td>{{ $item->employee_code }}</td></tr><tr><th style="width:200px">Email</th><td>{{ $item->user?->email ?? '—' }}</td></tr><tr><th style="width:200px">Phone</th><td>{{ $item->phone ?: '—' }}</td></tr><tr><th style="width:200px">Branch</th><td>{{ $item->branch?->name ?? '—' }}</td></tr><tr><th style="width:200px">Department</th><td>{{ $item->department?->name ?? '—' }}</td></tr><tr><th style="width:200px">Designation</th><td>{{ $item->designation?->name ?? '—' }}</td></tr><tr><th style="width:200px">Address</th><td>{{ $item->address ?: '—' }}</td></tr><tr><th style="width:200px">Joining Date</th><td>{{ $item->joining_date?->format('Y-m-d') ?? '—' }}</td></tr><tr><th style="width:200px">Salary</th><td>{{ number_format($item->salary ?? 0, 2) }}</td></tr><tr><th style="width:200px">Status</th><td><x-badge :status="$item->status" /></td></tr>
</table>

</div></div>
@endsection
