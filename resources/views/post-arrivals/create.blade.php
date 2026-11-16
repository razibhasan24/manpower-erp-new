@extends('layouts.app')
@section('title', __('New Post Arrival Support'))
@section('content')
<h1 class="mb-3">{{ __('New Post Arrival Support') }}</h1>
@if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
<form action="{{ route('post-arrivals.store') }}" method="POST" enctype="multipart/form-data">
    @include('post-arrivals._form', ['record' => null])
</form>
@endsection
