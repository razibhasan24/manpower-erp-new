@extends('layouts.app')

@section('title', 'Agent Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Agent Details</h1>
    <div class="d-flex gap-2">
        @can('agent.edit')
            <x-button href="{{ route('agents.edit', $agent) }}">Edit</x-button>
        @endcan
        <x-button href="{{ route('agents.index') }}" class="btn-outline-secondary">Back to list</x-button>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-borderless mb-0">
            <tbody>
        <tr><th scope="row" style="width: 220px;">Name</th><td>{{ $agent->name }}</td></tr>
        <tr><th scope="row" style="width: 220px;">Phone</th><td>{{ $agent->phone }}</td></tr>
        <tr><th scope="row" style="width: 220px;">Email</th><td>{{ $agent->email ?? "—" }}</td></tr>
        <tr><th scope="row" style="width: 220px;">Address</th><td>{{ $agent->address ?? "—" }}</td></tr>
        <tr><th scope="row" style="width: 220px;">NID</th><td>{{ $agent->nid ?? "—" }}</td></tr>
        <tr><th scope="row" style="width: 220px;">Trade License</th><td>{{ $agent->trade_license ?? "—" }}</td></tr>
        <tr><th scope="row" style="width: 220px;">Branch</th><td>{{ $agent->branch?->name ?? "—" }}</td></tr>
        <tr><th scope="row" style="width: 220px;">Authorised Countries</th><td>@foreach ($agent->countries as $country)<x-badge>{{ $country->name }}</x-badge> @endforeach</td></tr>
        <tr><th scope="row" style="width: 220px;">Status</th><td><x-badge>{{ $agent->status }}</x-badge></td></tr>
            </tbody>
        </table>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">Authorised Countries</div>
    <div class="card-body">
        @can('agent.edit')
        <form method="POST" action="{{ route('agents.countries', $agent) }}" class="mb-3">
            @csrf
            @method('PUT')
            <div class="row">
                @foreach (\App\Models\Country::query()->visibleTo(auth()->user())->where('status', 'active')->orderBy('name')->get() as $country)
                    <div class="col-md-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="country_ids[]" value="{{ $country->id }}" id="sync-country-{{ $country->id }}" @checked($agent->countries->contains($country->id))>
                            <label class="form-check-label" for="sync-country-{{ $country->id }}">{{ $country->name }}</label>
                        </div>
                    </div>
                @endforeach
            </div>
            <button type="submit" class="btn btn-primary mt-2">Save Authorised Countries</button>
        </form>
        @endcan
    </div>
</div>
<div class="row mt-4">
    <div class="col-md-6"><x-stat-card title="Passport Holders" :value="$agent->passportHolders()->count()" /></div>
    <div class="col-md-6"><x-stat-card title="Registrations" :value="$agent->recruitments()->count()" /></div>
</div>
<div class="card mt-4">
    <div class="card-header">Registrations by this agent</div>
    <div class="card-body">
        @forelse ($agent->recruitments as $recruitment)
            <div><a href="{{ route('recruitments.show', $recruitment) }}">{{ $recruitment->tracking_number }}</a> - {{ $recruitment->passportHolder?->full_name }} - <x-badge>{{ $recruitment->current_status }}</x-badge></div>
        @empty
            <p class="text-muted mb-0">No registrations by this agent yet.</p>
        @endforelse
    </div>
</div>
@endsection
