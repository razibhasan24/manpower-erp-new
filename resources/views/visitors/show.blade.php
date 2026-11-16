@extends('layouts.app')
@section('title', 'Visitor')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>{{ $item->name }}</h1>
    <div>
        <a href="{{ route('visitors.pass', $item) }}" class="btn btn-outline-primary">Print Pass</a>
        <a href="{{ route('visitors.index') }}" class="btn btn-secondary">Back to list</a>
    </div>
</div>
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
<div class="card"><div class="card-body">
<table class="table table-bordered mb-0">
    <tr><th style="width:200px">Pass Number</th><td>{{ $item->pass_number }}</td></tr>
    <tr><th>Name</th><td>{{ $item->name }}</td></tr>
    <tr><th>Phone</th><td>{{ $item->phone }}</td></tr>
    <tr><th>Purpose</th><td>{{ $item->purpose }}</td></tr>
    <tr><th>Person to Meet</th><td>{{ $item->person_to_meet }}</td></tr>
    <tr><th>Entry Time</th><td>{{ $item->entry_time }}</td></tr>
    <tr><th>Exit Time</th><td>{{ $item->exit_time ?? 'Still inside' }}</td></tr>
</table>
@if(!$item->exit_time)
<form method="POST" action="{{ route('visitors.check-out', $item) }}" class="mt-3">
    @csrf
    <button type="submit" class="btn btn-success">Check Out Visitor</button>
</form>
@endif
</div></div>
@endsection
