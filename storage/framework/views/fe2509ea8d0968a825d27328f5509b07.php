
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['headers' => [], 'empty' => null, 'total' => null]));

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

foreach (array_filter((['headers' => [], 'empty' => null, 'total' => null]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php if($headers !== []): ?>
    <div <?php echo e($attributes->merge(['class' => 'table-responsive'])); ?>>
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <?php $__currentLoopData = $headers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $header): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <th><?php echo e($header); ?></th>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tr>
            </thead>
            <tbody>
                <?php echo e($slot); ?>

            </tbody>
        </table>
    </div>
<?php else: ?>
    <div <?php echo e($attributes->merge(['class' => 'table-responsive'])); ?>>
        <?php echo e($slot); ?>

    </div>
<?php endif; ?>

<?php if($total !== null): ?>
    <div class="card-footer text-muted small"><?php echo e(number_format($total)); ?> <?php echo e(__('record(s)')); ?></div>
<?php endif; ?>
<?php /**PATH R:\xampp\htdocs\manpower-erp-new\resources\views/components/table.blade.php ENDPATH**/ ?>