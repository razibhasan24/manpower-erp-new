@extends('layouts.app')
@section('title', __('New Passport Return'))
@section('content')
<h1 class="mb-3">{{ __('New Passport Return') }}</h1>
@if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
<form action="{{ route('passport-returns.store') }}" method="POST" enctype="multipart/form-data">
    @include('passport-returns._form', ['record' => null])
</form>
@endsection
