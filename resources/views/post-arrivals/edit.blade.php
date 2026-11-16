@extends('layouts.app')
@section('title', __('Edit Post Arrival Support'))
@section('content')
<h1 class="mb-3">{{ __('Edit Post Arrival Support') }}</h1>
@if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
<form action="{{ route('post-arrivals.update', $record) }}" method="POST" enctype="multipart/form-data">
    @method('PUT')
    @include('post-arrivals._form')
</form>
@endsection
