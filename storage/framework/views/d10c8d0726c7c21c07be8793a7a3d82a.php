<?php $__env->startSection('title', 'Voucher ' . $item->voucher_number); ?>
<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-3"><h1><?php echo e($item->voucher_number); ?></h1><?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('viewAny', \App\Models\Voucher::class)): ?><a href="<?php echo e(route('accounting.vouchers.index')); ?>" class="btn btn-secondary">Back to list</a><?php endif; ?></div>
<?php if(session('success')): ?><div class="alert alert-success"><?php echo e(session('success')); ?></div><?php endif; ?>
<div class="card mb-3"><div class="card-body"><table class="table table-bordered mb-0">
    <tr><th style="width:200px">Voucher Number</th><td><?php echo e($item->voucher_number); ?></td></tr>
    <tr><th>Type</th><td><?php echo e(ucfirst($item->voucher_type)); ?></td></tr>
    <tr><th>Date</th><td><?php echo e($item->voucher_date); ?></td></tr>
    <tr><th>Branch</th><td><?php echo e($item->branch?->name ?? '—'); ?></td></tr>
    <?php if($item->paymentMode): ?><tr><th>Payment Mode</th><td><?php echo e($item->paymentMode->name); ?></td></tr><?php endif; ?>
    <tr><th>Status</th><td><?php if (isset($component)) { $__componentOriginal2ddbc40e602c342e508ac696e52f8719 = $component; } ?>
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
    <?php if($item->status === 'draft'): ?><tr><th>Approval</th><td class="text-warning">Pending approval. This voucher has not affected account balances.</td></tr><?php endif; ?>
    <tr><th>Total Debit</th><td><?php echo e(number_format($item->total_debit, 2)); ?></td></tr>
    <tr><th>Total Credit</th><td><?php echo e(number_format($item->total_credit, 2)); ?></td></tr>
    <tr><th>Narration</th><td><?php echo e($item->narration); ?></td></tr>
    <?php if($item->payee): ?><tr><th>Payee / Received From</th><td><?php echo e($item->payee); ?></td></tr><?php endif; ?>
    <?php if($item->attachment_path): ?><tr><th>Attachment</th><td><a href="<?php echo e(route('accounting.vouchers.attachment', $item)); ?>"><?php echo e($item->attachment_original_name ?? 'Download attachment'); ?></a></td></tr><?php endif; ?>
    <?php if($item->reversal_voucher_id): ?><tr><th>Reversed By</th><td><a href="<?php echo e(route('accounting.vouchers.show', $item->reversal_voucher_id)); ?>">View reversal voucher</a></td></tr><?php endif; ?>
</table></div></div>
<div class="card mb-3"><div class="card-header"><?php echo e($item->status === 'draft' ? 'Draft Entries' : 'Journal Lines'); ?></div><div class="table-responsive"><table class="table mb-0">
    <thead><tr><th>Account</th><th>Narration</th><th class="text-end">Debit</th><th class="text-end">Credit</th></tr></thead><tbody>
    <?php if(!$item->journalEntry): ?>
        <?php $__currentLoopData = $item->draft_lines ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $line): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php ($account = $draftAccounts[$line['account_id'] ?? 0] ?? null); ?>
            <tr><td><?php echo e($account?->code); ?> - <?php echo e($account?->name); ?></td><td><?php echo e($line['narration'] ?? ''); ?></td><td class="text-end"><?php echo e(number_format((float)($line['debit'] ?? 0), 2)); ?></td><td class="text-end"><?php echo e(number_format((float)($line['credit'] ?? 0), 2)); ?></td></tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php else: ?>
        <?php $__currentLoopData = $item->journalEntry->lines ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $line): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr><td><?php echo e($line->account->code ?? ''); ?> - <?php echo e($line->account->name ?? ''); ?></td><td><?php echo e($line->narration); ?></td><td class="text-end"><?php echo e(number_format($line->debit, 2)); ?></td><td class="text-end"><?php echo e(number_format($line->credit, 2)); ?></td></tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>
    </tbody></table></div></div>
<?php if($item->status === 'draft'): ?>
<div class="d-flex flex-wrap gap-2 mb-3">
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $item)): ?><a href="<?php echo e(route('accounting.vouchers.edit', $item)); ?>" class="btn btn-outline-primary">Edit Draft</a><?php endif; ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('approve', $item)): ?><form method="POST" action="<?php echo e(route('accounting.vouchers.approve', $item)); ?>" onsubmit="return confirm('Approve and post this voucher to accounts?')"><?php echo csrf_field(); ?><button class="btn btn-success" type="submit">Approve &amp; Post</button></form><?php endif; ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('reject', $item)): ?><form method="POST" action="<?php echo e(route('accounting.vouchers.reject', $item)); ?>" onsubmit="return confirm('Reject this draft voucher?')"><?php echo csrf_field(); ?><button class="btn btn-outline-danger" type="submit">Reject Draft</button></form><?php endif; ?>
</div>
<?php endif; ?>
<?php if($item->status === 'posted'): ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('reverse', $item)): ?><div class="card"><div class="card-header">Reverse this voucher</div><div class="card-body"><p class="text-muted">Posted vouchers cannot be edited or deleted. A reversal creates a mirror-image voucher and marks this one reversed.</p><form method="POST" action="<?php echo e(route('accounting.vouchers.reverse', $item)); ?>" onsubmit="return confirm('Reverse voucher <?php echo e($item->voucher_number); ?>? This cannot be undone.')"><?php echo csrf_field(); ?><div class="mb-3"><label class="form-label">Reason (required)</label><input type="text" name="reason" class="form-control" required maxlength="500"></div><button type="submit" class="btn btn-danger">Reverse Voucher</button></form></div></div><?php endif; ?>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\xampp\htdocs\manpower-erp-new\resources\views/accounting/vouchers/show.blade.php ENDPATH**/ ?>