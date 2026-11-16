@extends('layouts.app')
@section('title', __('New Selection & Grading'))
@section('content')
<h1 class="mb-3">{{ __('New Selection & Grading') }}</h1>
@if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
<form action="{{ route('selections.store') }}" method="POST" enctype="multipart/form-data">
    @include('selections._form', ['record' => null])
</form>
@endsection
