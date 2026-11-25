<?php $__env->startSection('title', 'Recruitment Details'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Recruitment Details</h1>
    <div class="d-flex gap-2">
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('recruitment.view')): ?>
            <a href="<?php echo e(route('recruitments.download-format', [$recruitment, 'cv'])); ?>" class="btn btn-outline-dark">Download CV</a>
            <a href="<?php echo e(route('recruitments.download-format', [$recruitment, 'entry-form'])); ?>" class="btn btn-outline-dark">Barcode Entry</a>
            <a href="<?php echo e(route('recruitments.download-format', [$recruitment, 'id-card'])); ?>" class="btn btn-outline-dark">ID Card</a>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('recruitment.edit')): ?>
            <?php if (isset($component)) { $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['href' => ''.e(route('recruitments.edit', $recruitment)).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('recruitments.edit', $recruitment)).'']); ?>Edit <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $attributes = $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $component = $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
        <?php endif; ?>
        <?php if (isset($component)) { $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['href' => ''.e(route('recruitments.index')).'','class' => 'btn-outline-secondary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('recruitments.index')).'','class' => 'btn-outline-secondary']); ?>Back to list <?php echo $__env->renderComponent(); ?>
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
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-borderless mb-0">
            <tbody>
        <tr><th scope="row" style="width: 220px;">SL</th><td><?php echo e($recruitment->passportHolder?->id ?? '—'); ?></td></tr>
        <tr><th scope="row" style="width: 220px;">Tracking Number</th><td><?php echo e($recruitment->tracking_number); ?></td></tr>
        <tr><th scope="row">Registration Type</th><td><?php echo e(match($recruitment->service_type) {'student' => 'Student', 'hajj_umrah' => 'Hajj & Umrah', default => 'Overseas Employment'}); ?></td></tr>
        <?php if($recruitment->servicePackage): ?><tr><th scope="row">Package</th><td><?php echo e($recruitment->servicePackage->name); ?> (<?php echo e($recruitment->servicePackage->code); ?>)</td></tr><tr><th scope="row">Quoted Cost / Due</th><td><?php echo e($recruitment->quoted_currency); ?> <?php echo e(number_format((float) $recruitment->quoted_amount, 2)); ?> · Remaining due <?php echo e(number_format((float) $recruitment->remaining_due, 2)); ?></td></tr><?php endif; ?>
        <tr><th scope="row" style="width: 220px;">Passport Holder</th><td><?php echo e($recruitment->passportHolder?->full_name ?? "—"); ?></td></tr>
        <tr><th scope="row" style="width: 220px;">Agent</th><td><?php echo e($recruitment->agent?->name ?? "—"); ?></td></tr>
        <tr><th scope="row" style="width: 220px;">Branch</th><td><?php echo e($recruitment->branch?->name ?? "—"); ?></td></tr>
        <tr><th scope="row" style="width: 220px;">Country</th><td><?php echo e($recruitment->country?->name ?? "—"); ?></td></tr>
        <tr><th scope="row" style="width: 220px;">Company</th><td><?php echo e($recruitment->company?->name ?? "—"); ?></td></tr>
        <tr><th scope="row" style="width: 220px;">Position</th><td><?php echo e($recruitment->position?->title ?? "—"); ?></td></tr>
        <tr><th scope="row" style="width: 220px;">Expected Salary</th><td><?php echo e($recruitment->expected_salary !== null ? number_format((float) $recruitment->expected_salary, 2) : "—"); ?></td></tr>
        <tr><th scope="row" style="width: 220px;">Current Stage</th><td><?php if (isset($component)) { $__componentOriginal2ddbc40e602c342e508ac696e52f8719 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2ddbc40e602c342e508ac696e52f8719 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.badge','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?><?php echo e($recruitment->current_stage); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2ddbc40e602c342e508ac696e52f8719)): ?>
<?php $attributes = $__attributesOriginal2ddbc40e602c342e508ac696e52f8719; ?>
<?php unset($__attributesOriginal2ddbc40e602c342e508ac696e52f8719); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2ddbc40e602c342e508ac696e52f8719)): ?>
<?php $component = $__componentOriginal2ddbc40e602c342e508ac696e52f8719; ?>
<?php unset($__componentOriginal2ddbc40e602c342e508ac696e52f8719); ?>
<?php endif; ?></td></tr>
        <tr><th scope="row" style="width: 220px;">Current Status</th><td><?php if (isset($component)) { $__componentOriginal2ddbc40e602c342e508ac696e52f8719 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2ddbc40e602c342e508ac696e52f8719 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.badge','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?><?php echo e($recruitment->current_status); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2ddbc40e602c342e508ac696e52f8719)): ?>
<?php $attributes = $__attributesOriginal2ddbc40e602c342e508ac696e52f8719; ?>
<?php unset($__attributesOriginal2ddbc40e602c342e508ac696e52f8719); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2ddbc40e602c342e508ac696e52f8719)): ?>
<?php $component = $__componentOriginal2ddbc40e602c342e508ac696e52f8719; ?>
<?php unset($__componentOriginal2ddbc40e602c342e508ac696e52f8719); ?>
<?php endif; ?></td></tr>
        <tr><th scope="row" style="width: 220px;">Financial Responsibility</th><td><?php echo e($recruitment->financial_responsible_type === 'agent' ? 'Agent: '.($recruitment->agent?->name ?? '—') : ($recruitment->financial_responsible_type === 'passport_holder' ? 'Passport Holder: '.($recruitment->passportHolder?->full_name ?? '—') : 'To be decided at Selection & Grading')); ?></td></tr>
        <tr><th scope="row" style="width: 220px;">Remarks</th><td><?php echo e($recruitment->remarks ?? "—"); ?></td></tr>
            </tbody>
        </table>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">Workflow</div>
    <div class="card-body">
        <?php if($recruitment->service_type === 'student' && $recruitment->current_status !== 'cancelled'): ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('recruitment.edit')): ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('recruitment.approve')): ?>
                <?php if(in_array([$recruitment->current_stage, $recruitment->current_status], [['selection', 'approved'], ['university_management', 'pending'], ['university_management', 'completed']], true)): ?>
                    <form method="POST" action="<?php echo e(route('recruitments.student-stage', $recruitment)); ?>" class="border rounded p-3 mb-3"><?php echo csrf_field(); ?><input type="hidden" name="stage" value="university_management"><h5>University Management</h5><label class="form-label">University *</label><select name="university_id" class="form-select mb-2" required><option value="">Select University</option><?php $__currentLoopData = $universities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $university): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($university->id); ?>" <?php if($recruitment->university_id==$university->id): echo 'selected'; endif; ?>><?php echo e($university->name); ?> — <?php echo e($university->country); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select><input name="remarks" class="form-control mb-2" placeholder="Notes"><button class="btn btn-primary">Save University &amp; Continue</button></form>
                <?php endif; ?>
                <?php if(in_array([$recruitment->current_stage, $recruitment->current_status], [['university_management', 'completed'], ['offer_letter', 'pending'], ['offer_letter', 'issued']], true)): ?>
                    <form method="POST" enctype="multipart/form-data" action="<?php echo e(route('recruitments.student-stage', $recruitment)); ?>" class="border rounded p-3 mb-3"><?php echo csrf_field(); ?><input type="hidden" name="stage" value="offer_letter"><h5>Offer Letter</h5><input type="file" name="document" class="form-control mb-2" accept=".pdf,.jpg,.jpeg,.png" required><input name="remarks" class="form-control mb-2" placeholder="Notes"><button class="btn btn-primary">Save Offer Letter &amp; Continue</button></form>
                <?php endif; ?>
                <?php if(in_array([$recruitment->current_stage, $recruitment->current_status], [['offer_letter', 'issued'], ['admission', 'pending'], ['admission', 'admitted']], true)): ?>
                    <form method="POST" enctype="multipart/form-data" action="<?php echo e(route('recruitments.student-stage', $recruitment)); ?>" class="border rounded p-3 mb-3"><?php echo csrf_field(); ?><input type="hidden" name="stage" value="admission"><h5>Admission</h5><input type="file" name="document" class="form-control mb-2" accept=".pdf,.jpg,.jpeg,.png" required><input name="remarks" class="form-control mb-2" placeholder="Notes"><button class="btn btn-primary">Save Admission &amp; Continue</button></form>
                <?php endif; ?>
            <?php endif; ?>
            <?php endif; ?>
        <?php endif; ?>
        <?php if (isset($component)) { $__componentOriginalfed8860d116b3f02bb30c55071819700 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalfed8860d116b3f02bb30c55071819700 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.workflow-timeline','data' => ['recruitment' => $recruitment,'stages' => $workflowStages]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('workflow-timeline'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['recruitment' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($recruitment),'stages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($workflowStages)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalfed8860d116b3f02bb30c55071819700)): ?>
<?php $attributes = $__attributesOriginalfed8860d116b3f02bb30c55071819700; ?>
<?php unset($__attributesOriginalfed8860d116b3f02bb30c55071819700); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalfed8860d116b3f02bb30c55071819700)): ?>
<?php $component = $__componentOriginalfed8860d116b3f02bb30c55071819700; ?>
<?php unset($__componentOriginalfed8860d116b3f02bb30c55071819700); ?>
<?php endif; ?>
    </div>
</div>
<div class="card mt-4">
    <div class="card-header">Documents</div>
    <div class="card-body">
        <?php $__empty_1 = true; $__currentLoopData = $recruitment->documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $document): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span><?php echo e(ucfirst(str_replace('_', ' ', $document->type))); ?> - <?php echo e($document->original_name ?? basename($document->file_path)); ?></span>
                <?php if (isset($component)) { $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['href' => ''.e(route('recruitments.documents.download', [$recruitment, $document])).'','class' => 'btn-sm btn-outline-primary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('recruitments.documents.download', [$recruitment, $document])).'','class' => 'btn-sm btn-outline-primary']); ?>Download <?php echo $__env->renderComponent(); ?>
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
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p class="text-muted mb-0">No documents uploaded yet.</p>
        <?php endif; ?>
    </div>
</div>
<div class="card mt-4">
    <div class="card-header">Stage History</div>
    <div class="card-body">
        <?php $__empty_1 = true; $__currentLoopData = $recruitment->stageHistories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $history): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div><?php echo e($history->stage); ?> - <?php echo e($history->status); ?> - <?php echo e($history->changed_at); ?> <?php if($history->remarks): ?> - <?php echo e($history->remarks); ?> <?php endif; ?></div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p class="text-muted mb-0">No stage history recorded yet.</p>
        <?php endif; ?>
    </div>
</div>
<?php if($recruitment->current_status !== 'cancelled' && $recruitment->current_status !== 'completed'): ?>
<div class="card mt-4">
    <div class="card-header">Cancel Registration</div>
    <div class="card-body">
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('cancel', $recruitment)): ?>
        <form method="POST" action="<?php echo e(route('recruitments.cancel', $recruitment)); ?>">
            <?php echo csrf_field(); ?>
            <div class="mb-3">
                <label for="cancel_remarks" class="form-label">Cancellation Remarks</label>
                <textarea id="cancel_remarks" name="remarks" rows="2" class="form-control"></textarea>
            </div>
            <button type="submit" class="btn btn-danger" onclick="return confirm('Cancel this registration? The record will be kept as history.')">Cancel Registration</button>
        </form>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\xampp\htdocs\manpower-erp-new\resources\views/recruitments/show.blade.php ENDPATH**/ ?>