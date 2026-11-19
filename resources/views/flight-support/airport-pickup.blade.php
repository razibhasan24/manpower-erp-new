@extends('layouts.app')
@section('title', __('Airport Pickup'))
@section('content')
<h1 class="mb-3">{{ __('Airport Pickup') }}</h1>
@include('flight-support._arrival-list', ['clearUrl' => route('airport-pickup.index'), 'valueLabel' => __('Pickup'), 'value' => fn ($record) => __('Required'), 'emptyMessage' => __('No airport pickup records found.')])
@endsection
