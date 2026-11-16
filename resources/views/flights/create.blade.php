@extends('layouts.app')
@section('title', __('New Flight Booking'))
@section('content')
<h1 class="mb-3">{{ __('New Flight Booking') }}</h1>
@if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
<form action="{{ route('flights.store') }}" method="POST" enctype="multipart/form-data">
    @include('flights._form', ['record' => null])
</form>
@endsection
