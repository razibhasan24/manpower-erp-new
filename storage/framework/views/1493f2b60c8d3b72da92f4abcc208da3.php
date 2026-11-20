<?php $__env->startSection('title', __('Users')); ?>
<?php $__env->startSection('content'); ?>
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3"><h1 class="mb-0"><?php echo e(__('User Management')); ?></h1><?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('user.create')): ?><?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('role.manage')): ?><a href="<?php echo e(route('users.create')); ?>" class="btn btn-primary"><?php echo e(__('Create user')); ?></a><?php endif; ?> <?php endif; ?></div>
<div class="alert alert-info"><?php echo e(__('Use Edit beside a user to change their role, direct permissions, and access assignments.')); ?></div>
<?php if(session('success')): ?><div class="alert alert-success"><?php echo e(session('success')); ?></div><?php endif; ?>
<form method="GET" class="row g-2 mb-3"><div class="col-md-5"><input name="search" value="<?php echo e(request('search')); ?>" class="form-control" placeholder="<?php echo e(__('Search name or email')); ?>"></div><div class="col-md-3"><select name="status" class="form-select"><option value=""><?php echo e(__('All statuses')); ?></option><?php $__currentLoopData = ['active','inactive','suspended']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($status); ?>" <?php if(request('status') === $status): echo 'selected'; endif; ?>><?php echo e(__(ucfirst($status))); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div><div class="col-auto"><button class="btn btn-primary"><?php echo e(__('Filter')); ?></button></div><div class="col-auto"><a class="btn btn-outline-secondary" href="<?php echo e(route('users.index')); ?>"><?php echo e(__('Clear')); ?></a></div></form>
<div class="table-responsive"><table class="table table-striped align-middle"><thead><tr><th><?php echo e(__('Name')); ?></th><th><?php echo e(__('Email')); ?></th><th><?php echo e(__('Roles')); ?></th><th><?php echo e(__('Branch')); ?></th><th><?php echo e(__('Additional branches')); ?></th><th><?php echo e(__('Countries')); ?></th><th><?php echo e(__('Status')); ?></th><th><?php echo e(__('Last login')); ?></th></tr></thead><tbody><?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><tr><td><?php echo e($user->name); ?> <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('user.edit')): ?><?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('role.manage')): ?><?php if (! ($user->hasRole('Super Admin'))): ?><a href="<?php echo e(route('users.edit', $user)); ?>" class="btn btn-sm btn-outline-primary ms-2"><?php echo e(__('Edit')); ?></a><?php endif; ?> <?php endif; ?> <?php endif; ?></td><td><?php echo e($user->email); ?></td><td><?php echo e($user->roles->pluck('name')->join(', ') ?: '—'); ?></td><td><?php echo e($user->branch?->name ?? '—'); ?></td><td><?php echo e($user->branches->pluck('name')->join(', ') ?: '—'); ?></td><td><?php echo e($user->countries->pluck('name')->join(', ') ?: '—'); ?></td><td><?php if (isset($component)) { $__componentOriginal2ddbc40e602c342e508ac696e52f8719 = $component; } ?>
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
<?php endif; ?></td><td><?php echo e($user->last_login_at?->format('Y-m-d H:i') ?? '—'); ?></td></tr><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><tr><td colspan="8" class="text-center text-muted"><?php echo e(__('No users found.')); ?></td></tr><?php endif; ?></tbody></table></div><?php echo e($users->links()); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\xampp\htdocs\manpower-erp-new\resources\views/access-administration/users.blade.php ENDPATH**/ ?>