@extends('layouts.app')
@section('title', __('Agent Documents'))
@section('content')
<h1 class="mb-3">{{ __('Agent Documents') }}</h1>
<form method="GET" class="row g-2 mb-3"><div class="col-md-6"><input name="search" value="{{ request('search') }}" class="form-control" placeholder="{{ __('Search agent, document name or type') }}"></div><div class="col-auto"><button class="btn btn-primary">{{ __('Search') }}</button></div><div class="col-auto"><a class="btn btn-outline-secondary" href="{{ route('agent-documents.index') }}">{{ __('Clear') }}</a></div></form>
<div class="table-responsive"><table class="table table-striped align-middle"><thead><tr><th>{{ __('Agent') }}</th><th>{{ __('Document') }}</th><th>{{ __('Type') }}</th><th>{{ __('Uploaded') }}</th><th>{{ __('Size') }}</th><th>{{ __('Actions') }}</th></tr></thead><tbody>
@forelse($records as $record)<tr><td>{{ $record->agent?->name ?? '—' }}</td><td>{{ $record->original_name ?: basename($record->file_path) }}</td><td>{{ \Illuminate\Support\Str::headline($record->type) }}</td><td>{{ $record->created_at?->format('Y-m-d') ?? '—' }}</td><td>{{ $record->size ? number_format($record->size / 1024, 1).' KB' : '—' }}</td><td class="text-nowrap">@if($record->agent)<a class="btn btn-sm btn-outline-primary" href="{{ route('agents.documents.download', [$record->agent, $record]) }}">{{ __('Download') }}</a><a class="btn btn-sm btn-outline-secondary" href="{{ route('agents.show', $record->agent) }}">{{ __('Agent') }}</a>@endif</td></tr>@empty<tr><td colspan="6" class="text-center text-muted">{{ __('No agent documents found.') }}</td></tr>@endforelse
</tbody></table></div>{{ $records->links() }}
@endsection
