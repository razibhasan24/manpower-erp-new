{{--
    x-badge
    Props:
      - status: optional status string used to choose the Bootstrap colour
      - label: optional override text
    If no status is supplied, the slot text is displayed with a neutral colour.
    Known statuses: pending, approved, completed, rejected, returned, cancelled,
    fit, unfit, cleared, uncleared, submitted, draft, posted, reversed, active,
    inactive, paid, ticket_issued, arrived, settled, open, resolved, closed.
--}}
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
