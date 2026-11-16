@extends('layouts.app')

@section('title', 'Edit Recruitment')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Edit Recruitment</h1>
    <x-button href="{{ route('recruitments.show', $recruitment) }}" class="btn-outline-secondary">Back to details</x-button>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('recruitments.update', $recruitment) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('recruitments._form')
            <div class="d-flex gap-2">
                <x-button type="submit">Update Recruitment</x-button>
                <a href="{{ route('recruitments.show', $recruitment) }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
