@extends('layouts.app')
@section('title', __('Employer Information'))
@section('content')
<h1 class="mb-3">{{ __('Employer Information') }}</h1>
@include('flight-support._arrival-list', ['clearUrl' => route('employer-information.index'), 'valueLabel' => __('Employer contact'), 'value' => fn ($record) => $record->employer_contact, 'emptyMessage' => __('No employer information found.')])
@endsection
