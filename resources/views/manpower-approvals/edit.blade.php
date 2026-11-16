@extends('layouts.app')
@section('title', __('Edit Manpower Approval'))
@section('content')
<h1 class="mb-3">{{ __('Edit Manpower Approval') }}</h1>
@if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
<form action="{{ route('manpower-approvals.update', $record) }}" method="POST" enctype="multipart/form-data">
    @method('PUT')
    @include('manpower-approvals._form')
</form>
@endsection
