@extends('layouts.app')
@section('title', __('Upload Delegation Document'))
@section('content')
    <h1 class="mb-3">{{ __('Upload Delegation Document') }}</h1>
    <form method="POST" action="{{ route('delegation-documents.store') }}" enctype="multipart/form-data">
        @include('delegation-documents._form')
    </form>
@endsection
