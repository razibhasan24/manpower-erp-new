@extends('layouts.app')
@section('title', 'Salary Structure')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Salary Structure</h1>
    <div>
        <a href="{{ route('hrm.salary-structures.index') }}" class="btn btn-secondary">Back to list</a>
        <a href="{{ route('hrm.salary-structures.edit', $item) }}" class="btn btn-outline-primary">Edit</a>
    </div>
</div>
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
<div class="card"><div class="card-body">
<table class="table table-bordered mb-0">
<tr><th style="width:200px">Employee</th><td>{{ $item->employee->user->name ?? '' }}</td></tr><tr><th style="width:200px">Basic</th><td>{{ number_format($item->basic, 2) }}</td></tr><tr><th style="width:200px">Allowances</th><td>{{ json_encode($item->allowances) }}</td></tr><tr><th style="width:200px">Deductions</th><td>{{ json_encode($item->deductions) }}</td></tr><tr><th style="width:200px">Effective From</th><td>{{ $item->effective_from }}</td></tr>
</table>

</div></div>
@endsection
