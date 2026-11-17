{{--
    x-workflow-timeline

    Props:
      - histories: Collection of RecruitmentStageHistory models (oldest first)
      - stages: Ordered stage list (default: WorkflowService::stages())

    Displays append-only recruitment workflow history.
--}}

@props([
    'histories' => collect(),
    'stages' => null,
])

@php
    $histories = collect($histories);

    $stages = $stages ?? \App\Services\WorkflowService::stages();

    $reached = $histories->pluck('stage')->unique()->all();
@endphp

<ol class="workflow-timeline list-unstyled">
    @foreach ($stages as $stage)
        @php
            $done = in_array($stage, $reached, true);
        @endphp

        <li class="workflow-step {{ $done ? 'done' : 'pending' }}">
            <strong>
                {{ ucwords(str_replace('_', ' ', $stage)) }}
            </strong>

            @if ($done)
                @foreach ($histories->where('stage', $stage) as $history)
                    <div class="small mt-2">
                        <x-badge :status="$history->status" />

                        <span class="text-muted">
                            {{ $history->changedBy?->name ?? 'System' }}
                            &middot;
                            {{ $history->changed_at?->format('d-m-Y H:i') ?? 'N/A' }}
                        </span>

                        @if ($history->remarks)
                            <div class="mt-1">
                                {{ $history->remarks }}
                            </div>
                        @endif
                    </div>
                @endforeach
            @else
                <div class="small text-muted">
                    {{ __('Not reached') }}
                </div>
            @endif
        </li>
    @endforeach
</ol>
