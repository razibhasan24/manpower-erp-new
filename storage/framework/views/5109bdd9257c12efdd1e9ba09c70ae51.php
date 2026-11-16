
<?php
    $groups = \App\Support\Menu::groups(auth()->user());
    $profile = \App\Services\BrandingService::profile();
    $appName = \App\Services\BrandingService::appName();
    $shortName = \App\Services\BrandingService::shortName();
    $mark = \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($shortName !== '' ? $shortName : $appName, 0, 2));
?>

<aside id="appSidebar" class="app-sidebar" aria-label="<?php echo e(__('Main navigation')); ?>">
    <a href="<?php echo e(route('dashboard')); ?>" class="app-sidebar__brand" aria-label="<?php echo e($appName); ?>">
        <span class="brand-mark">
            <?php if($profile !== null && $profile->logo_path): ?>
                <img src="<?php echo e(route('branding.logo')); ?>" alt="">
            <?php else: ?>
                <?php echo e($mark); ?>

            <?php endif; ?>
        </span>
        <span class="brand-text">
            <strong title="<?php echo e($appName); ?>"><?php echo e($shortName !== '' ? $shortName : $appName); ?></strong>
            <small><?php echo e(__('Navigation')); ?></small>
        </span>
    </a>

    <nav class="app-sidebar__nav">
        <?php $__empty_1 = true; $__currentLoopData = $groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php if (isset($component)) { $__componentOriginald32807dc8f6c938009036f3d7a97d64e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald32807dc8f6c938009036f3d7a97d64e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sidebar-group','data' => ['group' => $group]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sidebar-group'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['group' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($group)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald32807dc8f6c938009036f3d7a97d64e)): ?>
<?php $attributes = $__attributesOriginald32807dc8f6c938009036f3d7a97d64e; ?>
<?php unset($__attributesOriginald32807dc8f6c938009036f3d7a97d64e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald32807dc8f6c938009036f3d7a97d64e)): ?>
<?php $component = $__componentOriginald32807dc8f6c938009036f3d7a97d64e; ?>
<?php unset($__componentOriginald32807dc8f6c938009036f3d7a97d64e); ?>
<?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p class="px-2 py-3 small text-white-50"><?php echo e(__('No menu items are available for your role.')); ?></p>
        <?php endif; ?>
    </nav>

    <div class="app-sidebar__footer">
        <?php echo e(\App\Services\BrandingService::currency()); ?> &middot; <?php echo e(\App\Services\BrandingService::timezone()); ?>

        <span class="d-block opacity-75"><?php echo e($appName); ?></span>
    </div>
</aside>
<?php /**PATH R:\xampp\htdocs\manpower-erp-new\resources\views/components/sidebar.blade.php ENDPATH**/ ?>