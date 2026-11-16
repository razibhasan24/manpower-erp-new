
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['name', 'label', 'type' => 'text', 'value' => null, 'required' => false, 'placeholder' => null, 'hint' => null]));

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

foreach (array_filter((['name', 'label', 'type' => 'text', 'value' => null, 'required' => false, 'placeholder' => null, 'hint' => null]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="mb-3">
    <label for="<?php echo e($name); ?>" class="form-label"><?php echo e($label); ?><?php if($required): ?> <span class="text-danger">*</span><?php endif; ?></label>
    <input type="<?php echo e($type); ?>" name="<?php echo e($name); ?>" id="<?php echo e($name); ?>"
           value="<?php echo e(old($name, $value)); ?>" placeholder="<?php echo e($placeholder); ?>"
           <?php if($required): ?> required <?php endif; ?>
           <?php echo e($attributes->merge(['class' => 'form-control' . ($errors->has($name) ? ' is-invalid' : '')])); ?>>
    <?php $__errorArgs = [$name];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <div class="invalid-feedback"><?php echo e($message); ?></div>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    <?php if($hint): ?>
        <div class="form-text"><?php echo e($hint); ?></div>
    <?php endif; ?>
</div>
<?php /**PATH R:\xampp\htdocs\up2\resources\views/components/input.blade.php ENDPATH**/ ?>