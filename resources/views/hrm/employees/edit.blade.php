@extends('layouts.app')
@section('title', 'Edit Employee')
@section('content')
<h1 class="mb-3">Edit Employee</h1>
<div class="card"><div class="card-body">
<form method="POST" action="{{ route('hrm.employees.update', $item) }}">
    @csrf
    <p class="text-muted small">The employee's name and email live on their linked user account — pick the user below.</p>
    @method('PUT')
        <button type="submit" class="btn btn-primary">Save</button>
    <a href="{{ route('hrm.employees.index') }}" class="btn btn-secondary">Cancel</a>
</form>
</div></div>
@endsection
