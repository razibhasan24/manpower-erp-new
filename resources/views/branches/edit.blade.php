@extends('layouts.app')

@section('title', 'Edit Branch')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">
            <i class="fas fa-edit text-warning"></i> Edit Branch
        </h1>

        <x-button href="{{ route('branches.show', $branch) }}" variant="dark" size="sm">
            <i class="fas fa-arrow-left"></i> Back to details
        </x-button>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0">Branch Information</h5>
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('branches.update', $branch) }}">
                @csrf
                @method('PUT')

                @include('branches._form')

                <hr class="my-4">

                <div class="d-flex gap-2 justify-content-start">

                    <x-button type="submit" variant="success">
                        <i class="fas fa-save"></i> Update Branch
                    </x-button>

                    <x-button href="{{ route('branches.show', $branch) }}" variant="danger">
                        <i class="fas fa-times"></i> Cancel
                    </x-button>

                </div>
            </form>
        </div>
    </div>

@endsection
