@extends('layouts.app')
@section('title', __('New Delegation Approval'))
@section('content')
<h1 class="mb-3">{{ __('New Delegation Approval') }}</h1>
@if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
<form action="{{ route('delegations.store') }}" method="POST" enctype="multipart/form-data">
    @include('delegations._form', ['record' => null])
</form>
@endsection
