{{-- Reports index — Phase 28. Lists every report the current user may open, grouped. --}}
{{-- MERGE FIX 2026-11-13 (coordinator): this view used the
     x-app-layout component, but no AppLayout component or class
     exists anywhere, so the page could not render. Converted to
     the layouts.app pattern every other view uses; the header
     slot content is now a page header inside the content. --}}
@extends('layouts.app')

@section('title', __('Reports & Analytics'))

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="h4 mb-0">{{ __('Reports & Analytics') }}</h2>
    </div>

    <div class="container-fluid py-4">
        @forelse ($groups as $group => $reports)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">{{ $group }}</h5>
                </div>
                <div class="list-group list-group-flush">
                    @foreach ($reports as $key => $definition)
                        <a href="{{ route('reports.show', $key) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span>{{ $definition['title'] }}</span>
                            <span class="badge bg-primary">View report</span>
                        </a>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="alert alert-info">{{ __('No reports are available for your role.') }}</div>
        @endforelse
    </div>
@endsection
