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
                <option value="<?php echo e($recruitment->id); ?>" data-agent-id="<?php echo e($recruitment->agent_id); ?>" data-agent-name="<?php echo e($recruitment->agent?->name); ?>" data-holder-id="<?php echo e($recruitment->passport_holder_id); ?>" data-holder-name="<?php echo e($recruitment->passportHolder?->full_name); ?>" <?php if(old('recruitment_id', $record?->recruitment_id) == $recruitment->id): echo 'selected'; endif; ?>>
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
        'approved' => 'Selected',
        'returned' => 'Waiting',
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

<div class="row g-3 mt-1">
    <div class="col-md-3">
        <label for="total_amount" class="form-label"><?php echo e(__('Total Due Before Advance')); ?></label>
        <input type="number" id="total_amount" class="form-control" value="0.00" readonly>
    </div>
    <div class="col-md-3">
        <label for="remaining_due_display" class="form-label"><?php echo e(__('Remaining Due After Advance')); ?></label>
        <input type="number" id="remaining_due_display" class="form-control" value="0.00" readonly>
    </div>
    <div class="col-md-6">
        <label for="advance_account_id" class="form-label"><?php echo e(__('Advance Received In')); ?></label>
        <select name="advance_account_id" id="advance_account_id" class="form-select">
            <?php $__currentLoopData = ($cashAccounts ?? collect()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cashAccount): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($cashAccount->id); ?>" <?php if(old('advance_account_id', $cashAccount->code === '1110' ? $cashAccount->id : null) == $cashAccount->id): echo 'selected'; endif; ?>>
                    <?php echo e($cashAccount->code); ?> — <?php echo e($cashAccount->name); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <?php $__errorArgs = ['financial_responsible_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        <?php $__errorArgs = ['advance_amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        <?php $__errorArgs = ['advance_account_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const recruitment = document.getElementById('recruitment_id');
    const partyType = document.getElementById('financial_responsible_type');
    const partyId = document.getElementById('financial_responsible_id');
    const partyName = document.getElementById('responsible_party_name');
    const costFields = ['company_service_charge', 'agent_charge', 'visa_cost', 'medical_cost', 'training_cost', 'other_cost'];
    const advance = document.getElementById('advance_amount');
    const total = document.getElementById('total_amount');
    const due = document.getElementById('remaining_due_display');

    function updateParty() {
        const option = recruitment.options[recruitment.selectedIndex];
        if (option && partyType.value === 'agent' && !option.dataset.agentId && option.dataset.holderId) {
            partyType.value = 'passport_holder';
        }
        const isAgent = partyType.value === 'agent';
        partyId.value = option ? (isAgent ? option.dataset.agentId : option.dataset.holderId) || '' : '';
        partyName.value = option ? (isAgent ? option.dataset.agentName : option.dataset.holderName) || '' : '';
    }
    function updateAmounts() {
        const sum = costFields.reduce((value, id) => value + (parseFloat(document.getElementById(id)?.value) || 0), 0);
        const advanceValue = parseFloat(advance.value) || 0;
        total.value = sum.toFixed(2);
        due.value = Math.max(0, sum - advanceValue).toFixed(2);
        advance.max = sum.toFixed(2);
    }
    recruitment.addEventListener('change', updateParty);
    partyType.addEventListener('change', updateParty);
    [...costFields, 'advance_amount'].forEach(id => document.getElementById(id)?.addEventListener('input', updateAmounts));
    updateParty();
    updateAmounts();
});
</script>

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
        <label for="responsible_party_name" class="form-label"><?php echo e(__('Agent / Passport Holder Name')); ?></label>
        <input type="text" id="responsible_party_name" class="form-control" readonly>
        <input type="hidden" name="financial_responsible_id" id="financial_responsible_id"
            value="<?php echo e(old('financial_responsible_id', $recruitmentData?->financial_responsible_id ?? '')); ?>">
    </div>

</div>

<div class="row g-3 mt-1">

    <?php $__currentLoopData = [
        'salary' => 'Overseas Salary (not included in due)',
        'company_service_charge' => 'Company Service Charge',
        'agent_charge' => 'Agent Charge',
        'medical_cost' => 'Medical Cost',
        'training_cost' => 'Training Cost',
        'visa_cost' => 'Visa Cost',
        'other_cost' => 'Other Costs',
        'advance_amount' => 'Advance Amount',
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
<p class="form-text">Agent / Passport Holder due = Company Service Charge + Agent Charge + Visa Cost + Medical Cost + Training Cost + Other Costs − Advance. Overseas salary is shown separately and is not added to due.</p>

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