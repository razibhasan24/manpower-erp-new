@extends('layouts.app')

@section('title', 'New Passport Holder')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">New Passport Holder</h1>

        <x-button href="{{ route('passport-holders.index') }}" variant="dark" size="sm">
            <i class="fas fa-arrow-left me-1"></i> Back to list
        </x-button>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('passport-holders.store') }}" enctype="multipart/form-data">
                @csrf

                @include('passport-holders._form')

                <div class="d-flex gap-2 mt-4">
                    <x-button type="submit" variant="success">
                        <i class="fas fa-save me-1"></i> Save Passport Holder
                    </x-button>

                    <x-button type="reset" variant="danger">
                        <i class="fas fa-undo me-1"></i> Cancel
                    </x-button>
                </div>
            </form>
        </div>
    </div>
@endsection
