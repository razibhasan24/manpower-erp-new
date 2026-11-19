<?php $__env->startSection('title', 'Cash Book'); ?>
<?php $__env->startSection('content'); ?>
<h1 class="mb-3">Cash Book</h1>
<form method="GET" class="row g-2 mb-3">
    <div class="col-md-2"><input type="date" name="from" value="<?php echo e($filters['from'] ?? ''); ?>" class="form-control" placeholder="From"></div>
    <div class="col-md-2"><input type="date" name="to" value="<?php echo e($filters['to'] ?? ''); ?>" class="form-control" placeholder="To"></div>
    <div class="col-auto"><button class="btn btn-secondary" type="submit">Filter</button></div>
    <div class="col-auto">
        <a class="btn btn-outline-primary" href="<?php echo e(request()->fullUrlWithQuery(['export' => 'pdf'])); ?>">Export PDF</a>
        <a class="btn btn-outline-primary" href="<?php echo e(request()->fullUrlWithQuery(['export' => 'excel'])); ?>">Export Excel</a>
    </div>
</form>

<div class="card"><div class="table-responsive">
<table class="table mb-0">
    <thead><tr><th>Date</th><th>Voucher</th><th>Account</th><th>Narration</th><th class="text-end">Debit</th><th class="text-end">Credit</th><th class="text-end">Balance</th></tr></thead>
    <tbody>
    <?php $__empty_1 = true; $__currentLoopData = $lines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $line): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <tr><td><?php echo e($line['date']); ?></td><td><?php echo e($line['voucher_number']); ?></td><td><?php echo e($line['account']); ?></td><td><?php echo e($line['narration']); ?></td>
            <td class="text-end"><?php echo e(number_format($line['debit'], 2)); ?></td><td class="text-end"><?php echo e(number_format($line['credit'], 2)); ?></td>
            <td class="text-end"><?php echo e(number_format($line['balance'], 2)); ?></td></tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <tr><td colspan="7" class="text-center text-muted py-4">No posted entries in this period.</td></tr>
    <?php endif; ?>
    </tbody>
    <tfoot><tr><th colspan="4">Total</th><th class="text-end"><?php echo e(number_format($total_debit, 2)); ?></th><th class="text-end"><?php echo e(number_format($total_credit, 2)); ?></th><th class="text-end"><?php echo e(number_format($closing_balance, 2)); ?></th></tr></tfoot>
</table>
</div></div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\xampp\htdocs\manpower-erp-new\resources\views/reports/financial/cash-book.blade.php ENDPATH**/ ?>