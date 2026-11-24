@extends('layouts.app')
@section('title', __('Edit Training Attendance'))
@section('content')<h1 class="mb-3">{{ __('Edit Training Attendance') }}</h1><form method="POST" action="{{ route('training-attendance.update', $record) }}">@method('PUT')@include('training-attendance._form')</form>@endsection
