@extends('layouts.app')
@section('title', __('Edit Employee'))
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3"><h1 class="mb-0">{{ __('Edit Employee') }}</h1><a href="{{ route('hrm.employees.show', $item) }}" class="btn btn-outline-secondary">{{ __('Back to employee') }}</a></div>
<div class="card"><div class="card-body">
    <form method="POST" action="{{ route('hrm.employees.update', $item) }}">
        @csrf
        @method('PUT')
        @include('hrm.employees._form')
        <button type="submit" class="btn btn-primary">{{ __('Save changes') }}</button>
        <a href="{{ route('hrm.employees.show', $item) }}" class="btn btn-outline-secondary">{{ __('Cancel') }}</a>
    </form>
</div></div>
@endsection
