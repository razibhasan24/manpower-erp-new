@extends('layouts.app')

@section('title', 'New Country')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">New Country</h1>
    <x-button href="{{ route('countries.index') }}" class="btn-outline-secondary">Back to list</x-button>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('countries.store') }}">
            @csrf
            @include('countries._form')
            <div class="d-flex gap-2">
                <x-button type="submit">Save Country</x-button>
                <a href="{{ route('countries.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
