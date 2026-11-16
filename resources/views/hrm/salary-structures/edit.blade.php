@extends('layouts.app')
@section('title', 'Edit Salary Structure')
@section('content')
<h1 class="mb-3">Edit Salary Structure</h1>
<div class="card"><div class="card-body">
<form method="POST" action="{{ route('hrm.salary-structures.update', $item) }}">
    @csrf
    @method('PUT')
    <div class="mb-3"><label class="form-label">Employee</label><select name="employee_id" class="form-select"><option value="">— Select —</option>@foreach($employees as $opt)<option value="{{ $opt->id }}" {{ (string) old('employee_id', $item->employee_id ?? '') === (string) $opt->id ? 'selected' : '' }}>{{ $opt->name }}</option>@endforeach</select>@error('employee_id')<div class="text-danger small">{{ $message }}</div>@enderror</div><div class="mb-3"><label class="form-label">Basic Salary</label><input type="number" step="0.01" name="basic" value="{{ old('basic', $item->basic ?? '') }}" class="form-control">@error('basic')<div class="text-danger small">{{ $message }}</div>@enderror</div><div class="mb-3"><label class="form-label">Allowance — House Rent</label><input type="number" step="0.01" name="allowances[house_rent]" value="{{ old('allowances[house_rent]', $item->allowances[house_rent] ?? '') }}" class="form-control">@error('allowances[house_rent]')<div class="text-danger small">{{ $message }}</div>@enderror</div><div class="mb-3"><label class="form-label">Allowance — Transport</label><input type="number" step="0.01" name="allowances[transport]" value="{{ old('allowances[transport]', $item->allowances[transport] ?? '') }}" class="form-control">@error('allowances[transport]')<div class="text-danger small">{{ $message }}</div>@enderror</div><div class="mb-3"><label class="form-label">Deduction — Tax</label><input type="number" step="0.01" name="deductions[tax]" value="{{ old('deductions[tax]', $item->deductions[tax] ?? '') }}" class="form-control">@error('deductions[tax]')<div class="text-danger small">{{ $message }}</div>@enderror</div><div class="mb-3"><label class="form-label">Deduction — Provident Fund</label><input type="number" step="0.01" name="deductions[provident_fund]" value="{{ old('deductions[provident_fund]', $item->deductions[provident_fund] ?? '') }}" class="form-control">@error('deductions[provident_fund]')<div class="text-danger small">{{ $message }}</div>@enderror</div><div class="mb-3"><label class="form-label">Effective From</label><input type="date" name="effective_from" value="{{ old('effective_from', $item->effective_from ?? '') }}" class="form-control">@error('effective_from')<div class="text-danger small">{{ $message }}</div>@enderror</div>
    <button type="submit" class="btn btn-primary">Save</button>
    <a href="{{ route('hrm.salary-structures.index') }}" class="btn btn-secondary">Cancel</a>
</form>
</div></div>
@endsection
