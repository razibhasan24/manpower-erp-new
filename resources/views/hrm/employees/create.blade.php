@extends('layouts.app')
@section('title', __('Add Employee'))
@section('content')
    <div class="d-flex justify-content-end align-items-center mb-3">
        {{-- <x-button class="mb-0">{{ __('Add Employee') }}</x-butt> --}}
        <x-button href="{{ route('hrm.employees.index') }}" variant='success' class="btn btn-success">
            {{ __('Back to employees') }}
        </x-button>
    </div>
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('hrm.employees.store') }}">
                @csrf
                @include('hrm.employees._form')
                <x-button variant="primary" type="submit" class="btn btn-primary">{{ __('Create employee') }}</x-button>
                <x-button variant="danger" href="{{ route('hrm.employees.index') }}"
                    class="btn btn-danger">{{ __('Cancel') }}</x-button>
            </form>
        </div>
    </div>
@endsection
