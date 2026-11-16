@extends('layouts.app')

@section('title', 'New Recruitment')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">New Recruitment</h1>
    <x-button href="{{ route('recruitments.index') }}" class="btn-outline-secondary">Back to list</x-button>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('recruitments.store') }}" enctype="multipart/form-data">
            @csrf
            @include('recruitments._form')
            <div class="d-flex gap-2">
                <x-button type="submit">Save Recruitment</x-button>
                <a href="{{ route('recruitments.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
