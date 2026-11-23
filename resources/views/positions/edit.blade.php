@extends('layouts.app')

@section('title', 'Edit Position')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Edit Position</h1>
        <x-button href="{{ route('positions.show', $position) }}" variant="dark" size="sm" class="btn-dark">Back to
            details</x-button>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('positions.update', $position) }}">
                @csrf
                @method('PUT')
                @include('positions._form')
                <div class="d-flex gap-2">
                    <x-button variant="success" size="sm" type="submit">Update Position</x-button>
                    <x-button href="{{ route('positions.show', $position) }}" variant="danger"
                        size="sm">Cancel</x-button>
                </div>
            </form>
        </div>
    </div>
@endsection
