<?php $__env->startSection('title', $titles[$register]); ?>
<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="mb-0"><?php echo e($titles[$register]); ?></h1>
    <a class="btn btn-outline-secondary" href="<?php echo e(route($register.'.index')); ?>"><?php echo e(__('Back to list')); ?></a>
</div>

<div class="card"><div class="card-body">
    <form method="POST" action="<?php echo e(route($register.'.store')); ?>">
        <?php echo csrf_field(); ?>
        <div class="row g-3">
            <div class="col-md-6">
                <label for="recruitment_id" class="form-label"><?php echo e(__('Candidate / Recruitment')); ?> *</label>
                <select id="recruitment_id" name="recruitment_id" required class="form-select <?php $__errorArgs = ['recruitment_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <option value=""><?php echo e(__('Select candidate')); ?></option>
                    <?php $__currentLoopData = $recruitments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $recruitment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($recruitment->id); ?>" <?php if((string) old('recruitment_id') === (string) $recruitment->id): echo 'selected'; endif; ?>><?php echo e($recruitment->tracking_number); ?> — <?php echo e($recruitment->passportHolder?->full_name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['recruitment_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="col-md-6">
                <label for="arrival_date" class="form-label"><?php echo e(__('Arrival Date')); ?></label>
                <input id="arrival_date" type="date" name="arrival_date" value="<?php echo e(old('arrival_date')); ?>" class="form-control <?php $__errorArgs = ['arrival_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                <?php $__errorArgs = ['arrival_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <?php if($register === 'airport-pickup'): ?>
                <div class="col-12"><div class="alert alert-info mb-0"><?php echo e(__('This record will mark airport pickup as required for the selected candidate.')); ?></div></div>
            <?php elseif($register === 'employer-information'): ?>
                <div class="col-12">
                    <label for="employer_contact" class="form-label"><?php echo e(__('Employer Contact Information')); ?> *</label>
                    <input id="employer_contact" name="employer_contact" value="<?php echo e(old('employer_contact')); ?>" maxlength="255" required class="form-control <?php $__errorArgs = ['employer_contact'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <?php $__errorArgs = ['employer_contact'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            <?php elseif($register === 'accommodation'): ?>
                <div class="col-12">
                    <label for="accommodation" class="form-label"><?php echo e(__('Accommodation Details')); ?> *</label>
                    <textarea id="accommodation" name="accommodation" rows="4" maxlength="5000" required class="form-control <?php $__errorArgs = ['accommodation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e(old('accommodation')); ?></textarea>
                    <?php $__errorArgs = ['accommodation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            <?php else: ?>
                <div class="col-12">
                    <label for="feedback" class="form-label"><?php echo e(__('Candidate Feedback')); ?> *</label>
                    <textarea id="feedback" name="feedback" rows="4" maxlength="5000" required class="form-control <?php $__errorArgs = ['feedback'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e(old('feedback')); ?></textarea>
                    <?php $__errorArgs = ['feedback'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            <?php endif; ?>

            <div class="col-md-6">
                <label for="status" class="form-label"><?php echo e(__('Post-arrival Status')); ?> *</label>
                <select id="status" name="status" required class="form-select <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <?php $__currentLoopData = \App\Services\PostArrivalService::STATUSES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($status); ?>" <?php if(old('status', 'pending') === $status): echo 'selected'; endif; ?>><?php echo e(__(ucwords(str_replace('_', ' ', $status)))); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        </div>
        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-primary"><?php echo e(__('Save')); ?></button>
            <a class="btn btn-outline-secondary" href="<?php echo e(route($register.'.index')); ?>"><?php echo e(__('Cancel')); ?></a>
        </div>
    </form>
</div></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\xampp\htdocs\manpower-erp-new\resources\views/post-arrivals/register-create.blade.php ENDPATH**/ ?>