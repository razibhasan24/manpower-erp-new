@extends('layouts.app')

@section('title', isset($user) ? __('Edit User') : __('Create User'))

@section('content')
    {{-- ✅ Header Section --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">{{ isset($user) ? __('Edit User') : __('Create User') }}</h1>
            <p class="text-muted small mb-0">Manage account details, assign roles, and configure access permissions.</p>
        </div>
        <x-button variant='dark' class="btn btn-dark btn-sm" href="{{ route('users.index') }}">
            <i class="bi bi-arrow-left me-1"></i> {{ __('Back to users') }}
        </x-button>
    </div>

    {{-- ✅ Info Alert --}}
    @unless(isset($user))
        <div class="alert alert-info alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
            <i class="bi bi-info-circle-fill me-2 fs-5"></i>
            <div>{{ __('The new user will be asked to change this initial password after signing in. You can assign one role and additional permissions available to your own account.') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endunless

    <form method="POST" action="{{ isset($user) ? route('users.update', $user) : route('users.store') }}">
        @csrf
        @if(isset($user)) @method('PUT') @endif

        {{-- ✅ Section 1: Account Details --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white border-bottom pt-4 pb-3">
                <h5 class="mb-0 fw-semibold"><i class="bi bi-person me-2 text-primary"></i> {{ __('Account Details') }}</h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    {{-- Full Name --}}
                    <div class="col-md-6">
                        <label for="name" class="form-label small fw-semibold text-muted">{{ __('Full Name') }} <span class="text-danger">*</span></label>
                        <input id="name" name="name" value="{{ old('name', $user->name ?? '') }}" 
                            class="form-control @error('name') is-invalid @enderror" required placeholder="Enter Your Name">
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Email --}}
                    <div class="col-md-6">
                        <label for="email" class="form-label small fw-semibold text-muted">{{ __('Email') }} <span class="text-danger">*</span></label>
                        <input id="email" type="email" name="email" value="{{ old('email', $user->email ?? '') }}" 
                            class="form-control @error('email') is-invalid @enderror" required placeholder="Enter Your Email Address">
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Phone --}}
                    <div class="col-md-4">
                        <label for="phone" class="form-label small fw-semibold text-muted">{{ __('Phone') }}</label>
                        <input id="phone" name="phone" value="{{ old('phone', $user->phone ?? '') }}" 
                            class="form-control @error('phone') is-invalid @enderror" placeholder="Enter Your Phone Number">
                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Password --}}
                    <div class="col-md-4">
                        <label for="password" class="form-label small fw-semibold text-muted">
                            {{ isset($user) ? __('New Password (Optional)') : __('Initial Password') }} 
                            @if(!isset($user))<span class="text-danger">*</span>@endif
                        </label>
                        <input id="password" type="password" name="password" 
                            class="form-control @error('password') is-invalid @enderror" 
                            @required(!isset($user)) autocomplete="new-password" placeholder="••••••••">
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Confirm Password --}}
                    <div class="col-md-4">
                        <label for="password_confirmation" class="form-label small fw-semibold text-muted">{{ __('Confirm Password') }} @if(!isset($user))<span class="text-danger">*</span>@endif</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" 
                            class="form-control" @required(!isset($user)) autocomplete="new-password" placeholder="••••••••">
                    </div>

                    {{-- Status --}}
                    <div class="col-md-4">
                        <label for="status" class="form-label small fw-semibold text-muted">{{ __('Status') }} <span class="text-danger">*</span></label>
                        <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
                            @foreach(['active','inactive','suspended'] as $status)
                                <option value="{{ $status }}" @selected(old('status', $user->status ?? 'active') === $status)>
                                    {{ __(ucfirst($status)) }}
                                </option>
                            @endforeach
                        </select>
                        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- ✅ Section 2: Role and Permissions --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white border-bottom pt-4 pb-3">
                <h5 class="mb-0 fw-semibold"><i class="bi bi-shield-lock me-2 text-primary"></i> {{ __('Role and Permissions') }}</h5>
            </div>
            <div class="card-body p-4">
                {{-- Role Select --}}
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="role_id" class="form-label small fw-semibold text-muted">{{ __('Primary Role') }} <span class="text-danger">*</span></label>
                        <select id="role_id" name="role_id" class="form-select @error('role_id') is-invalid @enderror" required>
                            <option value="">{{ __('-- Select a role --') }}</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" @selected((string) old('role_id', isset($user) ? (string) $user->roles->first()?->id : '') === (string) $role->id)>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('role_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Permissions --}}
                <div class="d-flex flex-wrap justify-content-between align-items-end mb-3">
                    <div>
                        <label class="form-label small fw-semibold text-muted mb-0">{{ __('Additional Direct Permissions') }}</label>
                        <p class="small text-muted mb-0">{{ __('The role grants its standard permissions. Select only extra permissions needed.') }}</p>
                    </div>
                </div>
                
                @error('permission_ids')<div class="text-danger small mb-2">{{ $message }}</div>@enderror
                
                <div class="row g-3">
                    @foreach($permissions->groupBy(fn ($permission) => explode('.', $permission->name, 2)[0]) as $module => $modulePermissions)
                        <div class="col-lg-4 col-md-6">
                            <div class="border rounded p-3 h-100 bg-light bg-opacity-50">
                                <strong class="d-block mb-2 text-primary">{{ \Illuminate\Support\Str::headline($module) }}</strong>
                                <div class="d-flex flex-column gap-2" style="max-height: 200px; overflow-y: auto;">
                                    @foreach($modulePermissions as $permission)
                                        <div class="form-check mb-0">
                                            <input class="form-check-input" type="checkbox" id="permission_{{ $permission->id }}" name="permission_ids[]" value="{{ $permission->id }}" @checked(in_array((string) $permission->id, array_map('strval', old('permission_ids', isset($user) ? $user->permissions->pluck('id')->map(fn ($id) => (string) $id)->all() : [])), true))>
                                            <label class="form-check-label small" for="permission_{{ $permission->id }}">
                                                {{ \Illuminate\Support\Str::headline(substr($permission->name, strlen($module) + 1)) }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ✅ Section 3: Branch and Country Access --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white border-bottom pt-4 pb-3">
                <h5 class="mb-0 fw-semibold"><i class="bi bi-geo-alt me-2 text-primary"></i> {{ __('Branch and Country Access') }}</h5>
            </div>
            <div class="card-body p-4">
                <p class="small text-muted mb-4">{{ __('The primary branch is automatically included in the user’s allowed branch assignments.') }}</p>
                
                <div class="row g-4">
                    {{-- Primary Branch --}}
                    <div class="col-md-4">
                        <label for="branch_id" class="form-label small fw-semibold text-muted">{{ __('Primary Branch') }}</label>
                        <select id="branch_id" name="branch_id" class="form-select @error('branch_id') is-invalid @enderror">
                            <option value="">{{ __('No primary branch') }}</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}" @selected((string) old('branch_id', $user->branch_id ?? '') === (string) $branch->id)>
                                    {{ $branch->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('branch_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Additional Branches --}}
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold text-muted">{{ __('Additional Branch Access') }}</label>
                        <div class="border rounded p-3 bg-light bg-opacity-50" style="max-height: 200px; overflow-y: auto;">
                            @forelse($branches as $branch)
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="branch_ids[]" id="branch_{{ $branch->id }}" value="{{ $branch->id }}" @checked(in_array((string) $branch->id, array_map('strval', old('branch_ids', isset($user) ? $user->branches->pluck('id')->map(fn ($id) => (string) $id)->all() : [])), true))>
                                    <label class="form-check-label small" for="branch_{{ $branch->id }}">{{ $branch->name }}</label>
                                </div>
                            @empty
                                <span class="text-muted small">{{ __('No branches available.') }}</span>
                            @endforelse 
                        </div>
                        @error('branch_ids.*')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>

                    {{-- Countries --}}
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold text-muted">{{ __('Country Access') }}</label>
                        <div class="border rounded p-3 bg-light bg-opacity-50" style="max-height: 200px; overflow-y: auto;">
                            @forelse($countries as $country)
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="country_ids[]" id="country_{{ $country->id }}" value="{{ $country->id }}" @checked(in_array((string) $country->id, array_map('strval', old('country_ids', isset($user) ? $user->countries->pluck('id')->map(fn ($id) => (string) $id)->all() : [])), true))>
                                    <label class="form-check-label small" for="country_{{ $country->id }}">{{ $country->name }}</label>
                                </div>
                            @empty
                                <span class="text-muted small">{{ __('No countries available.') }}</span>
                            @endforelse 
                        </div>
                        @error('country_ids.*')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- ✅ Action Buttons --}}
        <div class="d-flex justify-content-between mt-4 pt-3 border-top">
            <a class="btn btn-outline-secondary px-4" href="{{ route('users.index') }}">{{ __('Cancel') }}</a>
            <button class="btn btn-primary px-4 shadow-sm" type="submit">
                <i class="bi bi-check-lg me-1"></i> {{ isset($user) ? __('Save changes') : __('Create user') }}
            </button>
        </div>
    </form>
@endsection