@extends('layouts.app')

@section('title', 'New Agent')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">New Agent</h1>
    <x-button href="{{ route('agents.index') }}" class="btn-outline-secondary">Back to list</x-button>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('agents.store') }}">
            @csrf
            @include('agents._form')
            <div class="d-flex gap-2">
                <x-button type="submit">Save Agent</x-button>
                <a href="{{ route('agents.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
