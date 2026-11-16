@extends('layouts.app')

@section('title', 'New Position')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">New Position</h1>
    <x-button href="{{ route('positions.index') }}" class="btn-outline-secondary">Back to list</x-button>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('positions.store') }}">
            @csrf
            @include('positions._form')
            <div class="d-flex gap-2">
                <x-button type="submit">Save Position</x-button>
                <a href="{{ route('positions.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
