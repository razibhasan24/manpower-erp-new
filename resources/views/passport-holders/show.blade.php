@extends('layouts.app')

@section('title', 'Passport Holder Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Passport Holder Details</h1>
    <div class="d-flex gap-2">
        @can('passport-holder.edit')
            <x-button href="{{ route('passport-holders.edit', $passport_holder) }}">Edit</x-button>
        @endcan
        <x-button href="{{ route('passport-holders.index') }}" class="btn-outline-secondary">Back to list</x-button>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-borderless mb-0">
            <tbody>
        <tr><th scope="row" style="width: 220px;">Full Name</th><td>{{ $passport_holder->full_name }}</td></tr>
        <tr><th scope="row" style="width: 220px;">Father's Name</th><td>{{ $passport_holder->father_name ?? "—" }}</td></tr>
        <tr><th scope="row" style="width: 220px;">Mother's Name</th><td>{{ $passport_holder->mother_name ?? "—" }}</td></tr>
        <tr><th scope="row" style="width: 220px;">Date of Birth</th><td>{{ $passport_holder->dob ?? "—" }}</td></tr>
        <tr><th scope="row" style="width: 220px;">Gender</th><td>{{ $passport_holder->gender ?? "—" }}</td></tr>
        <tr><th scope="row" style="width: 220px;">Nationality</th><td>{{ $passport_holder->nationality ?? "—" }}</td></tr>
        <tr><th scope="row" style="width: 220px;">Phone</th><td>{{ $passport_holder->phone }}</td></tr>
        <tr><th scope="row" style="width: 220px;">Email</th><td>{{ $passport_holder->email ?? "—" }}</td></tr>
        <tr><th scope="row" style="width: 220px;">Present Address</th><td>{{ $passport_holder->present_address ?? "—" }}</td></tr>
        <tr><th scope="row" style="width: 220px;">Permanent Address</th><td>{{ $passport_holder->permanent_address ?? "—" }}</td></tr>
        <tr><th scope="row" style="width: 220px;">NID</th><td>{{ $passport_holder->nid ?? "—" }}</td></tr>
        <tr><th scope="row" style="width: 220px;">Passport Number</th><td>{{ $passport_holder->passport_number }}</td></tr>
        <tr><th scope="row" style="width: 220px;">Passport Issue Date</th><td>{{ $passport_holder->passport_issue_date ?? "—" }}</td></tr>
        <tr><th scope="row" style="width: 220px;">Passport Expiry Date</th><td>{{ $passport_holder->passport_expiry_date ?? "—" }}</td></tr>
        <tr><th scope="row" style="width: 220px;">Agent</th><td>{{ $passport_holder->agent?->name ?? "—" }}</td></tr>
        <tr><th scope="row" style="width: 220px;">Branch</th><td>{{ $passport_holder->branch?->name ?? "—" }}</td></tr>
        <tr><th scope="row" style="width: 220px;">Country</th><td>{{ $passport_holder->country?->name ?? "—" }}</td></tr>
        <tr><th scope="row" style="width: 220px;">Company</th><td>{{ $passport_holder->company?->name ?? "—" }}</td></tr>
        <tr><th scope="row" style="width: 220px;">Position</th><td>{{ $passport_holder->position?->title ?? "—" }}</td></tr>
        <tr><th scope="row" style="width: 220px;">Status</th><td><x-badge>{{ $passport_holder->status }}</x-badge></td></tr>
            </tbody>
        </table>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">Documents</div>
    <div class="card-body">
        @forelse ($passport_holder->documents as $document)
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span>{{ ucfirst(str_replace('_', ' ', $document->type)) }} - {{ $document->original_name ?? basename($document->file_path) }}</span>
                <x-button href="{{ route('passport-holders.documents.download', [$passport_holder, $document]) }}" class="btn-sm btn-outline-primary">Download</x-button>
            </div>
        @empty
            <p class="text-muted mb-0">No documents uploaded yet.</p>
        @endforelse
    </div>
</div>
<div class="card mt-4">
    <div class="card-header">Registrations</div>
    <div class="card-body">
        @forelse ($passport_holder->recruitments as $recruitment)
            <div><a href="{{ route('recruitments.show', $recruitment) }}">{{ $recruitment->tracking_number }}</a> - <x-badge>{{ $recruitment->current_status }}</x-badge></div>
        @empty
            <p class="text-muted mb-0">No registrations for this passport holder yet.</p>
        @endforelse
    </div>
</div>
@endsection
