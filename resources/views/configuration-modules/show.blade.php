@extends('layouts.app')
@section('title', $title)
@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <h1 class="mb-0">{{ $title }}</h1>
    <div class="d-flex gap-2">
        @if($moduleKey === 'invoice-settings')
            <a class="btn btn-outline-secondary" href="{{ route('company-profile.edit') }}">{{ __('Company Profile') }}</a>
        @endif
        @if(in_array($moduleKey, ['invoice-settings', 'notification-settings']))
            <a class="btn btn-primary" href="{{ route('settings.index') }}">{{ __('General Settings') }}</a>
        @endif
    </div>
</div>

@if(!empty($details['notice']))
    <div class="alert alert-info">{{ $details['notice'] }}</div>
@endif

@if(isset($details['items']))
    <div class="card"><div class="table-responsive"><table class="table table-striped align-middle mb-0">
        <thead><tr><th>{{ __('Setting') }}</th><th>{{ __('Current value') }}</th></tr></thead>
        <tbody>@foreach($details['items'] as $label => $value)<tr><th scope="row">{{ $label }}</th><td>{{ $value ?? '—' }}</td></tr>@endforeach</tbody>
    </table></div></div>
@endif

@if(isset($details['stages']))
    <div class="table-responsive"><table class="table table-striped align-middle">
        <thead><tr><th>{{ __('Order') }}</th><th>{{ __('Workflow stage') }}</th><th>{{ __('Available statuses') }}</th></tr></thead>
        <tbody>@foreach($details['stages'] as $index => $stage)<tr><td>{{ $index + 1 }}</td><td>{{ __(\Illuminate\Support\Str::headline($stage)) }}</td><td>{{ collect($details['stageStatuses'][$stage] ?? [])->map(fn ($status) => __(\Illuminate\Support\Str::headline($status)))->join(', ') }}</td></tr>@endforeach</tbody>
    </table></div>
@endif

@if(array_key_exists('notifications', $details))
    <div class="card"><div class="list-group list-group-flush">
        @forelse($details['notifications'] as $notification)
            <a href="{{ $notification['url'] }}" class="list-group-item list-group-item-action d-flex align-items-center gap-3">
                <x-icon :name="$notification['icon']" :size="18" />
                <span>{{ $notification['label'] }}</span>
                <span class="badge text-bg-{{ $notification['tone'] }} ms-auto">{{ __(ucfirst($notification['tone'])) }}</span>
            </a>
        @empty
            <div class="list-group-item text-muted">{{ __('There are no current notifications for this account.') }}</div>
        @endforelse
    </div></div>
@endif
@endsection
