<?php
    $record = $record ?? null;
    $recruitmentData = $record?->recruitment;

    $grades = [
        'A+' => 'Grade A+',
        'A' => 'Grade A',
        'B' => 'Grade B',
        'C' => 'Grade C',
        'D' => 'Grade D',
    ];
?>

<?php echo csrf_field(); ?>

<div class="row g-3">

    
    <div class="col-xl-3 col-lg-3 col-md-6">
        <label for="recruitment_id" class="form-label">Recruitment</label>
        <select name="recruitment_id" id="recruitment_id" class="form-select" required>
            <option value="">-- <?php echo e(__('Select recruitment')); ?> --</option>

            <?php $__currentLoopData = $recruitments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $recruitment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($recruitment->id); ?>" <?php if(old('recruitment_id', $record?->recruitment_id) == $recruitment->id): echo 'selected'; endif; ?>>
                    <?php echo e($recruitment->tracking_number); ?>

                    — <?php echo e($recruitment->passportHolder?->full_name ?? 'N/A'); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <?php $__errorArgs = ['recruitment_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="text-danger small"><?php echo e($message); ?></div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    
    <div class="col-xl-3 col-lg-3 col-md-6">
        <label for="grade" class="form-label">Grade</label>
        <select name="grade" id="grade" class="form-select" required>
            <option value="">-- Select Grade --</option>

            <?php $__currentLoopData = $grades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($value); ?>" <?php if(old('grade', $record?->grade ?? '') == $value): echo 'selected'; endif; ?>>
                    <?php echo e($label); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <?php $__errorArgs = ['grade'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="text-danger small"><?php echo e($message); ?></div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    
    <div class="col-xl-3 col-lg-3 col-md-6">
        <label for="status" class="form-label">Status</label>
        <select name="status" id="status" class="form-select">
            <?php $__currentLoopData = [
        'pending' => 'Pending',
        'selected' => 'Selected',
        'waiting' => 'Waiting',
        'rejected' => 'Rejected',
        'cancelled' => 'Cancelled',
    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($value); ?>" <?php if(old('status', $record?->status ?? 'pending') == $value): echo 'selected'; endif; ?>>
                    <?php echo e($label); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="text-danger small"><?php echo e($message); ?></div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    
    <div class="col-xl-3 col-lg-3 col-md-6">
        <label for="decided_at" class="form-label">Decided At</label>
        <input type="date" name="decided_at" id="decided_at" class="form-control"
            value="<?php echo e(old('decided_at', $record?->decided_at ? \Illuminate\Support\Carbon::parse($record->decided_at)->format('Y-m-d') : '')); ?>">
        <?php $__errorArgs = ['decided_at'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="text-danger small"><?php echo e($message); ?></div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    
    <div class="col-xl-3 col-lg-3 col-md-6">
        <label for="remarks" class="form-label">Remarks</label>
        <textarea name="remarks" id="remarks" class="form-control" rows="3"><?php echo e(old('remarks', $record?->remarks ?? '')); ?></textarea>
        <?php $__errorArgs = ['remarks'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="text-danger small"><?php echo e($message); ?></div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

</div>

<hr>

<h5><?php echo e(__('Financial Responsibility (decided at Selection)')); ?></h5>

<div class="row g-3">

    <div class="col-md-6">
        <label for="financial_responsible_type" class="form-label">
            <?php echo e(__('Responsible Party')); ?>

        </label>

        <select name="financial_responsible_type" id="financial_responsible_type" class="form-select">
            <option value="agent" <?php if(old('financial_responsible_type', $recruitmentData?->financial_responsible_type ?? 'agent') == 'agent'): echo 'selected'; endif; ?>>
                <?php echo e(__('Agent')); ?>

            </option>

            <option value="passport_holder" <?php if(old('financial_responsible_type', $recruitmentData?->financial_responsible_type ?? '') == 'passport_holder'): echo 'selected'; endif; ?>>
                <?php echo e(__('Passport Holder')); ?>

            </option>
        </select>
    </div>

    <div class="col-md-6">
        <label for="financial_responsible_id" class="form-label">
            <?php echo e(__('Responsible Party ID')); ?>

        </label>

        <input type="number" name="financial_responsible_id" id="financial_responsible_id" class="form-control"
            value="<?php echo e(old('financial_responsible_id', $recruitmentData?->financial_responsible_id ?? '')); ?>">
    </div>

</div>

<div class="row g-3 mt-1">

    <?php $__currentLoopData = [
        'salary' => 'Salary',
        'company_service_charge' => 'Company Service Charge',
        'agent_charge' => 'Agent Charge',
        'medical_cost' => 'Medical Cost',
        'training_cost' => 'Training Cost',
        'visa_cost' => 'Visa Cost',
        'other_cost' => 'Other Costs',
        'advance_amount' => 'Advance',
    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-xl-3 col-lg-3 col-md-6">
            <label for="<?php echo e($field); ?>" class="form-label">
                <?php echo e(__($label)); ?>

            </label>

            <input type="number" step="0.01" min="0" name="<?php echo e($field); ?>" id="<?php echo e($field); ?>"
                class="form-control" value="<?php echo e(old($field, $recruitmentData?->$field ?? '')); ?>">
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

</div>

<hr>

<div class="d-flex gap-2">
    <?php if (isset($component)) { $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['type' => 'submit','variant' => 'primary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'submit','variant' => 'primary']); ?>
        <?php echo e(__('Save')); ?>

     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $attributes = $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $component = $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>

    <?php if (isset($component)) { $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['href' => ''.e(route('selections.index')).'','variant' => 'secondary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('selections.index')).'','variant' => 'secondary']); ?>
        <?php echo e(__('Cancel')); ?>

     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $attributes = $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $component = $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
</div>
<?php /**PATH R:\xampp\htdocs\manpower-erp-new\resources\views/selections/_form.blade.php ENDPATH**/ ?>