<?php $__env->startSection('title', __('Backups')); ?>

<?php $__env->startSection('content'); ?>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0"><?php echo e(__('Backups')); ?></h2>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('backup.create')): ?>
                <form method="POST" action="<?php echo e(route('backups.store')); ?>" class="d-flex gap-2">
                    <?php echo csrf_field(); ?>
                    <select name="type" class="form-select form-select-sm" style="width:auto">
                        <option value="full"><?php echo e(__('Full backup (database + files)')); ?></option>
                        <option value="db"><?php echo e(__('Database only')); ?></option>
                        <option value="files"><?php echo e(__('Files only')); ?></option>
                    </select>
                    <button type="submit" class="btn btn-primary btn-sm"><?php echo e(__('Create Backup')); ?></button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <div class="container-fluid py-4">
        <?php if(session('success')): ?>
            <div class="alert alert-success"><?php echo e(session('success')); ?></div>
        <?php endif; ?>

        <div class="alert alert-info">
            <?php echo e(__('Backups run automatically every day at 02:00 (Asia/Dhaka). Files are stored privately and integrity-checked after creation. Restore requires extra confirmation and is audit-logged.')); ?>

        </div>

        <div class="card">
            <div class="card-header"><?php echo e(__('Backup History')); ?></div>
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th><?php echo e(__('Filename')); ?></th>
                            <th><?php echo e(__('Type')); ?></th>
                            <th><?php echo e(__('Size')); ?></th>
                            <th><?php echo e(__('Status')); ?></th>
                            <th><?php echo e(__('Verified')); ?></th>
                            <th><?php echo e(__('Created By')); ?></th>
                            <th><?php echo e(__('Created At')); ?></th>
                            <th><?php echo e(__('Actions')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $backups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $backup): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($backup->filename); ?></td>
                                <td><span class="badge bg-secondary"><?php echo e($backup->type); ?></span></td>
                                <td><?php $s = (float) $backup->size; $u = ['B','KB','MB','GB']; $i = 0; while ($s >= 1024 && $i < 3) { $s /= 1024; $i++; } echo round($s, 2).' '.$u[$i]; ?></td>
                                <td>
                                    <span class="badge bg-<?php echo e($backup->status === 'completed' ? 'success' : 'danger'); ?>"><?php echo e($backup->status); ?></span>
                                </td>
                                <td><?php echo e($backup->verified_at ? $backup->verified_at->format('d M Y, h:i A') : '—'); ?></td>
                                <td><?php echo e($backup->creator?->name ?? 'System'); ?></td>
                                <td><?php echo e($backup->created_at->format('d M Y, h:i A')); ?></td>
                                <td class="d-flex gap-1">
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('backup.download')): ?>
                                        <a href="<?php echo e(route('backups.download', $backup)); ?>" class="btn btn-outline-primary btn-sm"><?php echo e(__('Download')); ?></a>
                                    <?php endif; ?>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('backup.restore')): ?>
                                        <a href="<?php echo e(route('backups.restore-form', $backup)); ?>" class="btn btn-outline-warning btn-sm"><?php echo e(__('Restore')); ?></a>
                                    <?php endif; ?>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('backup.delete')): ?>
                                        <form method="POST" action="<?php echo e(route('backups.destroy', $backup)); ?>" onsubmit="return confirm('<?php echo e(__('Delete this backup permanently?')); ?>')">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-outline-danger btn-sm"><?php echo e(__('Delete')); ?></button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="8" class="text-center text-muted py-4"><?php echo e(__('No backups yet.')); ?></td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="card-footer"><?php echo e($backups->links()); ?></div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\xampp\htdocs\manpower-erp-new\resources\views/backups/index.blade.php ENDPATH**/ ?>