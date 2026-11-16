{{--
    x-workflow-timeline
    Props:
      - histories: collection of RecruitmentStageHistory models (oldest first)
      - stages: ordered stage list (default: WorkflowService::stages())
    Renders the append-only stage history of a recruitment: stage, status
    badge, who changed it, when, and remarks. History is never edited here.
--}}
@props(['histories', 'stages' => null])

@php
    $stages = $stages ?? \App\Services\WorkflowService::stages();
    $reached = $histories->pluck('stage')->unique()->all();
@endphp

<ol class="workflow-timeline list-unstyled">
    @foreach($stages as $stage)
        @php $done = in_array($stage, $reached, true); @endphp
        <li class="workflow-step {{ $done ? 'done' : 'pending' }}">
            <strong>{{ ucwords(str_replace('_', ' ', $stage)) }}</strong>
            @if($done)
                @foreach($histories->where('stage', $stage) as $history)
                    <div class="small">
                        <x-badge :status="$history->status" />
                        <span class="text-muted">{{ $history->changedBy?->name }} &middot; {{ $history->changed_at?->format('d-m-Y H:i') }}</span>
                        @if($history->remarks)<div>{{ $history->remarks }}</div>@endif
                    </div>
                @endforeach
            @else
                <div class="small text-muted">{{ __('Not reached') }}</div>
            @endif
        </li>
    @endforeach
</ol>
