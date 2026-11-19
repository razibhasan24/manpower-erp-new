<?php $__env->startSection('title', 'Visitors'); ?>
<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Visitors</h1>
    <div>
        <a href="<?php echo e(route('visitors.daily-report')); ?>" class="btn btn-outline-secondary">Daily Report</a>
        <a href="<?php echo e(route('visitors.monthly-report')); ?>" class="btn btn-outline-secondary">Monthly Report</a>
        <a href="<?php echo e(route('visitors.create')); ?>" class="btn btn-primary">Register Visitor</a>
    </div>
</div>
<?php if(session('success')): ?>
    <div class="alert alert-success"><?php echo e(session('success')); ?></div>
<?php endif; ?>
<form method="GET" class="row g-2 mb-3">
    <div class="col-md-3"><input type="text" name="search" value="<?php echo e(request('search')); ?>" class="form-control" placeholder="Name, phone or pass number..."></div>
    <div class="col-md-2"><input type="date" name="date" value="<?php echo e(request('date')); ?>" class="form-control"></div>
    <div class="col-auto"><button class="btn btn-secondary" type="submit">Search</button></div>
</form>
<div class="card"><div class="table-responsive">
<table class="table table-striped mb-0">
    <thead><tr><th>Pass</th><th>Name</th><th>Phone</th><th>Purpose</th><th>Person to Meet</th><th>Entry</th><th>Exit</th><th class="text-end">Actions</th></tr></thead>
    <tbody>
    <?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <tr><td><?php echo e($item->pass_number); ?></td><td><?php echo e($item->name); ?></td><td><?php echo e($item->phone); ?></td>
            <td><?php echo e($item->purpose); ?></td><td><?php echo e($item->person_to_meet); ?></td>
            <td><?php echo e($item->entry_time); ?></td><td><?php echo e($item->exit_time ?? 'Inside'); ?></td>
            <td class="text-end">
                <a href="<?php echo e(route('visitors.show', $item)); ?>" class="btn btn-sm btn-outline-primary">View</a>
                <a href="<?php echo e(route('visitors.pass', $item)); ?>" class="btn btn-sm btn-outline-secondary">Pass</a>
            </td></tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <tr><td colspan="8" class="text-center text-muted py-4">No visitors found.</td></tr>
    <?php endif; ?>
    </tbody>
</table>
</div></div>
<div class="mt-3"><?php echo e($items->links()); ?></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\xampp\htdocs\manpower-erp-new\resources\views/visitors/index.blade.php ENDPATH**/ ?>