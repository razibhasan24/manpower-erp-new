@extends('layouts.app')

@section('title', 'New Agent')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">New Agent</h1>

        <x-button href="{{ route('agents.index') }}" variant="dark" size="sm">
            <i class="fas fa-arrow-left me-1"></i> Back to list
        </x-button>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form method="POST" action="{{ route('agents.store') }}">
                @csrf

                @include('agents._form')

                <div class="d-flex gap-2 mt-4">
                    <x-button type="submit" variant="success">
                        <i class="fas fa-save me-1"></i> Save Agent
                    </x-button>
                    <x-button href="{{ route('agents.index') }}" variant="danger">
                        <i class="fas fa-times"></i> Cancel
                    </x-button>
                </div>
            </form>
        </div>
    </div>
@endsection
