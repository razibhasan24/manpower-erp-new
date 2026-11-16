@extends('layouts.app')

@section('title', 'Recruitment Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Recruitment Details</h1>
    <div class="d-flex gap-2">
        @can('recruitment.edit')
            <x-button href="{{ route('recruitments.edit', $recruitment) }}">Edit</x-button>
        @endcan
        <x-button href="{{ route('recruitments.index') }}" class="btn-outline-secondary">Back to list</x-button>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-borderless mb-0">
            <tbody>
        <tr><th scope="row" style="width: 220px;">Tracking Number</th><td>{{ $recruitment->tracking_number }}</td></tr>
        <tr><th scope="row" style="width: 220px;">Passport Holder</th><td>{{ $recruitment->passportHolder?->full_name ?? "—" }}</td></tr>
        <tr><th scope="row" style="width: 220px;">Agent</th><td>{{ $recruitment->agent?->name ?? "—" }}</td></tr>
        <tr><th scope="row" style="width: 220px;">Branch</th><td>{{ $recruitment->branch?->name ?? "—" }}</td></tr>
        <tr><th scope="row" style="width: 220px;">Country</th><td>{{ $recruitment->country?->name ?? "—" }}</td></tr>
        <tr><th scope="row" style="width: 220px;">Company</th><td>{{ $recruitment->company?->name ?? "—" }}</td></tr>
        <tr><th scope="row" style="width: 220px;">Position</th><td>{{ $recruitment->position?->title ?? "—" }}</td></tr>
        <tr><th scope="row" style="width: 220px;">Expected Salary</th><td>{{ $recruitment->expected_salary !== null ? number_format((float) $recruitment->expected_salary, 2) : "—" }}</td></tr>
        <tr><th scope="row" style="width: 220px;">Current Stage</th><td><x-badge>{{ $recruitment->current_stage }}</x-badge></td></tr>
        <tr><th scope="row" style="width: 220px;">Current Status</th><td><x-badge>{{ $recruitment->current_status }}</x-badge></td></tr>
        <tr><th scope="row" style="width: 220px;">Financial Responsibility</th><td><span class="text-muted">Undecided - decided at Selection &amp; Grading, not at registration.</span></td></tr>
        <tr><th scope="row" style="width: 220px;">Remarks</th><td>{{ $recruitment->remarks ?? "—" }}</td></tr>
            </tbody>
        </table>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">Workflow</div>
    <div class="card-body">
        <x-workflow-timeline :recruitment="$recruitment" />
    </div>
</div>
<div class="card mt-4">
    <div class="card-header">Documents</div>
    <div class="card-body">
        @forelse ($recruitment->documents as $document)
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span>{{ ucfirst(str_replace('_', ' ', $document->type)) }} - {{ $document->original_name ?? basename($document->file_path) }}</span>
                <x-button href="{{ route('recruitments.documents.download', [$recruitment, $document]) }}" class="btn-sm btn-outline-primary">Download</x-button>
            </div>
        @empty
            <p class="text-muted mb-0">No documents uploaded yet.</p>
        @endforelse
    </div>
</div>
<div class="card mt-4">
    <div class="card-header">Stage History</div>
    <div class="card-body">
        @forelse ($recruitment->stageHistories as $history)
            <div>{{ $history->stage }} - {{ $history->status }} - {{ $history->changed_at }} @if($history->remarks) - {{ $history->remarks }} @endif</div>
        @empty
            <p class="text-muted mb-0">No stage history recorded yet.</p>
        @endforelse
    </div>
</div>
@if ($recruitment->current_status !== 'cancelled' && $recruitment->current_status !== 'completed')
<div class="card mt-4">
    <div class="card-header">Cancel Registration</div>
    <div class="card-body">
        @can('cancel', $recruitment)
        <form method="POST" action="{{ route('recruitments.cancel', $recruitment) }}">
            @csrf
            <div class="mb-3">
                <label for="cancel_remarks" class="form-label">Cancellation Remarks</label>
                <textarea id="cancel_remarks" name="remarks" rows="2" class="form-control"></textarea>
            </div>
            <button type="submit" class="btn btn-danger" onclick="return confirm('Cancel this registration? The record will be kept as history.')">Cancel Registration</button>
        </form>
        @endcan
    </div>
</div>
@endif
@endsection
