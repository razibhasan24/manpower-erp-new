@extends('layouts.app')
@section('title', 'Register Visitor')
@section('content')
<h1 class="mb-3">Register Visitor</h1>
<div class="card"><div class="card-body">
<form method="POST" action="{{ route('visitors.store') }}">
    @csrf
    <div class="mb-3"><label class="form-label">Name</label><input type="text" name="name" value="{{ old('name') }}" class="form-control" required>@error('name')<div class="text-danger small">{{ $message }}</div>@enderror</div>
    <div class="mb-3"><label class="form-label">Phone</label><input type="text" name="phone" value="{{ old('phone') }}" class="form-control">@error('phone')<div class="text-danger small">{{ $message }}</div>@enderror</div>
    <div class="mb-3"><label class="form-label">Purpose</label><textarea name="purpose" class="form-control" rows="2" required>{{ old('purpose') }}</textarea>@error('purpose')<div class="text-danger small">{{ $message }}</div>@enderror</div>
    <div class="mb-3"><label class="form-label">Person to Meet</label><input type="text" name="person_to_meet" value="{{ old('person_to_meet') }}" class="form-control">@error('person_to_meet')<div class="text-danger small">{{ $message }}</div>@enderror</div>
    <button type="submit" class="btn btn-primary">Register &amp; Issue Pass</button>
    <a href="{{ route('visitors.index') }}" class="btn btn-secondary">Cancel</a>
</form>
</div></div>
@endsection
