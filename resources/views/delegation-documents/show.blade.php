@extends('layouts.app')
@section('title', __('Delegation Document'))
@section('content')
    @if (session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>{{ __('Delegation Document') }}</h1>
        <div class="d-flex gap-2">
            @can('delegation.download') <a class="btn btn-outline-primary" href="{{ route('delegation-documents.download', $document) }}">{{ __('Download') }}</a> @endcan
            @can('delegation.edit') <a class="btn btn-warning" href="{{ route('delegation-documents.edit', $document) }}">{{ __('Edit') }}</a> @endcan
            <a class="btn btn-secondary" href="{{ route('delegation-documents.index') }}">{{ __('Back to List') }}</a>
        </div>
    </div>
    <div class="card"><div class="card-body">
        <dl class="row mb-0">
            <dt class="col-sm-3">{{ __('File') }}</dt><dd class="col-sm-9">{{ $document->original_name ?? basename($document->file_path) }}</dd>
            <dt class="col-sm-3">{{ __('Type') }}</dt><dd class="col-sm-9">{{ $document->type === 'e_duty' ? __('E-Duty') : __('Delegation') }}</dd>
            <dt class="col-sm-3">{{ __('Tracking') }}</dt><dd class="col-sm-9">{{ $document->delegation?->recruitment?->tracking_number ?? '—' }}</dd>
            <dt class="col-sm-3">{{ __('Candidate') }}</dt><dd class="col-sm-9">{{ $document->delegation?->recruitment?->passportHolder?->full_name ?? '—' }}</dd>
            <dt class="col-sm-3">{{ __('Uploaded By') }}</dt><dd class="col-sm-9">{{ $document->uploader?->name ?? '—' }}</dd>
            <dt class="col-sm-3">{{ __('Uploaded At') }}</dt><dd class="col-sm-9">{{ $document->created_at?->format('Y-m-d H:i') }}</dd>
        </dl>
    </div></div>
    @can('delegation.edit')
        <form class="mt-3" method="POST" action="{{ route('delegation-documents.destroy', $document) }}" onsubmit="return confirm('{{ __('Archive this document?') }}')">
            @csrf @method('DELETE')
            <x-button type="submit" variant="danger">{{ __('Archive Document') }}</x-button>
        </form>
    @endcan
@endsection
