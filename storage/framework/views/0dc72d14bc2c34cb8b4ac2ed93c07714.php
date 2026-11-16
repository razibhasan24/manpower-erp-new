
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['type' => 'submit', 'variant' => 'primary', 'href' => null, 'size' => null, 'confirm' => null]));

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

foreach (array_filter((['type' => 'submit', 'variant' => 'primary', 'href' => null, 'size' => null, 'confirm' => null]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $classes = 'btn btn-' . $variant . ($size ? ' btn-' . $size : '');
    $attrs = $confirm ? 'onclick="return confirm(' . json_encode($confirm) . ')"' : '';
?>

<?php if($href): ?>
    <a href="<?php echo e($href); ?>" <?php echo e($attributes->merge(['class' => $classes])); ?> <?php echo $attrs; ?>><?php echo e($slot); ?></a>
<?php else: ?>
    <button type="<?php echo e($type); ?>" <?php echo e($attributes->merge(['class' => $classes])); ?> <?php echo $attrs; ?>><?php echo e($slot); ?></button>
<?php endif; ?>
<?php /**PATH R:\xampp\htdocs\up2\resources\views/components/button.blade.php ENDPATH**/ ?>