@extends('layouts.app')

@section('title', 'New Passport Holder')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">New Passport Holder</h1>
    <x-button href="{{ route('passport-holders.index') }}" class="btn-outline-secondary">Back to list</x-button>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('passport-holders.store') }}" enctype="multipart/form-data">
            @csrf
            @include('passport-holders._form')
            <div class="d-flex gap-2">
                <x-button type="submit">Save Passport Holder</x-button>
                <a href="{{ route('passport-holders.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
