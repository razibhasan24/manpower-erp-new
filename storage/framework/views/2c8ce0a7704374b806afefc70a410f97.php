<?php $__env->startSection('title', 'Create Vendor'); ?>
<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Create Vendor</h1>
    <a href="<?php echo e(route('reports.financial.sub-ledger.due-list', ['type' => 'vendor'])); ?>" class="btn btn-outline-secondary">Back to Vendor Due List</a>
</div>
<?php if($errors->any()): ?>
    <div class="alert alert-danger"><ul class="mb-0"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($error); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul></div>
<?php endif; ?>
<div class="card"><div class="card-body">
    <form method="POST" action="<?php echo e(route('reports.financial.sub-ledger.vendor.store')); ?>">
        <?php echo csrf_field(); ?>
        <div class="mb-3"><label for="name" class="form-label">Vendor Name *</label><input id="name" name="name" value="<?php echo e(old('name')); ?>" class="form-control" required maxlength="255"></div>
        <div class="mb-3"><label for="phone" class="form-label">Phone</label><input id="phone" name="phone" value="<?php echo e(old('phone')); ?>" class="form-control" maxlength="30"></div>
        <div class="mb-3"><label for="email" class="form-label">Email</label><input id="email" name="email" type="email" value="<?php echo e(old('email')); ?>" class="form-control"></div>
        <div class="mb-3"><label for="branch_id" class="form-label">Branch</label><select id="branch_id" name="branch_id" class="form-select"><option value="">Select branch</option><?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($branch->id); ?>" <?php if(old('branch_id', auth()->user()->branch_id)==$branch->id): echo 'selected'; endif; ?>><?php echo e($branch->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
        <div class="mb-3"><label for="address" class="form-label">Address</label><textarea id="address" name="address" rows="3" class="form-control"><?php echo e(old('address')); ?></textarea></div>
        <button type="submit" class="btn btn-primary">Create Vendor & Payable Account</button>
    </form>
</div></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\xampp\htdocs\manpower-erp-new\resources\views/reports/sub-ledgers/vendors/create.blade.php ENDPATH**/ ?>