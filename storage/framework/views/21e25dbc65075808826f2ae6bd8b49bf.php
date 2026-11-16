<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['item']));

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

foreach (array_filter((['item']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<a
    href="<?php echo e($item['url']); ?>"
    class="sidebar-link <?php echo e(!empty($item['active']) ? 'is-active' : ''); ?>"
    <?php if(!empty($item['active'])): ?> aria-current="page" <?php endif; ?>
    title="<?php echo e($item['label']); ?>"
>
    <span class="sidebar-link__dot" aria-hidden="true"></span>
    <span class="sidebar-link__label"><?php echo e($item['label']); ?></span>
</a>
<?php /**PATH R:\xampp\htdocs\up2\resources\views/components/sidebar-link.blade.php ENDPATH**/ ?>