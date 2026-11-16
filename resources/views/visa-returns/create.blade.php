@extends('layouts.app')
@section('title', __('New Visa Return'))
@section('content')
<h1 class="mb-3">{{ __('New Visa Return') }}</h1>
@if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
<form action="{{ route('visa-returns.store') }}" method="POST" enctype="multipart/form-data">
    @include('visa-returns._form', ['record' => null])
</form>
@endsection
