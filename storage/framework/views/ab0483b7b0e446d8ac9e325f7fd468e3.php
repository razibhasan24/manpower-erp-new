
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['status' => null, 'label' => null]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['status' => null, 'label' => null]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
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
?>

<span <?php echo e($attributes->merge(['class' => 'badge text-bg-' . $color])); ?>><?php echo e($text); ?></span>
<?php /**PATH R:\xampp\htdocs\up2\resources\views/components/badge.blade.php ENDPATH**/ ?>