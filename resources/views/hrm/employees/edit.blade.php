@extends('layouts.app')
@section('title', __('Edit Employee'))
@section('content')
    <div class="d-flex justify-content-end align-items-center mb-3">
        {{-- <x-button variant="warning" class="mb-0">{{ __('Edit Employee') }}</x-button> --}}
        <x-button
            href="{{ route('hrm.employees.show', $item) }}" variant="dark"
            class="btn btn-dark">{{ __('Back to employee') }}</x-button>
    </div>
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('hrm.employees.update', $item) }}">
                @csrf
                @method('PUT')
                @include('hrm.employees._form')
                <x-button variant="primary" type="submit" class="btn btn-primary">{{ __('Save changes') }}</x-button>
                <x-button variant="danger" href="{{ route('hrm.employees.show', $item) }}"
                    class="btn btn-danger">{{ __('Cancel') }}</x-button>
            </form>
        </div>
    </div>
@endsection
