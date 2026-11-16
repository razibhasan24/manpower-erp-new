@extends('layouts.app')

@section('title', 'Edit Passport Holder')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Edit Passport Holder</h1>
    <x-button href="{{ route('passport-holders.show', $passport_holder) }}" class="btn-outline-secondary">Back to details</x-button>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('passport-holders.update', $passport_holder) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('passport-holders._form')
            <div class="d-flex gap-2">
                <x-button type="submit">Update Passport Holder</x-button>
                <a href="{{ route('passport-holders.show', $passport_holder) }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
