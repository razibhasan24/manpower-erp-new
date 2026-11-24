@extends('layouts.app')
@section('title', __('Edit Airline'))
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3"><h1>{{ __('Edit Airline') }}</h1><a class="btn btn-outline-secondary" href="{{ route('airlines.index') }}">{{ __('Back to Airlines') }}</a></div>
<div class="card"><div class="card-body"><form method="POST" action="{{ route('airlines.update', $airline) }}">@csrf @method('PUT')<div class="row g-3">
<div class="col-md-6"><label for="name" class="form-label">{{ __('Airline Name') }} *</label><input id="name" name="name" value="{{ old('name', $airline->name) }}" maxlength="255" required class="form-control @error('name') is-invalid @enderror">@error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
<div class="col-md-6"><label for="code" class="form-label">{{ __('Airline Code') }}</label><input id="code" name="code" value="{{ old('code', $airline->code) }}" maxlength="10" class="form-control @error('code') is-invalid @enderror">@error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
<div class="col-md-6"><label for="status" class="form-label">{{ __('Status') }} *</label><select id="status" name="status" required class="form-select @error('status') is-invalid @enderror"><option value="active" @selected(old('status', $airline->status) === 'active')>{{ __('Active') }}</option><option value="inactive" @selected(old('status', $airline->status) === 'inactive')>{{ __('Inactive') }}</option></select>@error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
</div><div class="d-flex gap-2 mt-4"><button class="btn btn-primary">{{ __('Update Airline') }}</button><a class="btn btn-outline-secondary" href="{{ route('airlines.index') }}">{{ __('Cancel') }}</a></div></form></div></div>
@endsection
