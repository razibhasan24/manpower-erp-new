<?php $__env->startSection('title', __('Training Attendance')); ?>
<?php $__env->startSection('content'); ?>
    <div class="d-flex justify-content-between align-items-center mb-3"><h1><?php echo e(__('Training Attendance')); ?></h1><?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('attendance.create')): ?><a class="btn btn-success" href="<?php echo e(route('training-attendance.create')); ?>"><?php echo e(__('Add Attendance')); ?></a><?php endif; ?></div>
    <?php if(session('success')): ?><div class="alert alert-success"><?php echo e(session('success')); ?></div><?php endif; ?>
    <div class="table-responsive"><table class="table table-striped align-middle"><thead><tr><th><?php echo e(__('Date')); ?></th><th><?php echo e(__('Tracking')); ?></th><th><?php echo e(__('Candidate')); ?></th><th><?php echo e(__('Course')); ?></th><th><?php echo e(__('Status')); ?></th><th><?php echo e(__('Remarks')); ?></th><th><?php echo e(__('Actions')); ?></th></tr></thead><tbody>
        <?php $__empty_1 = true; $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr><td><?php echo e($record->attendance_date?->format('Y-m-d')); ?></td><td><?php echo e($record->training?->recruitment?->tracking_number ?? '—'); ?></td><td><?php echo e($record->training?->recruitment?->passportHolder?->full_name ?? '—'); ?></td><td><?php echo e($record->training?->course?->title ?? '—'); ?></td><td><?php if (isset($component)) { $__componentOriginal2ddbc40e602c342e508ac696e52f8719 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2ddbc40e602c342e508ac696e52f8719 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.badge','data' => ['status' => $record->status]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($record->status)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2ddbc40e602c342e508ac696e52f8719)): ?>
<?php $attributes = $__attributesOriginal2ddbc40e602c342e508ac696e52f8719; ?>
<?php unset($__attributesOriginal2ddbc40e602c342e508ac696e52f8719); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2ddbc40e602c342e508ac696e52f8719)): ?>
<?php $component = $__componentOriginal2ddbc40e602c342e508ac696e52f8719; ?>
<?php unset($__componentOriginal2ddbc40e602c342e508ac696e52f8719); ?>
<?php endif; ?></td><td><?php echo e($record->remarks ?: '—'); ?></td><td class="text-nowrap"><?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('attendance.update')): ?><a class="btn btn-sm btn-warning" href="<?php echo e(route('training-attendance.edit', $record)); ?>"><?php echo e(__('Edit')); ?></a><?php endif; ?> <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('attendance.delete')): ?><form class="d-inline" method="POST" action="<?php echo e(route('training-attendance.destroy', $record)); ?>" onsubmit="return confirm('<?php echo e(__('Delete this attendance record?')); ?>')"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?><button class="btn btn-sm btn-danger"><?php echo e(__('Delete')); ?></button></form><?php endif; ?></td></tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><tr><td colspan="7" class="text-center text-muted"><?php echo e(__('No attendance records found.')); ?></td></tr><?php endif; ?>
    </tbody></table></div><?php echo e($records->links()); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\xampp\htdocs\manpower-erp-new\resources\views/training-attendance/index.blade.php ENDPATH**/ ?>