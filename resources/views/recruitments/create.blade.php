@extends('layouts.app')

@section('title', 'New Recruitment')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">New Recruitment</h1>

        <x-button href="{{ route('recruitments.index') }}" variant="dark" size="sm">
            <i class="fas fa-arrow-left me-1"></i> Back to list
        </x-button>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('recruitments.store') }}" enctype="multipart/form-data">
                @csrf

                @include('recruitments._form')

                <div class="d-flex gap-2 mt-4">
                    <x-button type="submit" variant="success">
                        <i class="fas fa-save me-1"></i> Save Recruitment
                    </x-button>

                    <x-button href="{{ route('recruitments.index') }}" variant="danger">
                        <i class="fas fa-undo me-1"></i> Cancel
                    </x-button>
                </div>
            </form>
        </div>
    </div>
@endsection
