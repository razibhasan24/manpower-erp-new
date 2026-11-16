@extends('layouts.app')
@section('title', 'Payroll')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Payroll</h1>
    <a href="{{ route('hrm.payroll.create') }}" class="btn btn-primary">Compute Payroll</a>
</div>
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
<form method="GET" class="row g-2 mb-3">
    <div class="col-md-2"><input type="month" name="month" value="{{ request('month') }}" class="form-control"></div>
    <div class="col-md-2"><select name="status" class="form-select"><option value="">All statuses</option>@foreach(['draft','approved','paid'] as $s)<option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>@endforeach</select></div>
    <div class="col-auto"><button class="btn btn-secondary" type="submit">Filter</button></div>
</form>
<div class="card"><div class="table-responsive">
<table class="table table-striped mb-0">
    <thead><tr><th>Employee</th><th>Month</th><th class="text-end">Gross</th><th class="text-end">Deductions</th><th class="text-end">Net</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
    <tbody>
    @forelse($items as $item)
        <tr><td>{{ $item->employee->user->name ?? $item->employee->employee_code ?? $item->employee_id }}</td><td>{{ $item->month }}</td>
            <td class="text-end">{{ number_format($item->gross, 2) }}</td>
            <td class="text-end">{{ number_format($item->deductions, 2) }}</td>
            <td class="text-end">{{ number_format($item->net, 2) }}</td>
            <td><x-badge :status="$item->status" /></td>
            <td class="text-end"><a href="{{ route('hrm.payroll.show', $item) }}" class="btn btn-sm btn-outline-primary">View</a></td></tr>
    @empty
        <tr><td colspan="7" class="text-center text-muted py-4">No payroll records found.</td></tr>
    @endforelse
    </tbody>
</table>
</div></div>
<div class="mt-3">{{ $items->links() }}</div>
@endsection
