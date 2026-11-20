<?php $__env->startSection('title', 'Payment Modes'); ?>
<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h1 class="mb-1">Payment Modes</h1>
        <p class="text-muted mb-0">Set up Cash and Bank options and link each one to its accounting ledger.</p>
    </div>
    <a href="<?php echo e(route('accounting.payment-modes.create')); ?>" class="btn btn-primary">Add Payment Mode</a>
</div>
<?php if(session('success')): ?><div class="alert alert-success"><?php echo e(session('success')); ?></div><?php endif; ?>
<div class="card"><div class="table-responsive">
    <table class="table table-striped mb-0">
        <thead><tr><th>Name</th><th>Type</th><th>Linked Account</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
        <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><?php echo e($item->name); ?></td>
                <td><?php echo e(ucfirst($item->mode_type)); ?></td>
                <td><?php echo e($item->account?->code); ?> — <?php echo e($item->account?->name); ?></td>
                <td><?php if (isset($component)) { $__componentOriginal2ddbc40e602c342e508ac696e52f8719 = $component; } ?>
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
<?php endif; ?></td>
                <td class="text-end">
                    <a href="<?php echo e(route('accounting.payment-modes.edit', $item)); ?>" class="btn btn-sm btn-outline-secondary">Edit</a>
                    <form action="<?php echo e(route('accounting.payment-modes.destroy', $item)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Remove this payment mode?')">
                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                        <button class="btn btn-sm btn-outline-danger" type="submit">Remove</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="5" class="text-center text-muted py-4">No payment modes added yet.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div></div>
<div class="mt-3"><?php echo e($items->links()); ?></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\xampp\htdocs\manpower-erp-new\resources\views/accounting/payment-modes/index.blade.php ENDPATH**/ ?>