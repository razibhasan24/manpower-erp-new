
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['label', 'value', 'sub' => null, 'icon' => null]));

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

foreach (array_filter((['label', 'value', 'sub' => null, 'icon' => null]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="card h-100">
    <div class="card-body d-flex justify-content-between align-items-start">
        <div>
            <div class="small text-muted"><?php echo e($label); ?></div>
            <div class="h3 mb-0"><?php echo e($value); ?></div>
            <?php if($sub): ?><div class="small text-muted mt-1"><?php echo e($sub); ?></div><?php endif; ?>
        </div>
        <?php if($icon): ?><div class="fs-3" aria-hidden="true"><?php echo e($icon); ?></div><?php endif; ?>
    </div>
</div>
<?php /**PATH R:\xampp\htdocs\manpower-erp-new\resources\views/components/stat-card.blade.php ENDPATH**/ ?>