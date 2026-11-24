@extends('layouts.app')
@section('title', __('Delegation Documents'))
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>{{ __('Delegation Documents') }}</h1>
        @can('delegation.create')
            <x-button href="{{ route('delegation-documents.create') }}" variant="success">{{ __('Upload Document') }}</x-button>
        @endcan
    </div>
    @if (session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead><tr><th>{{ __('Document') }}</th><th>{{ __('Type') }}</th><th>{{ __('Tracking') }}</th><th>{{ __('Candidate') }}</th><th>{{ __('Uploaded') }}</th><th>{{ __('Actions') }}</th></tr></thead>
            <tbody>
                @forelse ($records as $document)
                    <tr>
                        <td>{{ $document->original_name ?? basename($document->file_path) }}</td>
                        <td>{{ $document->type === 'e_duty' ? __('E-Duty') : __('Delegation') }}</td>
                        <td>{{ $document->delegation?->recruitment?->tracking_number ?? '—' }}</td>
                        <td>{{ $document->delegation?->recruitment?->passportHolder?->full_name ?? '—' }}</td>
                        <td>{{ $document->created_at?->format('Y-m-d') }}</td>
                        <td class="text-nowrap">
                            <x-button href="{{ route('delegation-documents.show', $document) }}" variant="primary" size="sm">{{ __('View') }}</x-button>
                            @can('delegation.edit') <x-button href="{{ route('delegation-documents.edit', $document) }}" variant="warning" size="sm">{{ __('Edit') }}</x-button> @endcan
                            @can('delegation.download') <a class="btn btn-sm btn-outline-secondary" href="{{ route('delegation-documents.download', $document) }}">{{ __('Download') }}</a> @endcan
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted">{{ __('No delegation documents found.') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $records->links() }}
@endsection
