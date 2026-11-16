{{-- Restore confirmation — Phase 29. Extra confirmation step (prompt §29). --}}
{{-- MERGE FIX 2026-11-13 (coordinator): this view used the
     x-app-layout component, but no AppLayout component or class
     exists anywhere, so the page could not render. Converted to
     the layouts.app pattern every other view uses; the header
     slot content is now a page header inside the content. --}}
@extends('layouts.app')

@section('title', __('Restore Backup'))

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="h4 mb-0">{{ __('Restore Backup') }}</h2>
    </div>

    <div class="container-fluid py-4">
        <div class="card border-danger">
            <div class="card-header bg-danger text-white">{{ __('Danger: restoring replaces current data') }}</div>
            <div class="card-body">
                <p>
                    {{ __('You are about to restore backup') }} <strong>{{ $backup->filename }}</strong>
                    ({{ $backup->type }}, @php $s = (float) $backup->size; $u = ['B','KB','MB','GB']; $i = 0; while ($s >= 1024 && $i < 3) { $s /= 1024; $i++; } echo round($s, 2).' '.$u[$i]; @endphp, {{ __('created') }} {{ $backup->created_at->format('d M Y, h:i A') }}).
                </p>
                <p>
                    {{ __('The current database will be replaced. A safety backup of the current state is created automatically before the restore starts, and this restore is written to the audit log.') }}
                </p>
                <p class="text-danger fw-bold">{{ __('Never restore on the live database without a fresh backup first.') }}</p>

                <form method="POST" action="{{ route('backups.restore', $backup) }}">
                    @csrf
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="confirmed" value="1" id="confirmed" required>
                        <label class="form-check-label" for="confirmed">
                            {{ __('I understand this will replace current data and I want to proceed.') }}
                        </label>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="confirmation_phrase">{{ __('Type RESTORE to confirm') }}</label>
                        <input type="text" name="confirmation_phrase" id="confirmation_phrase" class="form-control" required autocomplete="off">
                        @error('confirmation_phrase')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-danger">{{ __('Restore Now') }}</button>
                    <a href="{{ route('backups.index') }}" class="btn btn-outline-secondary">{{ __('Cancel') }}</a>
                </form>
            </div>
        </div>
    </div>
@endsection
