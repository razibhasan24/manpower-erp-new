@extends('layouts.app')

@section('title', 'Edit Branch')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Edit Branch</h1>
    <x-button href="{{ route('branches.show', $branch) }}" class="btn-outline-secondary">Back to details</x-button>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('branches.update', $branch) }}">
            @csrf
            @method('PUT')
            @include('branches._form')
            <div class="d-flex gap-2">
                <x-button type="submit">Update Branch</x-button>
                <a href="{{ route('branches.show', $branch) }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
