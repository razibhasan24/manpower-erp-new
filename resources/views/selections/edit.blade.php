@extends('layouts.app')
@section('title', __('Edit Selection & Grading'))
@section('content')
<h1 class="mb-3">{{ __('Edit Selection & Grading') }}</h1>
@if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
<form action="{{ route('selections.update', $record) }}" method="POST" enctype="multipart/form-data">
    @method('PUT')
    @include('selections._form')
</form>
@endsection
