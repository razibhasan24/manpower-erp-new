@extends('layouts.app')

@section('title', 'New Branch')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">New Branch</h1>
    <x-button href="{{ route('branches.index') }}" class="btn-outline-secondary">Back to list</x-button>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('branches.store') }}">
            @csrf
            @include('branches._form')
            <div class="d-flex gap-2">
                <x-button type="submit">Save Branch</x-button>
                <a href="{{ route('branches.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
