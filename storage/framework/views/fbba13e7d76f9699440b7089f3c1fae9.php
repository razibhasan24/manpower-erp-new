<?php $__env->startSection('title', 'Account Types'); ?>
<?php $__env->startSection('content'); ?>
<h1 class="mb-3">Account Types</h1>
<p class="text-muted">The five account types are seeded and not user-editable.</p>
<div class="card"><div class="table-responsive">
<table class="table table-striped mb-0">
    <thead><tr><th>Name</th><th>Normal Balance</th><th class="text-end">Actions</th></tr></thead>
    <tbody>
    <?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <tr><td><?php echo e($item->name); ?></td><td><?php echo e($item->normal_balance); ?></td>
            <td class="text-end"><a href="<?php echo e(route('accounting.account-types.show', $item)); ?>" class="btn btn-sm btn-outline-primary">View</a></td></tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <tr><td colspan="3" class="text-center text-muted py-4">No account types found. Run the AccountTypeSeeder.</td></tr>
    <?php endif; ?>
    </tbody>
</table>
</div></div>
<div class="mt-3"><?php echo e($items->links()); ?></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\xampp\htdocs\manpower-erp-new\resources\views/accounting/account-types/index.blade.php ENDPATH**/ ?>