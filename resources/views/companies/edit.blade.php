@extends('layouts.app')

@section('title', 'Edit Company')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Edit Company</h1>
        <x-button href="{{ route('companies.show', $company) }}" variant="dark" size="sm" class="btn btn-dark">Back to
            details</x-button>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('companies.update', $company) }}">
                @csrf
                @method('PUT')
                @include('companies._form')
                <div class="d-flex gap-2">
                    <x-button variant="success" size="sm" type="submit">Update Company</x-button>
                    <a href="{{ route('companies.show', $company) }}" variant="danger" size="sm"
                        class="btn btn-danger">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
