<?php $__env->startSection('title', __('Dashboard') . ' — ' . \App\Services\BrandingService::appName()); ?>

<?php $__env->startSection('breadcrumbs'); ?>
    <li class="breadcrumb-item active" aria-current="page"><?php echo e(__('Dashboard')); ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3"><?php if (isset($component)) { $__componentOriginal527fae77f4db36afc8c8b7e9f5f81682 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.stat-card','data' => ['label' => __('Total Recruitments'),'value' => $stats['total_recruitments']]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Total Recruitments')),'value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($stats['total_recruitments'])]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682)): ?>
<?php $attributes = $__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682; ?>
<?php unset($__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal527fae77f4db36afc8c8b7e9f5f81682)): ?>
<?php $component = $__componentOriginal527fae77f4db36afc8c8b7e9f5f81682; ?>
<?php unset($__componentOriginal527fae77f4db36afc8c8b7e9f5f81682); ?>
<?php endif; ?></div>
        <div class="col-6 col-lg-3"><?php if (isset($component)) { $__componentOriginal527fae77f4db36afc8c8b7e9f5f81682 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.stat-card','data' => ['label' => __('In Progress'),'value' => $stats['in_progress']]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('In Progress')),'value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($stats['in_progress'])]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682)): ?>
<?php $attributes = $__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682; ?>
<?php unset($__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal527fae77f4db36afc8c8b7e9f5f81682)): ?>
<?php $component = $__componentOriginal527fae77f4db36afc8c8b7e9f5f81682; ?>
<?php unset($__componentOriginal527fae77f4db36afc8c8b7e9f5f81682); ?>
<?php endif; ?></div>
        <div class="col-6 col-lg-3"><?php if (isset($component)) { $__componentOriginal527fae77f4db36afc8c8b7e9f5f81682 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.stat-card','data' => ['label' => __('Completed'),'value' => $stats['completed']]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Completed')),'value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($stats['completed'])]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682)): ?>
<?php $attributes = $__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682; ?>
<?php unset($__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal527fae77f4db36afc8c8b7e9f5f81682)): ?>
<?php $component = $__componentOriginal527fae77f4db36afc8c8b7e9f5f81682; ?>
<?php unset($__componentOriginal527fae77f4db36afc8c8b7e9f5f81682); ?>
<?php endif; ?></div>
        <div class="col-6 col-lg-3"><?php if (isset($component)) { $__componentOriginal527fae77f4db36afc8c8b7e9f5f81682 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.stat-card','data' => ['label' => __('Passport Holders'),'value' => $stats['passport_holders']]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Passport Holders')),'value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($stats['passport_holders'])]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682)): ?>
<?php $attributes = $__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682; ?>
<?php unset($__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal527fae77f4db36afc8c8b7e9f5f81682)): ?>
<?php $component = $__componentOriginal527fae77f4db36afc8c8b7e9f5f81682; ?>
<?php unset($__componentOriginal527fae77f4db36afc8c8b7e9f5f81682); ?>
<?php endif; ?></div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header"><?php echo e(__('Recruitments by Stage')); ?></div>
                <div class="card-body">
                    <?php if(empty($byStage)): ?>
                        <p class="text-muted mb-0"><?php echo e(__('No recruitments yet.')); ?></p>
                    <?php else: ?>
                        <canvas id="stageChart" height="220" data-labels="<?php echo e(json_encode(array_keys($byStage))); ?>" data-values="<?php echo e(json_encode(array_values($byStage))); ?>"></canvas>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header"><?php echo e(__('Recruitments by Country')); ?></div>
                <div class="card-body">
                    <?php if(empty($byCountry)): ?>
                        <p class="text-muted mb-0"><?php echo e(__('No recruitments yet.')); ?></p>
                    <?php else: ?>
                        <canvas id="countryChart" height="220" data-labels="<?php echo e(json_encode(array_keys($byCountry))); ?>" data-values="<?php echo e(json_encode(array_values($byCountry))); ?>"></canvas>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <span><?php echo e(__('Recent Recruitments')); ?></span>
                    <span class="text-muted small"><?php echo e($recent->count()); ?> <?php echo e(__('shown')); ?></span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th><?php echo e(__('Tracking No.')); ?></th>
                                <th><?php echo e(__('Candidate')); ?></th>
                                <th><?php echo e(__('Country')); ?></th>
                                <th><?php echo e(__('Stage')); ?></th>
                                <th><?php echo e(__('Status')); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $recent; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $recruitment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($recruitment->tracking_number); ?></td>
                                    <td><?php echo e($recruitment->passportHolder?->full_name); ?></td>
                                    <td><?php echo e($recruitment->country?->name); ?></td>
                                    <td><?php echo e(ucwords(str_replace('_', ' ', $recruitment->current_stage))); ?></td>
                                    <td><?php if (isset($component)) { $__componentOriginal2ddbc40e602c342e508ac696e52f8719 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2ddbc40e602c342e508ac696e52f8719 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.badge','data' => ['status' => $recruitment->current_status]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($recruitment->current_status)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2ddbc40e602c342e508ac696e52f8719)): ?>
<?php $attributes = $__attributesOriginal2ddbc40e602c342e508ac696e52f8719; ?>
<?php unset($__attributesOriginal2ddbc40e602c342e508ac696e52f8719); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2ddbc40e602c342e508ac696e52f8719)): ?>
<?php $component = $__componentOriginal2ddbc40e602c342e508ac696e52f8719; ?>
<?php unset($__componentOriginal2ddbc40e602c342e508ac696e52f8719); ?>
<?php endif; ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr><td colspan="5" class="text-center text-muted py-4"><?php echo e(__('No recruitments yet.')); ?></td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header"><?php echo e(__('Passports Expiring Soon (60 days)')); ?></div>
                <ul class="list-group list-group-flush">
                    <?php $__empty_1 = true; $__currentLoopData = $expiringPassports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $holder): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <li class="list-group-item d-flex justify-content-between">
                            <span><?php echo e($holder->full_name); ?></span>
                            <span class="text-muted"><?php echo e($holder->passport_expiry_date?->format('d-m-Y')); ?></span>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <li class="list-group-item text-muted"><?php echo e(__('No passports expiring soon.')); ?></li>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="card mt-3">
                <div class="card-body">
                    <div class="small text-muted"><?php echo e(__('Posted vouchers this month')); ?></div>
                    <div class="h4 mb-0"><?php echo e($stats['posted_vouchers_this_month']); ?></div>
                    <div class="small text-muted mt-2"><?php echo e(__('Receipts collected this month')); ?></div>
                    <div class="h5 mb-0"><?php echo e(number_format($stats['total_collected_this_month'], 2)); ?> <?php echo e(\App\Services\BrandingService::currency()); ?></div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('canvas[data-labels]').forEach(function (el) {
        new Chart(el, {
            type: 'bar',
            data: {
                labels: JSON.parse(el.dataset.labels),
                datasets: [{ data: JSON.parse(el.dataset.values), backgroundColor: getComputedStyle(document.documentElement).getPropertyValue('--brand-primary') || '#0d6efd' }]
            },
            options: { responsive: true, plugins: { legend: { display: false } } }
        });
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\xampp\htdocs\up2\resources\views/dashboard/index.blade.php ENDPATH**/ ?>