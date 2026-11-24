@extends('layouts.app')
@section('title', __('Edit Delegation Document'))
@section('content')
    <h1 class="mb-3">{{ __('Edit Delegation Document') }}</h1>
    <form method="POST" action="{{ route('delegation-documents.update', $document) }}" enctype="multipart/form-data">
        @method('PUT')
        @include('delegation-documents._form')
    </form>
@endsection
