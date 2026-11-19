<?php $__env->startSection('title', __('Settings') . ' — ' . \App\Services\BrandingService::appName()); ?>

<?php $__env->startSection('breadcrumbs'); ?>
    <li class="breadcrumb-item active" aria-current="page"><?php echo e(__('Settings')); ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0"><?php echo e(__('Settings')); ?></h1>
        <a class="btn btn-outline-secondary" href="<?php echo e(route('company-profile.edit')); ?>"><?php echo e(__('Company Profile')); ?></a>
    </div>

    <form method="POST" action="<?php echo e(route('settings.update')); ?>">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        <div class="card">
            <div class="card-body">
                <?php
                    $flat = $settings->flatten(1)->keyBy('key');
                    $val = fn ($key, $default = '') => old($key, $flat->get($key)?->value ?? $default);
                ?>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="software_name" class="form-label"><?php echo e(__('Software display name')); ?></label>
                        <input type="text" name="software_name" id="software_name" class="form-control" value="<?php echo e($val('software_name')); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="software_short_name" class="form-label"><?php echo e(__('Short name')); ?></label>
                        <input type="text" name="software_short_name" id="software_short_name" class="form-control" value="<?php echo e($val('software_short_name')); ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label for="currency" class="form-label"><?php echo e(__('Currency')); ?></label>
                        <input type="text" name="currency" id="currency" class="form-control" value="<?php echo e($val('currency', 'BDT')); ?>" maxlength="3" required>
                    </div>
                    <div class="col-md-4">
                        <label for="timezone" class="form-label"><?php echo e(__('Timezone')); ?></label>
                        <input type="text" name="timezone" id="timezone" class="form-control" value="<?php echo e($val('timezone', 'Asia/Dhaka')); ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label for="language" class="form-label"><?php echo e(__('Language')); ?></label>
                        <select name="language" id="language" class="form-select">
                            <option value="en" <?php echo e($val('language', 'en') === 'en' ? 'selected' : ''); ?>>English</option>
                            <option value="bn" <?php echo e($val('language') === 'bn' ? 'selected' : ''); ?>>Bangla</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="date_format" class="form-label"><?php echo e(__('Date format')); ?></label>
                        <input type="text" name="date_format" id="date_format" class="form-control" value="<?php echo e($val('date_format', 'd-m-Y')); ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label for="theme" class="form-label"><?php echo e(__('Theme')); ?></label>
                        <select name="theme" id="theme" class="form-select">
                            <option value="light" <?php echo e($val('theme', 'light') === 'light' ? 'selected' : ''); ?>><?php echo e(__('Light')); ?></option>
                            <option value="dark" <?php echo e($val('theme') === 'dark' ? 'selected' : ''); ?>><?php echo e(__('Dark')); ?></option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="primary_color" class="form-label"><?php echo e(__('Primary colour')); ?></label>
                        <input type="color" name="primary_color" id="primary_color" class="form-control form-control-color" value="<?php echo e($val('primary_color', '#0d6efd')); ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label for="secondary_color" class="form-label"><?php echo e(__('Secondary colour')); ?></label>
                        <input type="color" name="secondary_color" id="secondary_color" class="form-control form-control-color" value="<?php echo e($val('secondary_color', '#6c757d')); ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label for="invoice_prefix" class="form-label"><?php echo e(__('Invoice prefix')); ?></label>
                        <input type="text" name="invoice_prefix" id="invoice_prefix" class="form-control" value="<?php echo e($val('invoice_prefix', 'INV')); ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label for="voucher_prefix" class="form-label"><?php echo e(__('Voucher prefix')); ?></label>
                        <input type="text" name="voucher_prefix" id="voucher_prefix" class="form-control" value="<?php echo e($val('voucher_prefix', 'VCH')); ?>" required>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary"><?php echo e(__('Save settings')); ?></button>
            </div>
        </div>
    </form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\xampp\htdocs\manpower-erp-new\resources\views/settings/index.blade.php ENDPATH**/ ?>