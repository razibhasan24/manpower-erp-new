@extends('layouts.app')

@section('title', 'Recruitment Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Recruitment Details</h1>
    <div class="d-flex gap-2">
        @can('recruitment.view')
            <a href="{{ route('recruitments.download-format', [$recruitment, 'cv']) }}" class="btn btn-outline-dark">Download CV</a>
            <a href="{{ route('recruitments.download-format', [$recruitment, 'entry-form']) }}" class="btn btn-outline-dark">Barcode Entry</a>
            <a href="{{ route('recruitments.download-format', [$recruitment, 'id-card']) }}" class="btn btn-outline-dark">ID Card</a>
        @endcan
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
        <tr><th scope="row" style="width: 220px;">SL</th><td>{{ $recruitment->passportHolder?->id ?? '—' }}</td></tr>
        <tr><th scope="row" style="width: 220px;">Tracking Number</th><td>{{ $recruitment->tracking_number }}</td></tr>
        <tr><th scope="row">Registration Type</th><td>{{ match($recruitment->service_type) {'student' => 'Student', 'hajj_umrah' => 'Hajj & Umrah', default => 'Overseas Employment'} }}</td></tr>
        @if($recruitment->servicePackage)<tr><th scope="row">Package</th><td>{{ $recruitment->servicePackage->name }} ({{ $recruitment->servicePackage->code }})</td></tr><tr><th scope="row">Quoted Cost / Due</th><td>{{ $recruitment->quoted_currency }} {{ number_format((float) $recruitment->quoted_amount, 2) }} · Remaining due {{ number_format((float) $recruitment->remaining_due, 2) }}</td></tr>@endif
        <tr><th scope="row" style="width: 220px;">Passport Holder</th><td>{{ $recruitment->passportHolder?->full_name ?? "—" }}</td></tr>
        <tr><th scope="row" style="width: 220px;">Agent</th><td>{{ $recruitment->agent?->name ?? "—" }}</td></tr>
        <tr><th scope="row" style="width: 220px;">Branch</th><td>{{ $recruitment->branch?->name ?? "—" }}</td></tr>
        <tr><th scope="row" style="width: 220px;">Country</th><td>{{ $recruitment->country?->name ?? "—" }}</td></tr>
        <tr><th scope="row" style="width: 220px;">Company</th><td>{{ $recruitment->company?->name ?? "—" }}</td></tr>
        <tr><th scope="row" style="width: 220px;">Position</th><td>{{ $recruitment->position?->title ?? "—" }}</td></tr>
        <tr><th scope="row" style="width: 220px;">Expected Salary</th><td>{{ $recruitment->expected_salary !== null ? number_format((float) $recruitment->expected_salary, 2) : "—" }}</td></tr>
        <tr><th scope="row" style="width: 220px;">Current Stage</th><td><x-badge>{{ $recruitment->current_stage }}</x-badge></td></tr>
        <tr><th scope="row" style="width: 220px;">Current Status</th><td><x-badge>{{ $recruitment->current_status }}</x-badge></td></tr>
        <tr><th scope="row" style="width: 220px;">Financial Responsibility</th><td>{{ $recruitment->financial_responsible_type === 'agent' ? 'Agent: '.($recruitment->agent?->name ?? '—') : ($recruitment->financial_responsible_type === 'passport_holder' ? 'Passport Holder: '.($recruitment->passportHolder?->full_name ?? '—') : 'To be decided at Selection & Grading') }}</td></tr>
        <tr><th scope="row" style="width: 220px;">Remarks</th><td>{{ $recruitment->remarks ?? "—" }}</td></tr>
            </tbody>
        </table>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">Workflow</div>
    <div class="card-body">
        @if($recruitment->service_type === 'student' && $recruitment->current_status !== 'cancelled')
            @can('recruitment.edit')
            @can('recruitment.approve')
                @if(in_array([$recruitment->current_stage, $recruitment->current_status], [['selection', 'approved'], ['university_management', 'pending'], ['university_management', 'completed']], true))
                    <form method="POST" action="{{ route('recruitments.student-stage', $recruitment) }}" class="border rounded p-3 mb-3">@csrf<input type="hidden" name="stage" value="university_management"><h5>University Management</h5><label class="form-label">University *</label><select name="university_id" class="form-select mb-2" required><option value="">Select University</option>@foreach($universities as $university)<option value="{{ $university->id }}" @selected($recruitment->university_id==$university->id)>{{ $university->name }} — {{ $university->country }}</option>@endforeach</select><input name="remarks" class="form-control mb-2" placeholder="Notes"><button class="btn btn-primary">Save University &amp; Continue</button></form>
                @endif
                @if(in_array([$recruitment->current_stage, $recruitment->current_status], [['university_management', 'completed'], ['offer_letter', 'pending'], ['offer_letter', 'issued']], true))
                    <form method="POST" enctype="multipart/form-data" action="{{ route('recruitments.student-stage', $recruitment) }}" class="border rounded p-3 mb-3">@csrf<input type="hidden" name="stage" value="offer_letter"><h5>Offer Letter</h5><input type="file" name="document" class="form-control mb-2" accept=".pdf,.jpg,.jpeg,.png" required><input name="remarks" class="form-control mb-2" placeholder="Notes"><button class="btn btn-primary">Save Offer Letter &amp; Continue</button></form>
                @endif
                @if(in_array([$recruitment->current_stage, $recruitment->current_status], [['offer_letter', 'issued'], ['admission', 'pending'], ['admission', 'admitted']], true))
                    <form method="POST" enctype="multipart/form-data" action="{{ route('recruitments.student-stage', $recruitment) }}" class="border rounded p-3 mb-3">@csrf<input type="hidden" name="stage" value="admission"><h5>Admission</h5><input type="file" name="document" class="form-control mb-2" accept=".pdf,.jpg,.jpeg,.png" required><input name="remarks" class="form-control mb-2" placeholder="Notes"><button class="btn btn-primary">Save Admission &amp; Continue</button></form>
                @endif
            @endcan
            @endcan
        @endif
        <x-workflow-timeline :recruitment="$recruitment" :stages="$workflowStages" />
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
