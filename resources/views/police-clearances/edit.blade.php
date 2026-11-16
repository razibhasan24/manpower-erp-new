@extends('layouts.app')
@section('title', __('Edit Police Clearance'))
@section('content')
<h1 class="mb-3">{{ __('Edit Police Clearance') }}</h1>
@if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
<form action="{{ route('police-clearances.update', $record) }}" method="POST" enctype="multipart/form-data">
    @method('PUT')
    @include('police-clearances._form')
</form>
@endsection
