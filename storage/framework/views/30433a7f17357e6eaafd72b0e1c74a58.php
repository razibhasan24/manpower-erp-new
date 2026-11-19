<?php $__env->startSection('title', __('Country User Permissions')); ?>
<?php $__env->startSection('content'); ?>
<h1 class="mb-3"><?php echo e(__('Country User Permissions')); ?></h1>
<div class="alert alert-info"><?php echo e(__('This page shows the country access currently assigned to each user. Assignments are managed through user administration.')); ?></div>
<form method="GET" class="row g-2 mb-3">
    <div class="col-md-5"><input name="search" value="<?php echo e(request('search')); ?>" class="form-control" placeholder="<?php echo e(__('Search user name or email')); ?>"></div>
    <div class="col-md-4"><select name="country_id" class="form-select"><option value=""><?php echo e(__('All countries')); ?></option><?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($country->id); ?>" <?php if((string) request('country_id') === (string) $country->id): echo 'selected'; endif; ?>><?php echo e($country->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
    <div class="col-auto"><button class="btn btn-primary"><?php echo e(__('Filter')); ?></button></div>
    <div class="col-auto"><a class="btn btn-outline-secondary" href="<?php echo e(route('country-user-permissions.index')); ?>"><?php echo e(__('Clear')); ?></a></div>
</form>
<div class="table-responsive"><table class="table table-striped align-middle"><thead><tr><th><?php echo e(__('User')); ?></th><th><?php echo e(__('Email')); ?></th><th><?php echo e(__('Branch')); ?></th><th><?php echo e(__('Roles')); ?></th><th><?php echo e(__('Assigned countries')); ?></th><th><?php echo e(__('Status')); ?></th></tr></thead><tbody>
<?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><tr><td><?php echo e($user->name); ?></td><td><?php echo e($user->email); ?></td><td><?php echo e($user->branch?->name ?? '—'); ?></td><td><?php echo e($user->roles->pluck('name')->map(fn ($role) => __(\Illuminate\Support\Str::headline($role)))->join(', ') ?: '—'); ?></td><td><?php echo e($user->countries->pluck('name')->join(', ') ?: __('No countries assigned')); ?></td><td><?php if (isset($component)) { $__componentOriginal2ddbc40e602c342e508ac696e52f8719 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2ddbc40e602c342e508ac696e52f8719 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.badge','data' => ['status' => $user->status ?? 'active']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($user->status ?? 'active')]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2ddbc40e602c342e508ac696e52f8719)): ?>
<?php $attributes = $__attributesOriginal2ddbc40e602c342e508ac696e52f8719; ?>
<?php unset($__attributesOriginal2ddbc40e602c342e508ac696e52f8719); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2ddbc40e602c342e508ac696e52f8719)): ?>
<?php $component = $__componentOriginal2ddbc40e602c342e508ac696e52f8719; ?>
<?php unset($__componentOriginal2ddbc40e602c342e508ac696e52f8719); ?>
<?php endif; ?></td></tr><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><tr><td colspan="6" class="text-center text-muted"><?php echo e(__('No users found.')); ?></td></tr><?php endif; ?>
</tbody></table></div><?php echo e($users->links()); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\xampp\htdocs\manpower-erp-new\resources\views/country-administration/user-permissions.blade.php ENDPATH**/ ?>