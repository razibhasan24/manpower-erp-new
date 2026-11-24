@extends('layouts.app')
@section('title', __('Training Certificates'))
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3"><h1>{{ __('Training Certificates') }}</h1>@can('training.create')<a class="btn btn-success" href="{{ route('training-certificates.create') }}">{{ __('Register Certificate') }}</a>@endcan</div>
    @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <div class="table-responsive"><table class="table table-striped align-middle"><thead><tr><th>{{ __('Certificate No.') }}</th><th>{{ __('Issue Date') }}</th><th>{{ __('Tracking') }}</th><th>{{ __('Candidate') }}</th><th>{{ __('Course') }}</th><th>{{ __('Actions') }}</th></tr></thead><tbody>
        @forelse($records as $certificate)
            <tr><td>{{ $certificate->certificate_number ?: '—' }}</td><td>{{ $certificate->issued_at?->format('Y-m-d') }}</td><td>{{ $certificate->training?->recruitment?->tracking_number ?? '—' }}</td><td>{{ $certificate->training?->recruitment?->passportHolder?->full_name ?? '—' }}</td><td>{{ $certificate->training?->course?->title ?? '—' }}</td><td class="text-nowrap"><a class="btn btn-sm btn-primary" href="{{ route('training-certificates.show', $certificate) }}">{{ __('View') }}</a>@can('training.edit') <a class="btn btn-sm btn-warning" href="{{ route('training-certificates.edit', $certificate) }}">{{ __('Edit') }}</a>@endcan @can('training.download') <a class="btn btn-sm btn-outline-secondary" href="{{ route('training-certificates.download', $certificate) }}">{{ __('Download') }}</a>@endcan</td></tr>
        @empty<tr><td colspan="6" class="text-center text-muted">{{ __('No training certificates found.') }}</td></tr>@endforelse
    </tbody></table></div>{{ $records->links() }}
@endsection
