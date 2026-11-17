@extends('layouts.app')
@section('title', __('Edit Medical Center'))
@section('content')
<h1 class="mb-3">{{ __('Edit Medical Center') }}</h1>
<form method="POST" action="{{ route('medical-centres.update', $record) }}">@csrf @method('PUT')
    @include('medical-centres._form')
    <div class="mt-3"><button class="btn btn-primary">{{ __('Update') }}</button> <a class="btn btn-outline-secondary" href="{{ route('medical-centres.index') }}">{{ __('Cancel') }}</a></div>
</form>
@endsection
