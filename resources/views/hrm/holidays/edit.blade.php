@extends('layouts.app')
@section('title', 'Edit Holiday')
@section('content')
<h1 class="mb-3">Edit Holiday</h1>
<div class="card"><div class="card-body">
<form method="POST" action="{{ route('hrm.holidays.update', $item) }}">
    @csrf
    @method('PUT')
    <div class="mb-3"><label class="form-label">Name</label><input type="text" name="name" value="{{ old('name', $item->name ?? '') }}" class="form-control">@error('name')<div class="text-danger small">{{ $message }}</div>@enderror</div><div class="mb-3"><label class="form-label">Date</label><input type="date" name="date" value="{{ old('date', $item->date ?? '') }}" class="form-control">@error('date')<div class="text-danger small">{{ $message }}</div>@enderror</div><div class="mb-3"><label class="form-label">Type</label><input type="text" name="type" value="{{ old('type', $item->type ?? '') }}" class="form-control">@error('type')<div class="text-danger small">{{ $message }}</div>@enderror</div>
    <button type="submit" class="btn btn-primary">Save</button>
    <a href="{{ route('hrm.holidays.index') }}" class="btn btn-secondary">Cancel</a>
</form>
</div></div>
@endsection
