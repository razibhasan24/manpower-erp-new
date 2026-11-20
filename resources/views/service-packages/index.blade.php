@extends('layouts.app')
@section('title', $title)
@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div><h1 class="mb-1">{{ $title }}</h1><p class="text-muted mb-0">{{ __('Manage package details, price and availability.') }}</p></div>
    @can($permissionPrefix.'.create')<a class="btn btn-primary" href="{{ route('service-packages.create', $type) }}">{{ __('Add Package') }}</a>@endcan
</div>
<form method="GET" class="row g-2 mb-3"><div class="col-md-5"><input class="form-control" name="search" value="{{ request('search') }}" placeholder="{{ __('Search by code or package name') }}"></div><div class="col-auto"><button class="btn btn-outline-primary">{{ __('Search') }}</button></div><div class="col-auto"><a class="btn btn-outline-secondary" href="{{ route('service-packages.index', $type) }}">{{ __('Clear') }}</a></div></form>
<div class="card"><div class="table-responsive"><table class="table table-hover align-middle mb-0"><thead><tr><th>{{ __('Code') }}</th><th>{{ __('Package') }}</th><th>{{ __('Duration') }}</th><th class="text-end">{{ __('Price') }}</th><th>{{ __('Status') }}</th><th>{{ __('Actions') }}</th></tr></thead><tbody>
@forelse($packages as $package)
<tr><td>{{ $package->code }}</td><td><strong>{{ $package->name }}</strong><div class="small text-muted">{{ \Illuminate\Support\Str::limit($package->description, 90) }}</div></td><td>{{ $package->duration_days ? $package->duration_days.' '.__('days') : '—' }}</td><td class="text-end">{{ $package->currency }} {{ number_format((float) $package->price, 2) }}</td><td><span class="badge {{ $package->is_active ? 'text-bg-success' : 'text-bg-secondary' }}">{{ $package->is_active ? __('Active') : __('Inactive') }}</span></td><td class="text-nowrap">@can($permissionPrefix.'.edit')<a class="btn btn-sm btn-outline-primary" href="{{ route('service-packages.edit', [$type, $package]) }}">{{ __('Edit') }}</a>@endcan @can($permissionPrefix.'.delete')<form class="d-inline" method="POST" action="{{ route('service-packages.destroy', [$type, $package]) }}" onsubmit="return confirm('{{ __('Delete this package?') }}')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">{{ __('Delete') }}</button></form>@endcan</td></tr>
@empty<tr><td colspan="6" class="text-center text-muted py-4">{{ __('No packages found.') }}</td></tr>@endforelse
</tbody></table></div><div class="card-body">{{ $packages->links() }}</div></div>
@endsection
