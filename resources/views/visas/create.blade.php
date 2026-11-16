@extends('layouts.app')
@section('title', __('New Visa & MOFA'))
@section('content')
<h1 class="mb-3">{{ __('New Visa & MOFA') }}</h1>
@if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
<form action="{{ route('visas.store') }}" method="POST" enctype="multipart/form-data">
    @include('visas._form', ['record' => null])
</form>
@endsection
