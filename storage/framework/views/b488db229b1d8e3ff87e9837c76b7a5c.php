<?php $__env->startSection('title', 'New Recruitment'); ?>



<?php $__env->startSection('content'); ?>

    <style>
        /* Professional Section Heading */
        .section-heading {
            display: flex;
            align-items: center;
            gap: 5px;

            padding: 13px 18px;
            margin-top: 15px;
            margin-bottom: 18px;

            color: #ffffff;
            font-size: 17px;
            font-weight: 800;
            letter-spacing: .4px;

            background: linear-gradient(110deg, #77d098, #b59ef7, #5a88eb);

            /* border: 2px solid rgb(227, 148, 148); */
            border-radius: 9px;

            /* box-shadow: 0 4px 12px rgba(22, 163, 74, .20); */

            animation: sectionGlow 1.8s ease-in-out infinite;
        }

        /* Red Border Animation */
        /* @keyframes sectionGlow {
                0% {
                    border-color: #bf8f8f;
                    box-shadow: 0 0 3px #b57878;
                }

                50% {
                    border-color: #ff7777;
                    box-shadow: 0 0 12px #7a5d5d;
                }

                100% {
                    border-color: #dc9a9a;
                    box-shadow: 0 0 3px #d39999;
                }
            } */

        /* Icon Animation */
        .section-heading i {
            font-size: 20px;
            animation: iconPulse 1.5s ease-in-out infinite;
        }


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


        @keyframes iconPulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.18);
            }
        }

        /* Mobile Responsive */
        @media (max-width: 576px) {
            .section-heading {
                font-size: 15px;
                padding: 11px 13px;
            }
        }

        /* Accessibility */
        @media (prefers-reduced-motion: reduce) {

            .section-heading,
            .section-heading i {
                animation: none;
            }
        }
    </style>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">New Recruitment</h1>

        <?php if (isset($component)) { $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['href' => ''.e(route('recruitments.index')).'','variant' => 'dark','size' => 'sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('recruitments.index')).'','variant' => 'dark','size' => 'sm']); ?>
            <i class="fas fa-arrow-left me-1"></i> Back to list
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
            <form method="POST" action="<?php echo e(route('recruitments.store')); ?>" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>

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
                        <i class="fas fa-save me-1"></i> Save Recruitment
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['href' => ''.e(route('recruitments.index')).'','variant' => 'danger']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('recruitments.index')).'','variant' => 'danger']); ?>
                        <i class="fas fa-undo me-1"></i> Cancel
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

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\xampp\htdocs\manpower-erp-new\resources\views/recruitments/create.blade.php ENDPATH**/ ?>