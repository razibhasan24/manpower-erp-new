<?php $__env->startSection('title', 'Trial Balance'); ?>
<?php $__env->startSection('content'); ?>
<h1 class="mb-3">Trial Balance</h1>
<form method="GET" class="row g-2 mb-3">
    <div class="col-md-2"><input type="date" name="from" value="<?php echo e($filters['from'] ?? ''); ?>" class="form-control" placeholder="From"></div>
    <div class="col-md-2"><input type="date" name="to" value="<?php echo e($filters['to'] ?? ''); ?>" class="form-control" placeholder="To"></div>
    <div class="col-auto"><button class="btn btn-secondary" type="submit">Filter</button></div>
    <div class="col-auto">
        <a class="btn btn-outline-primary" href="<?php echo e(request()->fullUrlWithQuery(['export' => 'pdf'])); ?>">Export PDF</a>
        <a class="btn btn-outline-primary" href="<?php echo e(request()->fullUrlWithQuery(['export' => 'excel'])); ?>">Export Excel</a>
    </div>
</form>

<?php if(session('success')): ?><div class="alert alert-success"><?php echo e(session('success')); ?></div><?php endif; ?>
<div class="card"><div class="table-responsive">
<table class="table table-striped mb-0">
    <thead><tr><th>Code</th><th>Account</th><th class="text-end">Debit</th><th class="text-end">Credit</th></tr></thead>
    <tbody>
    <?php $__empty_1 = true; $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <tr><td><?php echo e($row['code']); ?></td><td><?php echo e($row['name']); ?></td>
            <td class="text-end"><?php echo e(number_format($row['debit'], 2)); ?></td><td class="text-end"><?php echo e(number_format($row['credit'], 2)); ?></td></tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <tr><td colspan="4" class="text-center text-muted py-4">No posted entries in this period.</td></tr>
    <?php endif; ?>
    </tbody>
    <tfoot><tr><th colspan="2">Total</th><th class="text-end"><?php echo e(number_format($total_debit, 2)); ?></th><th class="text-end"><?php echo e(number_format($total_credit, 2)); ?></th></tr></tfoot>
</table>
</div></div>
<p class="mt-2"><?php if($balanced): ?><span class="badge bg-success">Balanced</span><?php else: ?><span class="badge bg-danger">Out of balance — investigate immediately</span><?php endif; ?></p>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\xampp\htdocs\manpower-erp-new\resources\views/reports/financial/trial-balance.blade.php ENDPATH**/ ?>