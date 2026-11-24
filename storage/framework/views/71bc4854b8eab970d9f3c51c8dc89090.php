<?php echo csrf_field(); ?>
<div class="row g-3">
    <div class="col-md-6"><label class="form-label" for="training_id"><?php echo e(__('Training Record')); ?> <span class="text-danger">*</span></label><select class="form-select <?php $__errorArgs = ['training_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="training_id" id="training_id" required><option value=""><?php echo e(__('Select training')); ?></option><?php $__currentLoopData = $trainings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $training): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($training->id); ?>" <?php if(old('training_id', $record->training_id ?? request('training_id')) == $training->id): echo 'selected'; endif; ?>><?php echo e($training->recruitment?->tracking_number ?? ('#'.$training->id)); ?> — <?php echo e($training->recruitment?->passportHolder?->full_name); ?> (<?php echo e($training->course?->title ?? __('Training')); ?>)</option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select><?php $__errorArgs = ['training_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
    <div class="col-md-3"><label class="form-label" for="attendance_date"><?php echo e(__('Date')); ?> <span class="text-danger">*</span></label><input type="date" class="form-control <?php $__errorArgs = ['attendance_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="attendance_date" id="attendance_date" required value="<?php echo e(old('attendance_date', isset($record) ? $record->attendance_date?->format('Y-m-d') : '')); ?>"><?php $__errorArgs = ['attendance_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
    <div class="col-md-3"><label class="form-label" for="status"><?php echo e(__('Status')); ?> <span class="text-danger">*</span></label><select class="form-select" name="status" id="status" required><?php $__currentLoopData = ['present' => __('Present'), 'absent' => __('Absent'), 'late' => __('Late'), 'excused' => __('Excused')]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($value); ?>" <?php if(old('status', $record->status ?? 'present') === $value): echo 'selected'; endif; ?>><?php echo e($label); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
    <div class="col-12"><label class="form-label" for="remarks"><?php echo e(__('Remarks')); ?></label><textarea class="form-control" name="remarks" id="remarks" rows="2"><?php echo e(old('remarks', $record->remarks ?? '')); ?></textarea></div>
</div>
<div class="d-flex gap-2 mt-4"><button class="btn btn-success"><?php echo e(__('Save')); ?></button><a class="btn btn-secondary" href="<?php echo e(route('training-attendance.index')); ?>"><?php echo e(__('Cancel')); ?></a></div>
<?php /**PATH R:\xampp\htdocs\manpower-erp-new\resources\views/training-attendance/_form.blade.php ENDPATH**/ ?>