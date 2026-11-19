<?php $__env->startSection('title', 'Salary Structures'); ?>
<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Salary Structures</h1>
    <a href="<?php echo e(route('hrm.salary-structures.create')); ?>" class="btn btn-primary">Add Salary Structure</a>
</div>
<?php if(session('success')): ?>
    <div class="alert alert-success"><?php echo e(session('success')); ?></div>
<?php endif; ?>
<form method="GET" class="row g-2 mb-3">
    <div class="col-md-4"><input type="text" name="search" value="<?php echo e(request('search')); ?>" class="form-control" placeholder="Search..."></div>
    <div class="col-auto"><button class="btn btn-secondary" type="submit">Search</button></div>
</form>
<div class="card"><div class="table-responsive">
<table class="table table-striped mb-0">
    <thead><tr><th>Employee</th><th>Basic</th><th>Effective From</th><th class="text-end">Actions</th></tr></thead>
    <tbody>
    <?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <tr><td><?php echo e($item->employee->user->name ?? ''); ?></td><td><?php echo e(number_format($item->basic, 2)); ?></td><td><?php echo e($item->effective_from); ?></td>
            <td class="text-end">
                <a href="<?php echo e(route('hrm.salary-structures.show', $item)); ?>" class="btn btn-sm btn-outline-primary">View</a>
                <a href="<?php echo e(route('hrm.salary-structures.edit', $item)); ?>" class="btn btn-sm btn-outline-secondary">Edit</a>
            </td>
        </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <tr><td colspan="99" class="text-center text-muted py-4">No records found.</td></tr>
    <?php endif; ?>
    </tbody>
</table>
</div></div>
<div class="mt-3"><?php echo e($items->links()); ?></div>
<p class="text-muted small"><?php echo e($items->total()); ?> record(s) found.</p>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\xampp\htdocs\manpower-erp-new\resources\views/hrm/salary-structures/index.blade.php ENDPATH**/ ?>