@extends('layouts.app')
@section('title', __('Add Training Attendance'))
@section('content')<h1 class="mb-3">{{ __('Add Training Attendance') }}</h1><form method="POST" action="{{ route('training-attendance.store') }}">@include('training-attendance._form')</form>@endsection
