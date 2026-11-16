@extends('layouts.app')
@section('name', 'Designation')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Designation</h1>
    <div>
        <a href="{{ route('hrm.designations.index') }}" class="btn btn-secondary">Back to list</a>
        <a href="{{ route('hrm.designations.edit', $item) }}" class="btn btn-outline-primary">Edit</a>
    </div>
</div>
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
<div class="card"><div class="card-body">
<table class="table table-bordered mb-0">
<tr><th style="width:200px">Name</th><td>{{ $item->name }}</td></tr><tr><th style="width:200px">Department</th><td>{{ $item->department->name ?? '' }}</td></tr><tr><th style="width:200px">Status</th><td><x-badge :status="$item->status" /></td></tr>
</table>

</div></div>
@endsection
