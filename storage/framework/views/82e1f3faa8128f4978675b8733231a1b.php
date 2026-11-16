
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['name' => 'circle-help', 'size' => 18, 'stroke' => 1.8]));

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

foreach (array_filter((['name' => 'circle-help', 'size' => 18, 'stroke' => 1.8]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $icons = config('icons', []);
    $inner = $icons[$name] ?? $icons['circle-help'] ?? '';
?>

<svg
    <?php echo e($attributes->merge(['class' => 'icon'])); ?>

    xmlns="http://www.w3.org/2000/svg"
    width="<?php echo e($size); ?>"
    height="<?php echo e($size); ?>"
    viewBox="0 0 24 24"
    fill="none"
    stroke="currentColor"
    stroke-width="<?php echo e($stroke); ?>"
    stroke-linecap="round"
    stroke-linejoin="round"
    aria-hidden="true"
    focusable="false"
><?php echo $inner; ?></svg>
<?php /**PATH R:\xampp\htdocs\manpower-erp-new\resources\views/components/icon.blade.php ENDPATH**/ ?>