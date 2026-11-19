<?php $__env->startSection('title', __('Login History')); ?>
<?php $__env->startSection('content'); ?>
<h1 class="mb-3"><?php echo e(__('Login History')); ?></h1>
<form method="GET" class="row g-2 mb-3"><div class="col-md-3"><input name="search" value="<?php echo e(request('search')); ?>" class="form-control" placeholder="<?php echo e(__('User or IP address')); ?>"></div><div class="col-md-2"><select name="status" class="form-select"><option value=""><?php echo e(__('All statuses')); ?></option><?php $__currentLoopData = ['success','failed','locked']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($status); ?>" <?php if(request('status') === $status): echo 'selected'; endif; ?>><?php echo e(__(ucfirst($status))); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div><div class="col-md-2"><input type="date" name="from" value="<?php echo e(request('from')); ?>" class="form-control"></div><div class="col-md-2"><input type="date" name="to" value="<?php echo e(request('to')); ?>" class="form-control"></div><div class="col-auto"><button class="btn btn-primary"><?php echo e(__('Filter')); ?></button></div><div class="col-auto"><a class="btn btn-outline-secondary" href="<?php echo e(route('login-history.index')); ?>"><?php echo e(__('Clear')); ?></a></div></form>
<div class="table-responsive"><table class="table table-striped align-middle"><thead><tr><th><?php echo e(__('Login time')); ?></th><th><?php echo e(__('User')); ?></th><th><?php echo e(__('Status')); ?></th><th><?php echo e(__('IP address')); ?></th><th><?php echo e(__('Device / browser')); ?></th><th><?php echo e(__('Reason')); ?></th></tr></thead><tbody><?php $__empty_1 = true; $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><tr><td><?php echo e($record->login_at?->format('Y-m-d H:i:s') ?? '—'); ?></td><td><?php echo e($record->user?->name ?? '—'); ?><div class="small text-muted"><?php echo e($record->user?->email); ?></div></td><td><?php if (isset($component)) { $__componentOriginal2ddbc40e602c342e508ac696e52f8719 = $component; } ?>
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
<?php endif; ?></td><td><?php echo e($record->ip_address ?? '—'); ?></td><td><?php echo e(\Illuminate\Support\Str::limit($record->user_agent, 90) ?: '—'); ?></td><td><?php echo e($record->failed_reason ?: '—'); ?></td></tr><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><tr><td colspan="6" class="text-center text-muted"><?php echo e(__('No login history found.')); ?></td></tr><?php endif; ?></tbody></table></div><?php echo e($records->links()); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\xampp\htdocs\manpower-erp-new\resources\views/access-administration/login-history.blade.php ENDPATH**/ ?>