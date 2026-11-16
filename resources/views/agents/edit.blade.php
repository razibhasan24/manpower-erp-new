@extends('layouts.app')

@section('title', 'Edit Agent')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Edit Agent</h1>

        <x-button href="{{ route('agents.show', $agent) }}" variant="dark" size="sm">
            <i class="fas fa-arrow-left me-1"></i> Back to details
        </x-button>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('agents.update', $agent) }}">
                @csrf
                @method('PUT')

                @include('agents._form')

                <div class="d-flex gap-2">
                    <x-button type="submit" variant="success">
                        <i class="fas fa-save me-1"></i> Update Agent
                    </x-button>

                    <a href="{{ route('agents.show', $agent) }}" class="btn btn btn-danger">
                        <i class="fas fa-times me-1"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
