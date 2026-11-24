
@props(['status' => null, 'label' => null])

@php
    $map = [
        'pending' => 'warning', 'approved' => 'success', 'completed' => 'success',
        'rejected' => 'danger', 'returned' => 'info', 'cancelled' => 'secondary',
        'fit' => 'success', 'unfit' => 'danger', 'cleared' => 'success',
        'uncleared' => 'danger', 'submitted' => 'primary', 'draft' => 'secondary',
        'posted' => 'success', 'reversed' => 'dark', 'active' => 'success',
        'inactive' => 'secondary', 'paid' => 'success', 'ticket_issued' => 'success',
        'arrived' => 'info', 'settled' => 'success', 'open' => 'warning',
        'resolved' => 'success', 'closed' => 'secondary', 'expired' => 'danger',
        'passed' => 'success', 'failed' => 'danger', 'confirmed' => 'success',
        'rescheduled' => 'warning', 'inside' => 'primary', 'left' => 'secondary',
    ];
    $color = $map[strtolower((string) $status)] ?? 'secondary';
    $text = $label;

    if ($text === null && $status !== null && $status !== '') {
        $text = ucwords(str_replace('_', ' ', (string) $status));
    }

    $text = $text ?? trim((string) $slot);
@endphp

<span {{ $attributes->merge(['class' => 'badge text-bg-' . $color]) }}>{{ $text }}</span>
