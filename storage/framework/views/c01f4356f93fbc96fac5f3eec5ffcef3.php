<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        @page {
            margin: 16px
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            color: #252a31
        }

        .card {
            border: 1px dotted #555;
            text-align: center;
            padding: 12px;
            height: 455px
        }

        .brand {
            font-size: 19px;
            font-weight: bold;
            margin-bottom: 12px
        }

        .photo {
            width: 190px;
            height: 225px;
            /* object-fit: cover; <-- DomPDF এর জন্য এটি সরানো হয়েছে */
            border: 3px solid #16a05d;
            margin: 0 auto 8px;
            display: block;
            /* ছবি সেন্টার করার জন্য */
        }

        .placeholder {
            height: 225px;
            width: 190px;
            margin: auto;
            border: 1px solid #aaa;
            color: #777;
            padding-top: 100px;
            box-sizing: border-box
        }

        .name {
            font-size: 20px;
            font-weight: bold;
            margin: 2px
        }

        .line {
            text-align: left;
            font-size: 16px;
            margin: 9px 20px
        }

        .value {
            font-weight: bold
        }

        .passport {
            color: #dc2626
        }

        .signature {
            text-align: right;
            margin: 30px 10px 0;
            border-top: 1px solid #333;
            padding-top: 5px;
            font-weight: bold;
            width: 145px;
            float: right
        }
    </style>
</head>

<body>
    <div class="card">
        <div class="brand"><?php echo e(strtoupper(config('app.name', 'RECRUITMENT'))); ?></div>

        
        <?php if(!empty($photoData)): ?>
            <img class="photo" src="<?php echo $photoData; ?>">
        <?php else: ?>
            <div class="placeholder">PHOTO</div>
        <?php endif; ?>

        <div class="name"><?php echo e($holder?->full_name ?? 'Candidate'); ?></div>
        <div class="line">Passport No: <span class="value passport"><?php echo e($holder?->passport_number ?? '—'); ?></span></div>
        <div class="line">Position: <span class="value"><?php echo e($recruitment->position?->title ?? '—'); ?></span></div>
        <div class="line">SL: <span
                class="value"><?php echo e(str_pad((string) ($holder?->id ?? $recruitment->id), 5, '0', STR_PAD_LEFT)); ?></span>
        </div>
        <div class="line">Ref: <?php echo e($holder?->referer ?? ($recruitment->agent?->name ?? '—')); ?></div>
        <div class="signature">Authorized Signature</div>
    </div>
</body>

</html>
<?php /**PATH R:\xampp\htdocs\manpower-erp-new\resources\views/recruitments/downloads/id-card.blade.php ENDPATH**/ ?>