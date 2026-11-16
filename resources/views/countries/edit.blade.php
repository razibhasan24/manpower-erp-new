@extends('layouts.app')

@section('title', 'Edit Country')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Edit Country</h1>
    <x-button href="{{ route('countries.show', $country) }}" class="btn-outline-secondary">Back to details</x-button>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('countries.update', $country) }}">
            @csrf
            @method('PUT')
            @include('countries._form')
            <div class="d-flex gap-2">
                <x-button type="submit">Update Country</x-button>
                <a href="{{ route('countries.show', $country) }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
