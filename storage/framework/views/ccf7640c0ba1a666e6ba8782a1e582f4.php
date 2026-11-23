<?php $__env->startSection('title', 'Edit Recruitment'); ?>

<?php $__env->startSection('content'); ?>
    <style>
        /* =========================================
       PROFESSIONAL GREEN INPUT HOVER EFFECT
    ========================================= */

        /* All Form Fields */
        .form-control,
        .form-select,
        textarea.form-control {
            border: 1px solid #ced8d0;
            border-radius: 8px;
            background-color: #ffffff;
            transition: all .25s ease-in-out;
        }

        /* Mouse Hover - Green Background */
        .form-control:hover,
        .form-select:hover,
        textarea.form-control:hover {
            background-color: #edfff2 !important;
            border-color: #16a34a !important;
            box-shadow: 0 0 7px rgba(22, 163, 74, .20);
        }

        /* Input Focus - Green Glow */
        .form-control:focus,
        .form-select:focus,
        textarea.form-control:focus {
            background-color: #f0fff4 !important;
            border: 1px solid #16a34a !important;

            box-shadow: 0 0 0 3px rgba(22, 163, 74, .18) !important;

            outline: none;
            transition: all .25s ease-in-out;
        }

        /* Placeholder */
        .form-control::placeholder,
        textarea.form-control::placeholder {
            color: #94a3a0;
            transition: color .2s ease;
        }

        .form-control:focus::placeholder,
        textarea.form-control:focus::placeholder {
            color: #16a34a;
        }

        /* Select Dropdown */
        .form-select:hover,
        .form-select:focus {
            cursor: pointer;
        }

        /* File Input */
        input[type="file"].form-control:hover {
            background-color: #edfff2 !important;
        }

        /* Readonly Fields */
        .form-control[readonly]:hover {
            background-color: #f0fff4 !important;
            border-color: #16a34a !important;
        }

        /* Disabled Fields */
        .form-control:disabled,
        .form-select:disabled {
            background-color: #e9ecef !important;
            cursor: not-allowed;
            box-shadow: none !important;
        }

        /* Validation Error */
        .form-control.is-invalid:hover,
        .form-select.is-invalid:hover {
            border-color: #dc3545 !important;
            background-color: #fff5f5 !important;
        }

        /* Smooth Animation */
        @media (prefers-reduced-motion: reduce) {

            .form-control,
            .form-select,
            textarea.form-control {
                transition: none;
            }
        }
    </style>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Edit Recruitment</h1>

        <?php if (isset($component)) { $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['href' => ''.e(route('recruitments.show', $recruitment)).'','variant' => 'dark','size' => 'sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('recruitments.show', $recruitment)).'','variant' => 'dark','size' => 'sm']); ?>
            <i class="fas fa-arrow-left me-1"></i> Back to details
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

    <div class="card">
        <div class="card-body">
            <form method="POST" action="<?php echo e(route('recruitments.update', $recruitment)); ?>" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <?php echo $__env->make('recruitments._form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                <div class="d-flex gap-2 mt-4">
                    <?php if (isset($component)) { $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['type' => 'submit','variant' => 'success']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'submit','variant' => 'success']); ?>
                        <i class="fas fa-save me-1"></i> Update Recruitment
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['href' => ''.e(route('recruitments.show', $recruitment)).'','variant' => 'danger']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('recruitments.show', $recruitment)).'','variant' => 'danger']); ?>
                        Cancel
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
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\xampp\htdocs\manpower-erp-new\resources\views/recruitments/edit.blade.php ENDPATH**/ ?>