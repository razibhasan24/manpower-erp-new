@extends('layouts.app')
@section('title', __('Airport Pickup'))
@section('content')
<h1 class="mb-3">{{ __('Airport Pickup') }}</h1>
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
@include('flight-support._arrival-list', ['clearUrl' => route('airport-pickup.index'), 'createUrl' => route('airport-pickup.create'), 'valueLabel' => __('Pickup'), 'value' => fn ($record) => __('Required'), 'emptyMessage' => __('No airport pickup records found.'), 'isAirportPickup' => true])
@endsection
