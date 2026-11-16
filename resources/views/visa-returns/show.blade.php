@extends('layouts.app')
@section('title', __('Visa Return'))
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>{{ __('Visa Return') }}</h1>
    <div>
        @can('visa-return.edit')
        <x-button href="{{ route('visa-returns.edit', $record) }}">{{ __('Edit') }}</x-button>
        @endcan
        <x-button href="{{ route('visa-returns.index') }}" variant="secondary">{{ __('Back to List') }}</x-button>
    </div>
</div>
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
<div class="card mb-3"><div class="card-body">
    <dl class="row mb-0">
        <dt class="col-sm-4">{{ __('Status') }}</dt><dd class="col-sm-8"><x-badge :status="$record->status" /></dd>
        <dt class="col-sm-4">Visa</dt><dd class="col-sm-8">{{ $record->visa?->recruitment?->tracking_number ?? $record->visa_id }}</dd>
<dt class="col-sm-4">Return Date</dt><dd class="col-sm-8">{{ $record->return_date ?? '—' }}</dd>
<dt class="col-sm-4">Return Reason</dt><dd class="col-sm-8">{{ $record->return_reason ?? '—' }}</dd>
<dt class="col-sm-4">Return Type</dt><dd class="col-sm-8">{{ $record->return_type ?? '—' }}</dd>
<dt class="col-sm-4">Responsible Person</dt><dd class="col-sm-8">{{ $record->responsible_person ?? '—' }}</dd>
<dt class="col-sm-4">Received By (Required)</dt><dd class="col-sm-8">{{ $record->received_by ?? '—' }}</dd>
<dt class="col-sm-4">Remarks</dt><dd class="col-sm-8">{{ $record->remarks ?? '—' }}</dd>
@isset($record->document_path)<dt class="col-sm-4">Return Document</dt><dd class="col-sm-8"><a href="{{ route('visa-returns.download', [$record, 'document_path']) }}">{{ __('Download') }}</a></dd>@endisset
    </dl>
</div></div>

@if($recruitment)
    <h5>{{ __('Workflow History') }}</h5>
    <x-workflow-timeline :recruitment="$recruitment" />
@endif
<hr>
<h5>{{ __('Change Status') }}</h5>
<form action="{{ route('visa-returns.status', $record) }}" method="POST" class="row g-2">@csrf
    <div class="col-md-4"><select name="status" class="form-select">@foreach($statuses as $status)<option value="{{ $status }}" @selected($record->status == $status)>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>@endforeach</select></div>
    <div class="col-md-5"><input type="text" name="remarks" class="form-control" placeholder="{{ __('Remarks') }}"></div>
    <div class="col-md-3"><x-button type="submit">{{ __('Update Status') }}</x-button></div>
</form>
<hr>
<x-modal id="cancel-modal" title="{{ __('Cancel Record') }}">
    <p>{{ __('This cancels (archives) the record. It is not deleted.') }}</p>
    <form action="{{ route('visa-returns.destroy', $record) }}" method="POST">@csrf @method('DELETE')<x-button type="submit" variant="danger">{{ __('Confirm Cancel') }}</x-button></form>
</x-modal>
@endsection
