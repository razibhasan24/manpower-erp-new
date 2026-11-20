@extends('layouts.app')
@section('title', __('Accommodation'))
@section('content')
<h1 class="mb-3">{{ __('Accommodation') }}</h1>
@include('flight-support._arrival-list', ['clearUrl' => route('accommodation.index'), 'createUrl' => route('accommodation.create'), 'valueLabel' => __('Accommodation'), 'value' => fn ($record) => $record->accommodation, 'emptyMessage' => __('No accommodation records found.')])
@endsection
