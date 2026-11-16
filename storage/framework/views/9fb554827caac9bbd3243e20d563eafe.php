<?php $__env->startSection('title', __('Company Profile') . ' — ' . \App\Services\BrandingService::appName()); ?>

<?php $__env->startSection('breadcrumbs'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('settings.index')); ?>"><?php echo e(__('Settings')); ?></a></li>
    <li class="breadcrumb-item active" aria-current="page"><?php echo e(__('Company Profile')); ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <h1 class="h4 mb-3"><?php echo e(__('Company Profile')); ?></h1>
    <p class="text-muted"><?php echo e(__('This branding appears on every page, report and PDF. Nothing here is hard-coded.')); ?></p>

    <form method="POST" action="<?php echo e(route('company-profile.update')); ?>" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        <div class="card">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label"><?php echo e(__('Company name')); ?></label>
                        <input type="text" name="name" id="name" class="form-control" value="<?php echo e(old('name', $profile->name)); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label"><?php echo e(__('Email')); ?></label>
                        <input type="email" name="email" id="email" class="form-control" value="<?php echo e(old('email', $profile->email)); ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="software_name" class="form-label"><?php echo e(__('Software display name')); ?></label>
                        <input type="text" name="software_name" id="software_name" class="form-control" value="<?php echo e(old('software_name', $profile->software_name)); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="software_short_name" class="form-label"><?php echo e(__('Short name')); ?></label>
                        <input type="text" name="software_short_name" id="software_short_name" class="form-control" value="<?php echo e(old('software_short_name', $profile->software_short_name)); ?>" required>
                    </div>
                    <div class="col-12">
                        <label for="address" class="form-label"><?php echo e(__('Address')); ?></label>
                        <textarea name="address" id="address" class="form-control" rows="2"><?php echo e(old('address', $profile->address)); ?></textarea>
                    </div>
                    <div class="col-md-4">
                        <label for="phone" class="form-label"><?php echo e(__('Phone')); ?></label>
                        <input type="text" name="phone" id="phone" class="form-control" value="<?php echo e(old('phone', $profile->phone)); ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="website" class="form-label"><?php echo e(__('Website')); ?></label>
                        <input type="url" name="website" id="website" class="form-control" value="<?php echo e(old('website', $profile->website)); ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="currency" class="form-label"><?php echo e(__('Currency')); ?></label>
                        <input type="text" name="currency" id="currency" class="form-control" value="<?php echo e(old('currency', $profile->currency ?? 'BDT')); ?>" maxlength="3" required>
                    </div>
                    <div class="col-md-4">
                        <label for="language" class="form-label"><?php echo e(__('Language')); ?></label>
                        <select name="language" id="language" class="form-select">
                            <option value="en" <?php echo e(old('language', $profile->language) === 'en' ? 'selected' : ''); ?>>English</option>
                            <option value="bn" <?php echo e(old('language', $profile->language) === 'bn' ? 'selected' : ''); ?>>Bangla</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="timezone" class="form-label"><?php echo e(__('Timezone')); ?></label>
                        <input type="text" name="timezone" id="timezone" class="form-control" value="<?php echo e(old('timezone', $profile->timezone ?? 'Asia/Dhaka')); ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label for="date_format" class="form-label"><?php echo e(__('Date format')); ?></label>
                        <input type="text" name="date_format" id="date_format" class="form-control" value="<?php echo e(old('date_format', $profile->date_format ?? 'd-m-Y')); ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label for="primary_color" class="form-label"><?php echo e(__('Primary colour')); ?></label>
                        <input type="color" name="primary_color" id="primary_color" class="form-control form-control-color" value="<?php echo e(old('primary_color', $profile->primary_color ?? '#0d6efd')); ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label for="secondary_color" class="form-label"><?php echo e(__('Secondary colour')); ?></label>
                        <input type="color" name="secondary_color" id="secondary_color" class="form-control form-control-color" value="<?php echo e(old('secondary_color', $profile->secondary_color ?? '#6c757d')); ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label for="invoice_prefix" class="form-label"><?php echo e(__('Invoice prefix')); ?></label>
                        <input type="text" name="invoice_prefix" id="invoice_prefix" class="form-control" value="<?php echo e(old('invoice_prefix', $profile->invoice_prefix ?? 'INV')); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="logo" class="form-label"><?php echo e(__('Logo')); ?> <span class="text-muted">(<?php echo e(__('JPG, PNG, WebP or SVG, max 2 MB')); ?>)</span></label>
                        <input type="file" name="logo" id="logo" class="form-control" accept="image/*">
                        <?php if($profile->logo_path): ?>
                            <div class="form-text"><?php echo e(__('Current logo is stored in private storage.')); ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6">
                        <label for="favicon" class="form-label"><?php echo e(__('Favicon')); ?> <span class="text-muted">(<?php echo e(__('max 512 KB')); ?>)</span></label>
                        <input type="file" name="favicon" id="favicon" class="form-control" accept="image/*">
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary"><?php echo e(__('Save company profile')); ?></button>
            </div>
        </div>
    </form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\xampp\htdocs\manpower-erp-new\resources\views/settings/company-profile.blade.php ENDPATH**/ ?>