@extends('layouts.app')
@section('title', 'Leave')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Leave</h1>
    <div>
        <a href="{{ route('hrm.leaves.index') }}" class="btn btn-secondary">Back to list</a>
        <a href="{{ route('hrm.leaves.edit', $item) }}" class="btn btn-outline-primary">Edit</a>
    </div>
</div>
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
<div class="card"><div class="card-body">
<table class="table table-bordered mb-0">
<tr><th style="width:200px">Employee</th><td>{{ $item->employee->user->name ?? '' }}</td></tr><tr><th style="width:200px">Type</th><td>{{ $item->type }}</td></tr><tr><th style="width:200px">From</th><td>{{ $item->start_date }}</td></tr><tr><th style="width:200px">To</th><td>{{ $item->end_date }}</td></tr><tr><th style="width:200px">Reason</th><td>{{ $item->reason }}</td></tr><tr><th style="width:200px">Status</th><td><x-badge :status="$item->status" /></td></tr>
</table>

</div></div>
@endsection
