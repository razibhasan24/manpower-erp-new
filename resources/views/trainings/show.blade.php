@extends('layouts.app')
@section('title', __('Training'))
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>{{ __('Training') }}</h1>
    <div>
        @can('training.edit')
        <x-button href="{{ route('trainings.edit', $record) }}">{{ __('Edit') }}</x-button>
        @endcan
        <x-button href="{{ route('trainings.index') }}" variant="secondary">{{ __('Back to List') }}</x-button>
    </div>
</div>
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
<div class="card mb-3"><div class="card-body">
    <dl class="row mb-0">
        <dt class="col-sm-4">{{ __('Status') }}</dt><dd class="col-sm-8"><x-badge :status="$record->result" /></dd>
        <dt class="col-sm-4">Recruitment</dt><dd class="col-sm-8">{{ $record->recruitment?->tracking_number ?? '—' }} — {{ $record->recruitment?->passportHolder?->full_name ?? '' }}</dd>
<dt class="col-sm-4">Training Centre</dt><dd class="col-sm-8">{{ $record->training_centre_id ?? '—' }}</dd>
<dt class="col-sm-4">Training Course</dt><dd class="col-sm-8">{{ $record->course_id ?? '—' }}</dd>
<dt class="col-sm-4">Start Date</dt><dd class="col-sm-8">{{ $record->start_date ?? '—' }}</dd>
<dt class="col-sm-4">End Date</dt><dd class="col-sm-8">{{ $record->end_date ?? '—' }}</dd>
<dt class="col-sm-4">Attendance (%)</dt><dd class="col-sm-8">{{ $record->attendance_percentage ?? '—' }}</dd>
<dt class="col-sm-4">Result</dt><dd class="col-sm-8">{{ $record->result ?? '—' }}</dd>
<dt class="col-sm-4">Remarks</dt><dd class="col-sm-8">{{ $record->remarks ?? '—' }}</dd>
@isset($record->certificate_path)<dt class="col-sm-4">Training Certificate</dt><dd class="col-sm-8"><a href="{{ route('trainings.download', [$record, 'certificate_path']) }}">{{ __('Download') }}</a></dd>@endisset
    </dl>
</div></div>

@if($recruitment)
    <h5>{{ __('Workflow History') }}</h5>
    <x-workflow-timeline :recruitment="$recruitment" />
@endif
<hr>
<h5>{{ __('Change Status') }}</h5>
<form action="{{ route('trainings.status', $record) }}" method="POST" class="row g-2">@csrf
    <div class="col-md-4"><select name="status" class="form-select">@foreach($statuses as $status)<option value="{{ $status }}" @selected($record->result == $status)>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>@endforeach</select></div>
    <div class="col-md-5"><input type="text" name="remarks" class="form-control" placeholder="{{ __('Remarks') }}"></div>
    <div class="col-md-3"><x-button type="submit">{{ __('Update Status') }}</x-button></div>
</form>
<hr>
<x-modal id="cancel-modal" title="{{ __('Cancel Record') }}">
    <p>{{ __('This cancels (archives) the record. It is not deleted.') }}</p>
    <form action="{{ route('trainings.destroy', $record) }}" method="POST">@csrf @method('DELETE')<x-button type="submit" variant="danger">{{ __('Confirm Cancel') }}</x-button></form>
</x-modal>
@endsection
