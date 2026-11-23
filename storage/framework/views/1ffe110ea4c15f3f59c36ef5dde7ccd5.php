<?php if (! $__env->hasRenderedOnce('98c27bdd-ffd7-4e58-8abb-c826fd1f1514')): $__env->markAsRenderedOnce('98c27bdd-ffd7-4e58-8abb-c826fd1f1514'); ?>
    <?php $__env->startPush('styles'); ?>
        <style>
            /* Main Button */
            .btn-modern {
                position: relative;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 7px;

                padding: 9px 18px;
                border: 1px solid transparent;
                border-radius: 8px !important;

                font-size: 13px;
                font-weight: 700;
                letter-spacing: .2px;

                text-decoration: none !important;
                cursor: pointer;
                overflow: hidden;

                transition: all .25s ease-in-out;
            }

            /* Hover Animation */
            .btn-modern:hover {
                transform: translateY(-3px);
                box-shadow: 0 5px 14px rgba(0, 0, 0, .16);
                filter: brightness(.95);
            }

            /* Click Animation */
            .btn-modern:active {
                transform: translateY(0) scale(.96);
                box-shadow: none;
            }

            /* Focus Animation */
            .btn-modern:focus-visible {
                outline: 3px solid rgba(37, 99, 235, .25);
                outline-offset: 3px;
            }

            /* PRIMARY */
            .btn-modern.btn-primary {
                background: #2563eb;
                border-color: #2563eb;
                color: #fff;
            }

            /* SUCCESS */
            .btn-modern.btn-success {
                background: #16a34a;
                border-color: #16a34a;
                color: #fff;
            }

            /* DANGER */
            .btn-modern.btn-danger {
                background: #dc2626;
                border-color: #dc2626;
                color: #fff;
            }

            /* WARNING */
            .btn-modern.btn-warning {
                background: #f59e0b;
                border-color: #f59e0b;
                color: #fff;
            }

            /* INFO */
            .btn-modern.btn-info {
                background: #0891b2;
                border-color: #0891b2;
                color: #fff;
            }

            /* SECONDARY */
            .btn-modern.btn-secondary {
                background: #64748b;
                border-color: #64748b;
                color: #fff;
            }

            /* DARK */
            .btn-modern.btn-dark {
                background: #1e293b;
                border-color: #1e293b;
                color: #fff;
            }

            /* LIGHT */
            .btn-modern.btn-light {
                background: #f1f5f9;
                border-color: #e2e8f0;
                color: #334155;
            }

            /* OUTLINE BUTTONS */
            .btn-modern[class*="btn-outline-"] {
                background: transparent;
            }

            .btn-modern.btn-outline-primary {
                color: #2563eb;
                border-color: #2563eb;
            }

            .btn-modern.btn-outline-success {
                color: #16a34a;
                border-color: #16a34a;
            }

            .btn-modern.btn-outline-danger {
                color: #dc2626;
                border-color: #dc2626;
            }

            .btn-modern.btn-outline-warning {
                color: #d97706;
                border-color: #d97706;
            }

            .btn-modern.btn-outline-info {
                color: #0891b2;
                border-color: #0891b2;
            }

            .btn-modern.btn-outline-secondary {
                color: #64748b;
                border-color: #64748b;
            }

            .btn-modern.btn-outline-dark {
                color: #1e293b;
                border-color: #1e293b;
            }

            /* Outline Hover */
            .btn-modern.btn-outline-primary:hover,
            .btn-modern.btn-outline-success:hover,
            .btn-modern.btn-outline-danger:hover,
            .btn-modern.btn-outline-warning:hover,
            .btn-modern.btn-outline-info:hover,
            .btn-modern.btn-outline-secondary:hover,
            .btn-modern.btn-outline-dark:hover {
                color: #fff;
            }

            /* SMALL BUTTON */
            .btn-modern.btn-sm {
                padding: 6px 12px;
                font-size: 12px;
                border-radius: 7px !important;
            }

            /* LARGE BUTTON */
            .btn-modern.btn-lg {
                padding: 12px 24px;
                font-size: 15px;
                border-radius: 10px !important;
            }

            /* DISABLED BUTTON */
            .btn-modern:disabled,
            .btn-modern.disabled {
                opacity: .55;
                cursor: not-allowed;
                transform: none !important;
                box-shadow: none !important;
                filter: none !important;
                pointer-events: none;
            }

            /* BUTTON ICON */
            .btn-modern i {
                transition: transform .25s ease;
            }

            .btn-modern:hover i {
                transform: scale(1.12);
            }

            /* REDUCED MOTION */
            @media (prefers-reduced-motion: reduce) {

                .btn-modern,
                .btn-modern i {
                    transition: none;
                }

                .btn-modern:hover,
                .btn-modern:active {
                    transform: none;
                }
            }

            /* MOBILE RESPONSIVE */
            @media (max-width: 576px) {
                .btn-modern {
                    padding: 8px 13px;
                    font-size: 12px;
                }

                .btn-modern.btn-lg {
                    padding: 10px 18px;
                }
            }
        </style>
    <?php $__env->stopPush(); ?>
<?php endif; ?>




<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'type' => 'submit',
    'variant' => 'warning',
    'href' => null,
    'size' => null,
    'confirm' => null,
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'type' => 'submit',
    'variant' => 'warning',
    'href' => null,
    'size' => null,
    'confirm' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $classes = 'btn btn-modern btn-' . $variant . ($size ? ' btn-' . $size : '');

    $confirmAttribute = $confirm ? ['onclick' => 'return confirm(' . json_encode($confirm) . ')'] : [];
?>

<?php if($href): ?>
    <a href="<?php echo e($href); ?>" <?php echo e($attributes->merge(['class' => $classes])->merge($confirmAttribute)); ?>>
        <?php echo e($slot); ?>

    </a>
<?php else: ?>
    <button type="<?php echo e($type); ?>" <?php echo e($attributes->merge(['class' => $classes])->merge($confirmAttribute)); ?>>
        <?php echo e($slot); ?>

    </button>
<?php endif; ?>
<?php /**PATH R:\xampp\htdocs\manpower-erp-new\resources\views/components/button.blade.php ENDPATH**/ ?>