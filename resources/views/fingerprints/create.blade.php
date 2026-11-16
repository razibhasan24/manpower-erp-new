@extends('layouts.app')
@section('title', __('New Fingerprint'))
@section('content')
<h1 class="mb-3">{{ __('New Fingerprint') }}</h1>
@if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
<form action="{{ route('fingerprints.store') }}" method="POST" enctype="multipart/form-data">
    @include('fingerprints._form', ['record' => null])
</form>
@endsection
