@extends('layouts.app')

@section('title', 'Edit Recruitment')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Edit Recruitment</h1>

        <x-button href="{{ route('recruitments.show', $recruitment) }}" variant="dark" size="sm">
            <i class="fas fa-arrow-left me-1"></i> Back to details
        </x-button>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('recruitments.update', $recruitment) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                @include('recruitments._form')

                <div class="d-flex gap-2 mt-4">
                    <x-button type="submit" variant="success">
                        <i class="fas fa-save me-1"></i> Update Recruitment
                    </x-button>

                    <x-button href="{{ route('recruitments.show', $recruitment) }}" variant="danger">
                        Cancel
                    </x-button>
                </div>
            </form>
        </div>
    </div>
@endsection
