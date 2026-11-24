<?php echo csrf_field(); ?>
<div class="row g-3">
    <div class="col-md-6"><label class="form-label" for="training_id"><?php echo e(__('Training Record')); ?> <span class="text-danger">*</span></label><select class="form-select <?php $__errorArgs = ['training_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="training_id" id="training_id" required><option value=""><?php echo e(__('Select training')); ?></option><?php $__currentLoopData = $trainings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $training): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($training->id); ?>" <?php if(old('training_id', $certificate->training_id ?? '') == $training->id): echo 'selected'; endif; ?>><?php echo e($training->recruitment?->tracking_number ?? ('#'.$training->id)); ?> — <?php echo e($training->recruitment?->passportHolder?->full_name); ?> (<?php echo e($training->course?->title ?? __('Training')); ?>)</option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select><?php $__errorArgs = ['training_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
    <div class="col-md-3"><label class="form-label" for="certificate_number"><?php echo e(__('Certificate Number')); ?></label><input class="form-control <?php $__errorArgs = ['certificate_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="certificate_number" id="certificate_number" maxlength="100" value="<?php echo e(old('certificate_number', $certificate->certificate_number ?? '')); ?>"><?php $__errorArgs = ['certificate_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
    <div class="col-md-3"><label class="form-label" for="issued_at"><?php echo e(__('Issue Date')); ?> <span class="text-danger">*</span></label><input type="date" class="form-control <?php $__errorArgs = ['issued_at'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="issued_at" id="issued_at" required value="<?php echo e(old('issued_at', isset($certificate) ? $certificate->issued_at?->format('Y-m-d') : '')); ?>"><?php $__errorArgs = ['issued_at'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
    <div class="col-12"><label class="form-label" for="file"><?php echo e(__('Certificate File')); ?> <?php if(empty($certificate)): ?><span class="text-danger">*</span><?php endif; ?></label><input type="file" class="form-control <?php $__errorArgs = ['file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="file" id="file" accept=".pdf,.jpg,.jpeg,.png" <?php if(empty($certificate)): ?> required <?php endif; ?>><?php $__errorArgs = ['file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> <?php if(isset($certificate)): ?><div class="form-text"><?php echo e(__('Current file: :name', ['name' => $certificate->original_name ?? basename($certificate->file_path)])); ?></div><?php endif; ?></div>
</div>
<div class="d-flex gap-2 mt-4"><button class="btn btn-success"><?php echo e(__('Save')); ?></button><a class="btn btn-secondary" href="<?php echo e(isset($certificate) ? route('training-certificates.show', $certificate) : route('training-certificates.index')); ?>"><?php echo e(__('Cancel')); ?></a></div>
<?php /**PATH R:\xampp\htdocs\manpower-erp-new\resources\views/training-certificates/_form.blade.php ENDPATH**/ ?>