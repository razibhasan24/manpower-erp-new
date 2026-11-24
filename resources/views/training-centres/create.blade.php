@extends('layouts.app')
@section('title', __('Add Training Center'))
@section('content')
    <h1 class="mb-3">{{ __('Add Training Center') }}</h1>
    <form method="POST" action="{{ route('training-centres.store') }}">@csrf @include('training-centres._form')<div class="mt-3">
            <x-button variant="primary" class="px-4">
                {{ __('Save') }}
            </x-button>
            <x-button href="{{ route('training-centres.index') }}" variant="danger" class="px-3">
                {{ __('Cancel') }}
            </x-button>
        </div>
    </form>
@endsection
