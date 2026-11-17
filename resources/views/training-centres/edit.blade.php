@extends('layouts.app')
@section('title', __('Edit Training Center'))
@section('content')
<h1 class="mb-3">{{ __('Edit Training Center') }}</h1><form method="POST" action="{{ route('training-centres.update', $record) }}">@csrf @method('PUT') @include('training-centres._form')<div class="mt-3"><button class="btn btn-primary">{{ __('Update') }}</button> <a class="btn btn-outline-secondary" href="{{ route('training-centres.index') }}">{{ __('Cancel') }}</a></div></form>
@endsection
