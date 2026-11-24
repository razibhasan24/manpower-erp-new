@extends('layouts.app')
@section('title', __('Register Training Certificate'))
@section('content')<h1 class="mb-3">{{ __('Register Training Certificate') }}</h1><form method="POST" action="{{ route('training-certificates.store') }}" enctype="multipart/form-data">@include('training-certificates._form')</form>@endsection
