@extends('layouts.app')

@section('title', __('My Documents') . ' — ' . \App\Services\BrandingService::appName())

@section('content')
    <x-page-header :title="__('My Documents')" :subtitle="$party['name']" />

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>{{ __('Uploaded documents') }}</span>
            <span class="small text-secondary">{{ count($documents) }} {{ __('files') }}</span>
        </div>
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>{{ __('Type') }}</th>
                        <th>{{ __('File') }}</th>
                        <th>{{ __('Size') }}</th>
                        <th>{{ __('Uploaded') }}</th>
                        <th class="text-end">{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($documents as $document)
                        <tr>
                            <td>{{ ucwords(str_replace('_', ' ', (string) $document->type)) }}</td>
                            <td>{{ $document->original_name ?? basename((string) $document->file_path) }}</td>
                            <td>{{ $document->size ? number_format($document->size / 1024, 1).' KB' : '—' }}</td>
                            <td>{{ $document->created_at?->format('d M Y') ?? '—' }}</td>
                            <td class="text-end">
                                <a href="{{ route('my.documents.download', $document->id) }}" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1">
                                    <x-icon name="download" :size="14" /> {{ __('Download') }}
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-secondary py-4">{{ __('No documents have been uploaded for your profile yet.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <p class="small text-secondary mt-3 mb-0">{{ __('Documents are uploaded and verified by office staff. You can download only your own files.') }}</p>
@endsection
