
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['label' => null, 'title' => null, 'value', 'sub' => null, 'icon' => null]));

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

foreach (array_filter((['label' => null, 'title' => null, 'value', 'sub' => null, 'icon' => null]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $heading = $label ?? $title;
?>

<div <?php echo e($attributes->merge(['class' => 'card border-0 shadow-sm h-100 stat-card-hover'])); ?>>
    <div class="card-body p-4 d-flex justify-content-between align-items-center gap-3">
        <div class="min-w-0">
            <?php if($heading !== null && $heading !== ''): ?>
                <div class="text-muted small text-uppercase fw-semibold mb-1 text-truncate"><?php echo e($heading); ?></div>
            <?php endif; ?>

            <div class="fs-2 fw-bold text-dark mb-1 lh-1"><?php echo e($value); ?></div>

            <?php if($sub): ?>
                <div class="text-muted small mt-1"><?php echo e($sub); ?></div>
            <?php endif; ?>
        </div>

        <?php if($icon): ?>
            <div class="stat-card__icon bg-primary bg-opacity-10 text-primary rounded-4 d-flex align-items-center justify-content-center flex-shrink-0"
                aria-hidden="true" style="width: 56px; height: 56px;">
                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => $icon,'size' => 24]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($icon),'size' => 24]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $attributes = $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $component = $__componentOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php /**PATH R:\xampp\htdocs\manpower-erp-new\resources\views/components/stat-card.blade.php ENDPATH**/ ?>