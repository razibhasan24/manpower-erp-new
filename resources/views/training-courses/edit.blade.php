@extends('layouts.app')
@section('title', __('Edit Training Course'))
@section('content')
<h1 class="mb-3">{{ __('Edit Training Course') }}</h1><form method="POST" action="{{ route('training-courses.update', $record) }}">@csrf @method('PUT') @include('training-courses._form')<div class="mt-3"><button class="btn btn-primary">{{ __('Update') }}</button> <a class="btn btn-outline-secondary" href="{{ route('training-courses.index') }}">{{ __('Cancel') }}</a></div></form>
@endsection
