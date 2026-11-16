@extends('layouts.app')

@section('title', 'New Branch')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">New Branch</h1>

        <x-button href="{{ route('branches.index') }}" variant="dark" size="sm">
            <i class="fas fa-arrow-left"></i> Back to list
        </x-button>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('branches.store') }}">
                @csrf

                @include('branches._form')

                <div class="d-flex gap-2 mt-3">

                    <x-button type="submit" variant="success">
                        <i class="fas fa-save"></i> Save Branch
                    </x-button>

                    <x-button href="{{ route('branches.index') }}" variant="danger">
                        <i class="fas fa-times"></i> Cancel
                    </x-button>

                </div>
            </form>
        </div>
    </div>
@endsection
