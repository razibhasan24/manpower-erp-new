@extends('layouts.app')
@section('title', 'Payroll')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Payroll — {{ $item->employee->user->name ?? '' }} ({{ $item->month }})</h1>
    <a href="{{ route('hrm.payroll.index') }}" class="btn btn-secondary">Back to list</a>
</div>
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
<div class="card mb-3"><div class="card-body">
<table class="table table-bordered mb-0">
    <tr><th style="width:200px">Employee</th><td>{{ $item->employee->user->name ?? $item->employee->employee_code ?? $item->employee_id }}</td></tr>
    <tr><th>Month</th><td>{{ $item->month }}</td></tr>
    <tr><th>Gross</th><td>{{ number_format($item->gross, 2) }}</td></tr>
    <tr><th>Bonus</th><td>{{ number_format($item->bonus, 2) }}</td></tr>
    <tr><th>Overtime</th><td>{{ $item->overtime }} hrs</td></tr>
    <tr><th>Deductions</th><td>{{ number_format($item->deductions, 2) }}</td></tr>
    <tr><th>Net</th><td><strong>{{ number_format($item->net, 2) }}</strong></td></tr>
    <tr><th>Status</th><td><x-badge :status="$item->status" /></td></tr>
    @if($item->voucher_id)
    <tr><th>Journal Voucher</th><td><a href="{{ route('accounting.vouchers.show', $item->voucher_id) }}">{{ $item->voucher->voucher_number ?? $item->voucher_id }}</a></td></tr>
    @endif
    @if($item->paid_at)
    <tr><th>Paid At</th><td>{{ $item->paid_at }}</td></tr>
    @endif
</table>
</div></div>
@if($item->status === 'draft')
@can('approve', $item)
<form method="POST" action="{{ route('hrm.payroll.approve', $item) }}" class="mb-3">
    @csrf
    <button type="submit" class="btn btn-success" onclick="return confirm('Approve and post this payroll as a Journal Voucher?')">Approve &amp; Post Voucher</button>
</form>
@endcan
@endif
@if($item->status === 'approved')
@can('pay', $item)
<div class="card"><div class="card-header">Pay Salary</div><div class="card-body">
<form method="POST" action="{{ route('hrm.payroll.pay', $item) }}">
    @csrf
    <div class="mb-3"><label class="form-label">Cash / Bank Account ID</label><input type="number" name="account_id" class="form-control" required></div>
    <div class="mb-3"><label class="form-label">Payment Date</label><input type="date" name="date" value="{{ now()->toDateString() }}" class="form-control"></div>
    <button type="submit" class="btn btn-success" onclick="return confirm('Pay net salary of {{ number_format($item->net, 2) }}?')">Pay Salary</button>
</form>
</div></div>
@endcan
@endif
@endsection
