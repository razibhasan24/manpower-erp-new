@extends('layouts.app')
@section('title', 'Attendance')
@section('content')
<h1 class="mb-3">Attendance</h1>
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
<div class="card mb-3" id="check-in"><div class="card-body">
    <form method="POST" action="{{ route('hrm.attendance.check-in') }}" class="row g-2 align-items-end">
        @csrf
        <div class="col-md-4"><label class="form-label">Employee ID</label><input type="number" name="employee_id" class="form-control" required></div>
        <div class="col-auto"><button class="btn btn-primary" type="submit">Check In</button></div>
    </form>
    <form method="POST" action="{{ route('hrm.attendance.check-out') }}" class="row g-2 align-items-end mt-2">
        @csrf
        <div class="col-md-4"><label class="form-label">Employee ID</label><input type="number" name="employee_id" class="form-control" required></div>
        <div class="col-auto"><button class="btn btn-secondary" type="submit">Check Out</button></div>
    </form>
    <form method="POST" action="{{ route('hrm.attendance.sync-biometric') }}" class="row g-2 align-items-end mt-2">
        @csrf
        <div class="col-md-2"><label class="form-label">From</label><input type="date" name="from" class="form-control" required></div>
        <div class="col-md-2"><label class="form-label">To</label><input type="date" name="to" class="form-control" required></div>
        <div class="col-auto"><button class="btn btn-outline-primary" type="submit">Sync Biometric Device</button></div>
        <div class="col-auto text-muted small">Interface only — no device is connected yet; sync reports that honestly.</div>
    </form>
</div></div>
<form method="GET" class="row g-2 mb-3">
    <div class="col-md-3"><input type="date" name="date" value="{{ request('date') }}" class="form-control"></div>
    <div class="col-md-3"><select name="status" class="form-select"><option value="">All statuses</option>@foreach(['present','late','absent','leave'] as $s)<option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>@endforeach</select></div>
    <div class="col-auto"><button class="btn btn-secondary" type="submit">Filter</button></div>
</form>
<div class="card"><div class="table-responsive">
<table class="table table-striped mb-0">
    <thead><tr><th>Employee</th><th>Date</th><th>Check In</th><th>Check Out</th><th>Status</th><th>Source</th></tr></thead>
    <tbody>
    @forelse($items as $item)
        <tr><td>{{ $item->employee->name ?: ($item->employee->user?->name ?? $item->employee->employee_code ?? $item->employee_id) }}</td><td>{{ $item->date }}</td><td>{{ $item->check_in }}</td><td>{{ $item->check_out }}</td>
            <td><x-badge :status="$item->status" /></td><td>{{ $item->source }}</td></tr>
    @empty
        <tr><td colspan="6" class="text-center text-muted py-4">No attendance records found.</td></tr>
    @endforelse
    </tbody>
</table>
</div></div>
<div class="mt-3">{{ $items->links() }}</div>
@endsection
