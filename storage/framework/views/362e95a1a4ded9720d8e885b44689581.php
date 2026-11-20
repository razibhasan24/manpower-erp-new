<?php $__env->startSection('title', 'Due List'); ?>
<?php $__env->startSection('content'); ?>
<h1 class="mb-3"><?php echo e(ucwords(str_replace('_', ' ', $type))); ?> Due List</h1>
<div class="card"><div class="table-responsive">
<table class="table table-striped mb-0">
    <thead><tr><th>Party ID</th><th class="text-end">Total Debit</th><th class="text-end">Total Credit</th><th class="text-end">Balance Due</th><th class="text-end">Actions</th></tr></thead>
    <tbody>
    <?php $__empty_1 = true; $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <tr><td><?php echo e($partyNames[$row->party_id] ?? ('#'.$row->party_id)); ?></td><td class="text-end"><?php echo e(number_format($row->total_debit, 2)); ?></td>
            <td class="text-end"><?php echo e(number_format($row->total_credit, 2)); ?></td>
            <td class="text-end"><?php echo e(number_format($row->balance, 2)); ?></td>
            <td class="text-end">
                <a href="<?php echo e(route('reports.financial.sub-ledger', ['type' => $type, 'id' => $row->party_id])); ?>" class="btn btn-sm btn-outline-primary">Ledger</a>
                <?php if($type === 'passport_holder'): ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', \App\Models\Voucher::class)): ?>
                        <?php if($row->balance > 0): ?>
                            <a href="<?php echo e(route('reports.financial.sub-ledger.transaction.create', ['type' => $type, 'id' => $row->party_id, 'action' => 'receive'])); ?>" class="btn btn-sm btn-success">Receive</a>
                        <?php elseif($row->balance < 0): ?>
                            <a href="<?php echo e(route('reports.financial.sub-ledger.transaction.create', ['type' => $type, 'id' => $row->party_id, 'action' => 'pay'])); ?>" class="btn btn-sm btn-outline-danger">Pay</a>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endif; ?>
            </td></tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <tr><td colspan="5" class="text-center text-muted py-4">No outstanding dues.</td></tr>
    <?php endif; ?>
    </tbody>
</table>
</div></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\xampp\htdocs\manpower-erp-new\resources\views/reports/sub-ledgers/due-list.blade.php ENDPATH**/ ?>