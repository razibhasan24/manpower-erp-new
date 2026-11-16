{{-- Backup history — Phase 29. --}}
{{-- MERGE FIX 2026-11-13 (coordinator): this view used the
     x-app-layout component, but no AppLayout component or class
     exists anywhere, so the page could not render. Converted to
     the layouts.app pattern every other view uses; the header
     slot content is now a page header inside the content. --}}
@extends('layouts.app')

@section('title', __('Backups'))

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">{{ __('Backups') }}</h2>
            @can('backup.create')
                <form method="POST" action="{{ route('backups.store') }}" class="d-flex gap-2">
                    @csrf
                    <select name="type" class="form-select form-select-sm" style="width:auto">
                        <option value="full">{{ __('Full backup (database + files)') }}</option>
                        <option value="db">{{ __('Database only') }}</option>
                        <option value="files">{{ __('Files only') }}</option>
                    </select>
                    <button type="submit" class="btn btn-primary btn-sm">{{ __('Create Backup') }}</button>
                </form>
            @endcan
        </div>
    </div>

    <div class="container-fluid py-4">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="alert alert-info">
            {{ __('Backups run automatically every day at 02:00 (Asia/Dhaka). Files are stored privately and integrity-checked after creation. Restore requires extra confirmation and is audit-logged.') }}
        </div>

        <div class="card">
            <div class="card-header">{{ __('Backup History') }}</div>
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>{{ __('Filename') }}</th>
                            <th>{{ __('Type') }}</th>
                            <th>{{ __('Size') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Verified') }}</th>
                            <th>{{ __('Created By') }}</th>
                            <th>{{ __('Created At') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($backups as $backup)
                            <tr>
                                <td>{{ $backup->filename }}</td>
                                <td><span class="badge bg-secondary">{{ $backup->type }}</span></td>
                                <td>@php $s = (float) $backup->size; $u = ['B','KB','MB','GB']; $i = 0; while ($s >= 1024 && $i < 3) { $s /= 1024; $i++; } echo round($s, 2).' '.$u[$i]; @endphp</td>
                                <td>
                                    <span class="badge bg-{{ $backup->status === 'completed' ? 'success' : 'danger' }}">{{ $backup->status }}</span>
                                </td>
                                <td>{{ $backup->verified_at ? $backup->verified_at->format('d M Y, h:i A') : '—' }}</td>
                                <td>{{ $backup->creator?->name ?? 'System' }}</td>
                                <td>{{ $backup->created_at->format('d M Y, h:i A') }}</td>
                                <td class="d-flex gap-1">
                                    @can('backup.download')
                                        <a href="{{ route('backups.download', $backup) }}" class="btn btn-outline-primary btn-sm">{{ __('Download') }}</a>
                                    @endcan
                                    @can('backup.restore')
                                        <a href="{{ route('backups.restore-form', $backup) }}" class="btn btn-outline-warning btn-sm">{{ __('Restore') }}</a>
                                    @endcan
                                    @can('backup.delete')
                                        <form method="POST" action="{{ route('backups.destroy', $backup) }}" onsubmit="return confirm('{{ __('Delete this backup permanently?') }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm">{{ __('Delete') }}</button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center text-muted py-4">{{ __('No backups yet.') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer">{{ $backups->links() }}</div>
        </div>
    </div>
@endsection
