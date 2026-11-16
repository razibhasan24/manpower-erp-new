@extends('layouts.app')
@section('title', 'Compute Payroll')
@section('content')
<h1 class="mb-3">Compute Payroll</h1>
<div class="card"><div class="card-body">
<form method="POST" action="{{ route('hrm.payroll.store') }}">
    @csrf
    <div class="mb-3"><label class="form-label">Employee</label>
        <select name="employee_id" class="form-select" required>@foreach($employees as $e)<option value="{{ $e->id }}">{{ $e->user->name ?? $e->employee_code }} ({{ $e->employee_code }})</option>@endforeach</select></div>
    <div class="mb-3"><label class="form-label">Month</label><input type="month" name="month" value="{{ now()->format('Y-m') }}" class="form-control" required></div>
    <div class="row">
        <div class="col-md-4 mb-3"><label class="form-label">Bonus</label><input type="number" step="0.01" min="0" name="bonus" class="form-control" value="0"></div>
        <div class="col-md-4 mb-3"><label class="form-label">Overtime Hours</label><input type="number" step="0.01" min="0" name="overtime" class="form-control" value="0"></div>
        <div class="col-md-4 mb-3"><label class="form-label">Overtime Amount</label><input type="number" step="0.01" min="0" name="overtime_amount" class="form-control" value="0"></div>
    </div>
    <p class="text-muted small">Basic, allowances and deductions come from the employee's effective salary structure. The payroll is created as a draft; approving it posts the Journal Voucher.</p>
    <button type="submit" class="btn btn-primary">Compute Draft Payroll</button>
</form>
</div></div>
@endsection
