<?php $__env->startSection('title', 'Daily Visitor Report'); ?>
<?php $__env->startSection('content'); ?>
<h1 class="mb-3">Daily Visitor Report — <?php echo e($date); ?></h1>
<form method="GET" class="row g-2 mb-3">
    <div class="col-md-3"><input type="date" name="date" value="<?php echo e($date); ?>" class="form-control"></div>
    <div class="col-auto"><button class="btn btn-secondary" type="submit">Show</button></div>
</form>
<p class="text-muted"><?php echo e($items->count()); ?> visitor(s) on this day.</p>
<div class="card"><div class="table-responsive">
<table class="table table-striped mb-0">
    <thead><tr><th>Pass</th><th>Name</th><th>Phone</th><th>Purpose</th><th>Person to Meet</th><th>Entry</th><th>Exit</th></tr></thead>
    <tbody>
    <?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <tr><td><?php echo e($item->pass_number); ?></td><td><?php echo e($item->name); ?></td><td><?php echo e($item->phone); ?></td><td><?php echo e($item->purpose); ?></td><td><?php echo e($item->person_to_meet); ?></td><td><?php echo e($item->entry_time); ?></td><td><?php echo e($item->exit_time ?? 'Inside'); ?></td></tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <tr><td colspan="7" class="text-center text-muted py-4">No visitors on this day.</td></tr>
    <?php endif; ?>
    </tbody>
</table>
</div></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\xampp\htdocs\manpower-erp-new\resources\views/visitors/daily-report.blade.php ENDPATH**/ ?>