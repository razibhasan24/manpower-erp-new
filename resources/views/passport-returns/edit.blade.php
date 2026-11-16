@extends('layouts.app')
@section('title', __('Edit Passport Return'))
@section('content')
<h1 class="mb-3">{{ __('Edit Passport Return') }}</h1>
@if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
<form action="{{ route('passport-returns.update', $record) }}" method="POST" enctype="multipart/form-data">
    @method('PUT')
    @include('passport-returns._form')
</form>
@endsection
