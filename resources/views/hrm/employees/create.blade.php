@extends('layouts.app')
@section('title', __('Add Employee'))
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3"><h1 class="mb-0">{{ __('Add Employee') }}</h1><a href="{{ route('hrm.employees.index') }}" class="btn btn-outline-secondary">{{ __('Back to employees') }}</a></div>
<div class="card"><div class="card-body">
    <form method="POST" action="{{ route('hrm.employees.store') }}">
        @csrf
        @include('hrm.employees._form')
        <button type="submit" class="btn btn-primary">{{ __('Create employee') }}</button>
        <a href="{{ route('hrm.employees.index') }}" class="btn btn-outline-secondary">{{ __('Cancel') }}</a>
    </form>
</div></div>
@endsection
