<?php $__env->startSection('title', $title); ?>
<?php $__env->startSection('content'); ?>
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <h1 class="mb-0"><?php echo e($title); ?></h1>
    <div class="d-flex gap-2">
        <?php if($moduleKey === 'invoice-settings'): ?>
            <a class="btn btn-outline-secondary" href="<?php echo e(route('company-profile.edit')); ?>"><?php echo e(__('Company Profile')); ?></a>
        <?php endif; ?>
        <?php if(in_array($moduleKey, ['invoice-settings', 'notification-settings'])): ?>
            <a class="btn btn-primary" href="<?php echo e(route('settings.index')); ?>"><?php echo e(__('General Settings')); ?></a>
        <?php endif; ?>
    </div>
</div>

<?php if(!empty($details['notice'])): ?>
    <div class="alert alert-info"><?php echo e($details['notice']); ?></div>
<?php endif; ?>

<?php if(isset($details['items'])): ?>
    <div class="card"><div class="table-responsive"><table class="table table-striped align-middle mb-0">
        <thead><tr><th><?php echo e(__('Setting')); ?></th><th><?php echo e(__('Current value')); ?></th></tr></thead>
        <tbody><?php $__currentLoopData = $details['items']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><tr><th scope="row"><?php echo e($label); ?></th><td><?php echo e($value ?? '—'); ?></td></tr><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></tbody>
    </table></div></div>
<?php endif; ?>

<?php if(isset($details['stages'])): ?>
    <div class="table-responsive"><table class="table table-striped align-middle">
        <thead><tr><th><?php echo e(__('Order')); ?></th><th><?php echo e(__('Workflow stage')); ?></th><th><?php echo e(__('Available statuses')); ?></th></tr></thead>
        <tbody><?php $__currentLoopData = $details['stages']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $stage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><tr><td><?php echo e($index + 1); ?></td><td><?php echo e(__(\Illuminate\Support\Str::headline($stage))); ?></td><td><?php echo e(collect($details['stageStatuses'][$stage] ?? [])->map(fn ($status) => __(\Illuminate\Support\Str::headline($status)))->join(', ')); ?></td></tr><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></tbody>
    </table></div>
<?php endif; ?>

<?php if(array_key_exists('notifications', $details)): ?>
    <div class="card"><div class="list-group list-group-flush">
        <?php $__empty_1 = true; $__currentLoopData = $details['notifications']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <a href="<?php echo e($notification['url']); ?>" class="list-group-item list-group-item-action d-flex align-items-center gap-3">
                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => $notification['icon'],'size' => 18]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($notification['icon']),'size' => 18]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $attributes = $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $component = $__componentOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
                <span><?php echo e($notification['label']); ?></span>
                <span class="badge text-bg-<?php echo e($notification['tone']); ?> ms-auto"><?php echo e(__(ucfirst($notification['tone']))); ?></span>
            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="list-group-item text-muted"><?php echo e(__('There are no current notifications for this account.')); ?></div>
        <?php endif; ?>
    </div></div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\xampp\htdocs\manpower-erp-new\resources\views/configuration-modules/show.blade.php ENDPATH**/ ?>