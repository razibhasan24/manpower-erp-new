@extends('layouts.app')
@section('title', 'Holiday')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Holiday</h1>
    <div>
        <a href="{{ route('hrm.holidays.index') }}" class="btn btn-secondary">Back to list</a>
        <a href="{{ route('hrm.holidays.edit', $item) }}" class="btn btn-outline-primary">Edit</a>
    </div>
</div>
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
<div class="card"><div class="card-body">
<table class="table table-bordered mb-0">
<tr><th style="width:200px">Name</th><td>{{ $item->name }}</td></tr><tr><th style="width:200px">Date</th><td>{{ $item->date }}</td></tr><tr><th style="width:200px">Type</th><td>{{ $item->type }}</td></tr>
</table>

</div></div>
@endsection
