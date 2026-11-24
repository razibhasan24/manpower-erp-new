<?php $__env->startSection('title', 'Account'); ?>
<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Account</h1>
    <div>
        <a href="<?php echo e(route('accounting.accounts.index')); ?>" class="btn btn-secondary">Back to list</a>
        <a href="<?php echo e(route('accounting.accounts.edit', $item)); ?>" class="btn btn-outline-primary">Edit</a>
    </div>
</div>
<?php if(session('success')): ?>
    <div class="alert alert-success"><?php echo e(session('success')); ?></div>
<?php endif; ?>
<div class="card"><div class="card-body">
<table class="table table-bordered mb-0">
<tr><th style="width:200px">Code</th><td><?php echo e($item->code); ?></td></tr><tr><th style="width:200px">Name</th><td><?php echo e($item->name); ?></td></tr><tr><th style="width:200px">Parent</th><td><?php echo e($item->parent->name ?? '—'); ?></td></tr><tr><th style="width:200px">Account Type</th><td><?php echo e($item->type->name ?? ''); ?></td></tr><tr><th style="width:200px">Normal Balance</th><td><?php echo e($item->normal_balance); ?></td></tr><tr><th style="width:200px">Status</th><td><?php if (isset($component)) { $__componentOriginal2ddbc40e602c342e508ac696e52f8719 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2ddbc40e602c342e508ac696e52f8719 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.badge','data' => ['status' => $item->status]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($item->status)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2ddbc40e602c342e508ac696e52f8719)): ?>
<?php $attributes = $__attributesOriginal2ddbc40e602c342e508ac696e52f8719; ?>
<?php unset($__attributesOriginal2ddbc40e602c342e508ac696e52f8719); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2ddbc40e602c342e508ac696e52f8719)): ?>
<?php $component = $__componentOriginal2ddbc40e602c342e508ac696e52f8719; ?>
<?php unset($__componentOriginal2ddbc40e602c342e508ac696e52f8719); ?>
<?php endif; ?></td></tr>
</table>

</div></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\xampp\htdocs\manpower-erp-new\resources\views/accounting/accounts/show.blade.php ENDPATH**/ ?>