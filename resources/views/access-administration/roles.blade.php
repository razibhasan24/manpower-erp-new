@extends('layouts.app')
@section('title', __('Role Management'))
@section('content')
<h1 class="mb-3">{{ __('Roles & Permissions') }}</h1>
<div class="alert alert-info">{{ __('Role and permission changes are not available here. This register shows the current role definitions and their assigned permissions.') }}</div>
<form method="GET" class="row g-2 mb-3"><div class="col-md-5"><input name="search" value="{{ request('search') }}" class="form-control" placeholder="{{ __('Search role') }}"></div><div class="col-auto"><button class="btn btn-primary">{{ __('Search') }}</button></div><div class="col-auto"><a class="btn btn-outline-secondary" href="{{ route('roles.index') }}">{{ __('Clear') }}</a></div></form>
<div class="row g-3">@forelse($roles as $role)<div class="col-lg-6"><div class="card h-100"><div class="card-header d-flex justify-content-between"><strong>{{ $role->name }}</strong><span class="text-muted small">{{ $role->permissions->count() }} {{ __('permissions') }}</span></div><div class="card-body"><div class="d-flex flex-wrap gap-2">@forelse($role->permissions->sortBy('name') as $permission)<span class="badge text-bg-light border">{{ $permission->name }}</span>@empty<span class="text-muted">{{ __('No direct permissions assigned.') }}</span>@endforelse</div></div></div></div>@empty<div class="col-12"><p class="text-center text-muted">{{ __('No roles found.') }}</p></div>@endforelse</div>{{ $roles->links() }}
@endsection
