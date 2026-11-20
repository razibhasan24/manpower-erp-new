<?php $__env->startSection('title', 'Sub-Ledger'); ?>
<?php $__env->startSection('content'); ?>
<h1 class="mb-3"><?php echo e(ucwords(str_replace('_', ' ', $type))); ?> Ledger — #<?php echo e($id); ?></h1>
<form method="GET" class="row g-2 mb-3">
    <div class="col-md-2"><input type="date" name="from" value="<?php echo e($filters['from'] ?? ''); ?>" class="form-control"></div>
    <div class="col-md-2"><input type="date" name="to" value="<?php echo e($filters['to'] ?? ''); ?>" class="form-control"></div>
    <div class="col-auto"><button class="btn btn-secondary" type="submit">Filter</button></div>
</form>
<div class="card mb-3"><div class="card-body d-flex justify-content-between">
    <span>Opening: <strong><?php echo e(number_format($ledger['opening_balance'], 2)); ?></strong></span>
    <span>Debit: <strong><?php echo e(number_format($ledger['total_debit'], 2)); ?></strong></span>
    <span>Credit: <strong><?php echo e(number_format($ledger['total_credit'], 2)); ?></strong></span>
    <span>Closing: <strong><?php echo e(number_format($ledger['closing_balance'], 2)); ?></strong></span>
</div></div>
<div class="card"><div class="table-responsive">
<table class="table mb-0">
    <thead><tr><th>Date</th><th>Voucher</th><th>Account</th><th>Narration</th><th class="text-end">Debit</th><th class="text-end">Credit</th><th class="text-end">Balance</th></tr></thead>
    <tbody>
    <?php $__empty_1 = true; $__currentLoopData = $ledger['lines']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $line): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <tr><td><?php echo e($line['date']); ?></td><td><?php echo e($line['voucher_number']); ?></td><td><?php echo e($line['account']); ?></td><td><?php echo e($line['narration']); ?></td>
            <td class="text-end"><?php echo e(number_format($line['debit'], 2)); ?></td><td class="text-end"><?php echo e(number_format($line['credit'], 2)); ?></td>
            <td class="text-end"><?php echo e(number_format($line['balance'], 2)); ?></td></tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <tr><td colspan="7" class="text-center text-muted py-4">No ledger entries in this period.</td></tr>
    <?php endif; ?>
    </tbody>
</table>
</div></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\xampp\htdocs\manpower-erp-new\resources\views/reports/sub-ledgers/show.blade.php ENDPATH**/ ?>