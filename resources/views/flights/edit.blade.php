@extends('layouts.app')
@section('title', __('Edit Flight Booking'))
@section('content')
<h1 class="mb-3">{{ __('Edit Flight Booking') }}</h1>
@if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
<form action="{{ route('flights.update', $record) }}" method="POST" enctype="multipart/form-data">
    @method('PUT')
    @include('flights._form')
</form>
@endsection
