@extends('layouts.app')

@section('title', 'New Company')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">New Company</h1>
    <x-button href="{{ route('companies.index') }}" class="btn-outline-secondary">Back to list</x-button>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('companies.store') }}">
            @csrf
            @include('companies._form')
            <div class="d-flex gap-2">
                <x-button type="submit">Save Company</x-button>
                <a href="{{ route('companies.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
