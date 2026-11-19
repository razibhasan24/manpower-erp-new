<?php $__env->startSection('title', __('Create User')); ?>
<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-3"><h1 class="mb-0"><?php echo e(__('Create User')); ?></h1><a class="btn btn-outline-secondary" href="<?php echo e(route('users.index')); ?>"><?php echo e(__('Back to users')); ?></a></div>
<div class="alert alert-info"><?php echo e(__('The new user will be asked to change this initial password after signing in. You can assign one role and additional permissions available to your own account.')); ?></div>
<form method="POST" action="<?php echo e(route('users.store')); ?>">
    <?php echo csrf_field(); ?>
    <div class="card mb-3"><div class="card-header"><?php echo e(__('Account details')); ?></div><div class="card-body"><div class="row g-3">
        <div class="col-md-6"><label for="name" class="form-label"><?php echo e(__('Full name')); ?></label><input id="name" name="name" value="<?php echo e(old('name')); ?>" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required><?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
        <div class="col-md-6"><label for="email" class="form-label"><?php echo e(__('Email')); ?></label><input id="email" type="email" name="email" value="<?php echo e(old('email')); ?>" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
        <div class="col-md-6"><label for="phone" class="form-label"><?php echo e(__('Phone')); ?></label><input id="phone" name="phone" value="<?php echo e(old('phone')); ?>" class="form-control <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
        <div class="col-md-3"><label for="password" class="form-label"><?php echo e(__('Initial password')); ?></label><input id="password" type="password" name="password" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required><?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
        <div class="col-md-3"><label for="password_confirmation" class="form-label"><?php echo e(__('Confirm password')); ?></label><input id="password_confirmation" type="password" name="password_confirmation" class="form-control" required></div>
        <div class="col-md-4"><label for="status" class="form-label"><?php echo e(__('Status')); ?></label><select id="status" name="status" class="form-select" required><?php $__currentLoopData = ['active','inactive','suspended']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($status); ?>" <?php if(old('status', 'active') === $status): echo 'selected'; endif; ?>><?php echo e(__(ucfirst($status))); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select><?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
    </div></div></div>

    <div class="card mb-3"><div class="card-header"><?php echo e(__('Role and permissions')); ?></div><div class="card-body">
        <div class="mb-3"><label for="role_id" class="form-label"><?php echo e(__('Role')); ?></label><select id="role_id" name="role_id" class="form-select <?php $__errorArgs = ['role_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required><option value=""><?php echo e(__('Select a role')); ?></option><?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($role->id); ?>" <?php if((string) old('role_id') === (string) $role->id): echo 'selected'; endif; ?>><?php echo e($role->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select><?php $__errorArgs = ['role_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
        <label class="form-label"><?php echo e(__('Additional direct permissions')); ?></label>
        <p class="small text-muted"><?php echo e(__('The role grants its standard permissions. Select only extra permissions needed for this user.')); ?></p>
        <?php $__errorArgs = ['permission_ids'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small mb-2"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        <div class="row g-3"><?php $__currentLoopData = $permissions->groupBy(fn ($permission) => explode('.', $permission->name, 2)[0]); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $module => $modulePermissions): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><div class="col-lg-4 col-md-6"><div class="border rounded p-3 h-100"><strong><?php echo e(\Illuminate\Support\Str::headline($module)); ?></strong><div class="mt-2"><?php $__currentLoopData = $modulePermissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><div class="form-check"><input class="form-check-input" type="checkbox" id="permission_<?php echo e($permission->id); ?>" name="permission_ids[]" value="<?php echo e($permission->id); ?>" <?php if(in_array((string) $permission->id, array_map('strval', old('permission_ids', [])), true)): echo 'checked'; endif; ?>><label class="form-check-label" for="permission_<?php echo e($permission->id); ?>"><?php echo e(\Illuminate\Support\Str::headline(substr($permission->name, strlen($module) + 1))); ?></label></div><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></div></div></div><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></div>
    </div></div>

    <div class="card mb-3"><div class="card-header"><?php echo e(__('Branch and country access')); ?></div><div class="card-body"><p class="small text-muted"><?php echo e(__('The primary branch is automatically included in the user’s allowed branch assignments.')); ?></p><div class="row g-4">
        <div class="col-md-4"><label for="branch_id" class="form-label"><?php echo e(__('Primary branch')); ?></label><select id="branch_id" name="branch_id" class="form-select"><option value=""><?php echo e(__('No primary branch')); ?></option><?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($branch->id); ?>" <?php if((string) old('branch_id') === (string) $branch->id): echo 'selected'; endif; ?>><?php echo e($branch->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select><?php $__errorArgs = ['branch_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
        <div class="col-md-4"><label class="form-label"><?php echo e(__('Additional branch access')); ?></label><?php $__empty_1 = true; $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><div class="form-check"><input class="form-check-input" type="checkbox" name="branch_ids[]" id="branch_<?php echo e($branch->id); ?>" value="<?php echo e($branch->id); ?>" <?php if(in_array((string) $branch->id, array_map('strval', old('branch_ids', [])), true)): echo 'checked'; endif; ?>><label class="form-check-label" for="branch_<?php echo e($branch->id); ?>"><?php echo e($branch->name); ?></label></div><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><span class="text-muted"><?php echo e(__('No branches available.')); ?></span><?php endif; ?> <?php $__errorArgs = ['branch_ids.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
        <div class="col-md-4"><label class="form-label"><?php echo e(__('Country access')); ?></label><?php $__empty_1 = true; $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><div class="form-check"><input class="form-check-input" type="checkbox" name="country_ids[]" id="country_<?php echo e($country->id); ?>" value="<?php echo e($country->id); ?>" <?php if(in_array((string) $country->id, array_map('strval', old('country_ids', [])), true)): echo 'checked'; endif; ?>><label class="form-check-label" for="country_<?php echo e($country->id); ?>"><?php echo e($country->name); ?></label></div><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><span class="text-muted"><?php echo e(__('No countries available.')); ?></span><?php endif; ?> <?php $__errorArgs = ['country_ids.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
    </div></div></div>

    <div class="d-flex gap-2"><button class="btn btn-primary" type="submit"><?php echo e(__('Create user')); ?></button><a class="btn btn-outline-secondary" href="<?php echo e(route('users.index')); ?>"><?php echo e(__('Cancel')); ?></a></div>
</form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\xampp\htdocs\manpower-erp-new\resources\views/access-administration/create-user.blade.php ENDPATH**/ ?>