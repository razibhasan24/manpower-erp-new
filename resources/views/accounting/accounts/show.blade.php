@extends('layouts.app')
@section('title', 'Account')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Account</h1>
    <div>
        <a href="{{ route('accounting.accounts.index') }}" class="btn btn-secondary">Back to list</a>
        <a href="{{ route('accounting.accounts.edit', $item) }}" class="btn btn-outline-primary">Edit</a>
    </div>
</div>
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
<div class="card"><div class="card-body">
<table class="table table-bordered mb-0">
<tr><th style="width:200px">Code</th><td>{{ $item->code }}</td></tr><tr><th style="width:200px">Name</th><td>{{ $item->name }}</td></tr><tr><th style="width:200px">Parent</th><td>{{ $item->parent->name ?? '—' }}</td></tr><tr><th style="width:200px">Account Type</th><td>{{ $item->type->name ?? '' }}</td></tr><tr><th style="width:200px">Normal Balance</th><td>{{ $item->normal_balance }}</td></tr><tr><th style="width:200px">Status</th><td><x-badge :status="$item->status" /></td></tr>
</table>

</div></div>
@endsection
