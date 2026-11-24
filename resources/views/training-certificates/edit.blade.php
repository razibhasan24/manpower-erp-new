@extends('layouts.app')
@section('title', __('Edit Training Certificate'))
@section('content')<h1 class="mb-3">{{ __('Edit Training Certificate') }}</h1><form method="POST" action="{{ route('training-certificates.update', $certificate) }}" enctype="multipart/form-data">@method('PUT')@include('training-certificates._form')</form>@endsection
