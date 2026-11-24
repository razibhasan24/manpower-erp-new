@extends('layouts.app')
@section('title', __('Add Training Course'))
@section('content')
    <h1 class="mb-3">{{ __('Add Training Course') }}</h1>
    <form method="POST" action="{{ route('training-courses.store') }}">@csrf @include('training-courses._form')<div class="mt-3">
            <x-button variant="primary">{{ __('Save') }}</x-button>
            <x-button variant="danger" href="{{ route('training-courses.index') }}">{{ __('Cancel') }}</x-button>
        </div>
    </form>
@endsection
