@extends('layouts.app')
@section('title', __('Edit Follow-up'))
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3"><h1>{{ __('Edit Follow-up') }}</h1><a class="btn btn-outline-secondary" href="{{ route('follow-ups.index') }}">{{ __('Back to follow-ups') }}</a></div>
<div class="card"><div class="card-body"><form method="POST" action="{{ route('follow-ups.update', $support) }}">@csrf @method('PUT')
<div class="row g-3">
<div class="col-md-8"><label for="post_arrival_id" class="form-label">{{ __('Candidate / Arrival Record') }} *</label><select id="post_arrival_id" name="post_arrival_id" required class="form-select @error('post_arrival_id') is-invalid @enderror"><option value="">{{ __('Select arrival record') }}</option>@foreach($postArrivals as $record)<option value="{{ $record->id }}" @selected(old('post_arrival_id', $support->post_arrival_id) == $record->id)>{{ $record->recruitment?->tracking_number }} — {{ $record->recruitment?->passportHolder?->full_name }}</option>@endforeach</select>@error('post_arrival_id')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
<div class="col-md-4"><label for="type" class="form-label">{{ __('Follow-up Type') }} *</label><input id="type" name="type" value="{{ old('type', $support->type) }}" maxlength="255" required class="form-control @error('type') is-invalid @enderror">@error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
<div class="col-12"><label for="description" class="form-label">{{ __('Description / Notes') }}</label><textarea id="description" name="description" rows="4" maxlength="5000" class="form-control @error('description') is-invalid @enderror">{{ old('description', $support->description) }}</textarea>@error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
<div class="col-md-6"><label for="status" class="form-label">{{ __('Status') }} *</label><select id="status" name="status" required class="form-select @error('status') is-invalid @enderror">@foreach(['open','in_progress','resolved','closed'] as $status)<option value="{{ $status }}" @selected(old('status', $support->status) === $status)>{{ __(ucwords(str_replace('_', ' ', $status))) }}</option>@endforeach</select>@error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
</div><div class="d-flex gap-2 mt-4"><button class="btn btn-primary">{{ __('Update Follow-up') }}</button><a class="btn btn-outline-secondary" href="{{ route('follow-ups.index') }}">{{ __('Cancel') }}</a></div>
</form></div></div>
@endsection
