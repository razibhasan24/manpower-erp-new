<?php $__env->startSection('title', __('Profile') . ' — ' . \App\Services\BrandingService::appName()); ?>

<?php $__env->startSection('breadcrumbs'); ?>
    <li class="breadcrumb-item active" aria-current="page"><?php echo e(__('Profile')); ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <h1 class="h4 mb-3"><?php echo e(__('My Profile')); ?></h1>

    <?php if($user->must_change_password): ?>
        <div class="alert alert-warning" role="alert"><?php echo e(__('Please change your password — it was set by an administrator or generated during setup.')); ?></div>
    <?php endif; ?>

    <div class="row g-3">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header"><?php echo e(__('Profile information')); ?></div>
                <div class="card-body">
                    <form method="POST" action="<?php echo e(route('profile.update')); ?>">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        <div class="mb-3">
                            <label for="name" class="form-label"><?php echo e(__('Name')); ?></label>
                            <input type="text" name="name" id="name" class="form-control" value="<?php echo e(old('name', $user->name)); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label"><?php echo e(__('Email address')); ?></label>
                            <input type="email" name="email" id="email" class="form-control" value="<?php echo e(old('email', $user->email)); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label"><?php echo e(__('Phone')); ?></label>
                            <input type="text" name="phone" id="phone" class="form-control" value="<?php echo e(old('phone', $user->phone)); ?>">
                        </div>
                        <p class="text-muted small"><?php echo e(__('Roles')); ?>: <?php echo e($user->getRoleNames()->implode(', ') ?: __('None')); ?></p>
                        <button type="submit" class="btn btn-primary"><?php echo e(__('Save profile')); ?></button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card" id="password">
                <div class="card-header"><?php echo e(__('Change password')); ?></div>
                <div class="card-body">
                    <form method="POST" action="<?php echo e(route('profile.password')); ?>">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        <div class="mb-3">
                            <label for="current_password" class="form-label"><?php echo e(__('Current password')); ?></label>
                            <input type="password" name="current_password" id="current_password" class="form-control" required autocomplete="current-password">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label"><?php echo e(__('New password')); ?></label>
                            <input type="password" name="password" id="password" class="form-control" required autocomplete="new-password">
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label"><?php echo e(__('Confirm new password')); ?></label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required autocomplete="new-password">
                        </div>
                        <button type="submit" class="btn btn-primary"><?php echo e(__('Change password')); ?></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\xampp\htdocs\manpower-erp-new\resources\views/profile/edit.blade.php ENDPATH**/ ?>