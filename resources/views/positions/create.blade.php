@extends('layouts.app')

@section('title', 'New Position')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">New Position</h1>
        <x-button href="{{ route('positions.index') }}" variant="dark" size="sm" class="btn-dark">Back to list</x-button>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('positions.store') }}">
                @csrf
                @include('positions._form')
                <div class="d-flex gap-2">
                    <x-button variant="success" size="sm" type="submit">Save Position</x-button>
                    <x-button href="{{ route('positions.index') }}" variant="danger" size="sm">Cancel</x-button>
                </div>
            </form>
        </div>
    </div>
@endsection
