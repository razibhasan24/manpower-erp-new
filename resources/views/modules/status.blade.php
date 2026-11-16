@extends('layouts.app')

@section('title', $module['label'] . ' — ' . \App\Services\BrandingService::appName())

@section('content')
    <div class="module-status">
        <div class="module-status__hero">
            <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
                <div>
                    <div class="module-status__eyebrow">
                        <x-icon name="construction" :size="14" /> {{ $module['group'] }}
                    </div>
                    <div class="module-status__icon mt-3">
                        <x-icon :name="$module['icon'] ?? 'file-text'" :size="28" />
                    </div>
                    <h1 class="mb-2">{{ $module['label'] }}</h1>
                    <span class="module-status__badge">
                        <x-icon name="clock" :size="13" /> {{ __('In development') }}
                    </span>
                </div>

                <div class="text-end">
                    <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-secondary">{{ __('Back to dashboard') }}</a>
                </div>
            </div>

            <p class="mt-3 mb-0 text-secondary">{{ $module['description'] }}</p>
        </div>

        <div class="alert alert-warning d-flex align-items-start gap-2 mt-3 mb-0" role="alert">
            <span class="flex-shrink-0 mt-1"><x-icon name="triangle-alert" :size="17" /></span>
            <div>
                <strong class="d-block">{{ __('This module is not available yet.') }}</strong>
                {{ __('This page is a development-status landing page. It deliberately contains no create, edit or delete actions, so no unfinished operation is presented as working.') }}
            </div>
        </div>

        <div class="row g-3 mt-1">
            <div class="col-lg-7">
                <div class="card h-100">
                    <div class="card-header">{{ __('Planned capabilities') }}</div>
                    <div class="card-body">
                        <ul class="module-status__planned">
                            @foreach($module['planned'] ?? [] as $item)
                                <li>
                                    <x-icon name="circle-help" :size="15" />
                                    <span>{{ $item }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card h-100">
                    <div class="card-header">{{ __('Available today') }}</div>
                    <div class="card-body d-flex flex-column">
                        <p class="text-secondary small">{{ __('The related working module below is fully available. Use it for the records that already exist.') }}</p>

                        @if(! empty($module['related_route']) && \Illuminate\Support\Facades\Route::has($module['related_route']))
                            <a href="{{ route($module['related_route'], $module['related_params'] ?? []) }}" class="btn btn-primary mt-auto d-inline-flex align-items-center justify-content-center gap-2">
                                {{ $module['related_label'] ?? __('Open related module') }}
                                <x-icon name="arrow-right" :size="15" />
                            </a>
                        @else
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary mt-auto">{{ __('Back to dashboard') }}</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <p class="small text-secondary mt-3 mb-0">
            {{ __('Module key') }}: <code>{{ $moduleKey }}</code> &middot; {{ __('Access is restricted by the module permission.') }}
        </p>
    </div>
@endsection
