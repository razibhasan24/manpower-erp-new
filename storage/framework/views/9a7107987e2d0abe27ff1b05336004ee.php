<?php ($holder = $recruitment->passportHolder ?? null); ?>
<?php ($serviceType = old('service_type', request('service_type', $recruitment->service_type ?? 'overseas'))); ?>

<div class="row g-3">
    
    <div class="col-12">
        <div class="bg-light rounded p-3 border mb-2">
            <h5 class="mb-3 text-primary"><i class="bi bi-briefcase me-2"></i> Registration Type & Package</h5>
            <?php if(!isset($recruitment)): ?>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Registration Type <span class="text-danger">*</span></label>
                        <select name="service_type" id="service_type" class="form-select" required>
                            <option value="overseas" <?php if($serviceType === 'overseas'): echo 'selected'; endif; ?>>Overseas Employment</option>
                            <option value="student" <?php if($serviceType === 'student'): echo 'selected'; endif; ?>>Student</option>
                            <option value="hajj_umrah" <?php if($serviceType === 'hajj_umrah'): echo 'selected'; endif; ?>>Hajj & Umrah</option>
                        </select>
                    </div>
                    <div class="col-md-4 package-field">
                        <label class="form-label fw-semibold">Package <span class="text-danger">*</span></label>
                        <select name="service_package_id" id="service_package_id" class="form-select">
                            <option value="">Select Package</option>
                            <?php $__currentLoopData = $packages ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $package): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($package->id); ?>" data-type="<?php echo e($package->type); ?>"
                                    data-price="<?php echo e($package->price); ?>" data-currency="<?php echo e($package->currency); ?>"
                                    <?php if(old('service_package_id', '') == $package->id): echo 'selected'; endif; ?>>
                                    <?php echo e($package->name); ?> (<?php echo e($package->code); ?>) · <?php echo e($package->currency); ?>

                                    <?php echo e(number_format((float) $package->price, 2)); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-4 package-field">
                        <label class="form-label fw-semibold">Quoted Cost / Registration Due</label>
                        <input id="package_quote" class="form-control bg-white" readonly
                            placeholder="Auto calculated...">
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-info mb-0 border-0 d-flex align-items-center">
                    <i class="bi bi-info-circle-fill me-2 fs-5"></i>
                    <div>
                        <strong>Registration Type:</strong> <?php echo e(ucfirst(str_replace('_', ' ', $serviceType))); ?>

                        <?php if($recruitment->servicePackage): ?>
                            · <strong>Package:</strong> <?php echo e($recruitment->servicePackage->name); ?>

                            (<?php echo e($recruitment->quoted_currency); ?>

                            <?php echo e(number_format((float) $recruitment->quoted_amount, 2)); ?>)
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    
    <div class="col-12">
        <h5 class="section-heading animated-red-border">
            <i class="bi bi-person me-2"></i>
            Personal Information
        </h5>
    </div>

    <div class="col-md-3">
        <label class="form-label fw-semibold">SL</label>
        <input class="form-control bg-light" value="<?php echo e($holder->id ?? ($nextPassportHolderId ?? 'Auto generated')); ?>"
            readonly>
    </div>
    <?php if(isset($recruitment)): ?>
        <input type="hidden" name="passport_holder_id" value="<?php echo e($holder->id); ?>">
    <?php endif; ?>

    <?php $__currentLoopData = ['passport_number' => 'Passport No.', 'nid' => 'NID No.', 'full_name' => 'Full Name', 'father_name' => "Father's Name", 'mother_name' => "Mother's Name"]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $name => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-md-3">
            <label class="form-label fw-semibold"><?php echo e($label); ?> <?php if(in_array($name, ['passport_number', 'full_name'])): ?>
                    <span class="text-danger">*</span>
                <?php endif; ?>
            </label>
            <input name="<?php echo e($name); ?>" class="form-control <?php $__errorArgs = [$name];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                value="<?php echo e(old($name, $holder->$name ?? '')); ?>" <?php if(in_array($name, ['passport_number', 'full_name'])): ?> required <?php endif; ?>>
            <?php $__errorArgs = [$name];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <div class="col-md-3">
        <label class="form-label fw-semibold">Agent <span id="agent-required" class="text-danger">*</span></label>
        <select name="agent_id" id="agent_id" class="form-select">
            <option value="">No Agent (charge passport holder)</option>
            <?php $__currentLoopData = $agents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($a->id); ?>" <?php if(old('agent_id', $recruitment->agent_id ?? ($holder->agent_id ?? '')) == $a->id): echo 'selected'; endif; ?>><?php echo e($a->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <div class="form-text small text-muted">If an agent is selected, package due is recorded under that agent.</div>
    </div>

    <div class="col-md-3">
        <label class="form-label fw-semibold">Referer</label>
        <input name="referer" class="form-control" value="<?php echo e(old('referer', $holder->referer ?? '')); ?>">
    </div>

    <?php $__currentLoopData = [['country_id', 'Country', $countries, 'name'], ['company_id', 'Company', $companies, 'name'], ['position_id', 'Position', $positions, 'title'], ['branch_id', 'Branch', $branches, 'name']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$name, $label, $items, $display]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-md-3">
            <label class="form-label fw-semibold"><?php echo e($label); ?> <span
                    class="overseas-required text-danger">*</span></label>
            <select name="<?php echo e($name); ?>" class="form-select <?php $__errorArgs = [$name];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                <?php if($name === 'branch_id' || $name === 'country_id' || $serviceType === 'overseas'): echo 'required'; endif; ?>>
                <option value="">Select <?php echo e($label); ?></option>
                <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($item->id); ?>" <?php if(old($name, $recruitment->$name ?? ($holder->$name ?? '')) == $item->id): echo 'selected'; endif; ?>><?php echo e($item->$display); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <?php $__errorArgs = [$name];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <?php $__currentLoopData = ['phone' => 'Mobile Number', 'email' => 'E-mail', 'gender' => 'Gender', 'religion' => 'Religion', 'marital_status' => 'Marital Status', 'passport_issue_date' => 'Passport Issue Date', 'passport_expiry_date' => 'Passport Expiry Date', 'dob' => 'Date of Birth', 'height' => 'Height', 'weight' => 'Weight', 'village_road' => 'Village/Road/Moholla', 'post_office' => 'P.O.', 'police_station' => 'P.S.', 'district' => 'District', 'present_address' => 'Present Address']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $name => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-md-3">
            <label class="form-label fw-semibold"><?php echo e($label); ?> <?php if($name === 'phone' && !isset($recruitment)): ?>
                    <span class="text-danger">*</span>
                <?php endif; ?>
            </label>

            <?php if($name === 'gender'): ?>
                <select name="gender" class="form-select <?php $__errorArgs = [$name];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <option value="">Select Gender</option>
                    <?php $__currentLoopData = ['male' => 'Male', 'female' => 'Female', 'other' => 'Other']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v => $txt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($v); ?>" <?php if(old($name, $holder->gender ?? '') == $v): echo 'selected'; endif; ?>><?php echo e($txt); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            <?php elseif($name === 'marital_status'): ?>
                <select name="marital_status" class="form-select <?php $__errorArgs = [$name];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <option value="">Select Status</option>
                    <?php $__currentLoopData = ['single', 'married', 'divorced', 'widowed']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($v); ?>" <?php if(old($name, $holder->marital_status ?? '') == $v): echo 'selected'; endif; ?>><?php echo e(ucfirst($v)); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            <?php else: ?>
                <input name="<?php echo e($name); ?>"
                    type="<?php echo e(in_array($name, ['dob', 'passport_issue_date', 'passport_expiry_date']) ? 'date' : 'text'); ?>"
                    class="form-control <?php $__errorArgs = [$name];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                    value="<?php echo e(old($name, isset($holder->$name) ? (in_array($name, ['dob', 'passport_issue_date', 'passport_expiry_date']) ? $holder->$name?->format('Y-m-d') : $holder->$name) : '')); ?>"
                    <?php if($name === 'phone' && !isset($recruitment)): ?> required <?php endif; ?> <?php if($name === 'passport_expiry_date'): ?> readonly <?php endif; ?>>
            <?php endif; ?>
            <?php $__errorArgs = [$name];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <div class="col-md-3">
        <label class="form-label fw-semibold">Passport Validity</label>
        <select id="passport_validity" class="form-select">
            <option value="5">5 Years</option>
            <option value="10" selected>10 Years</option>
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label fw-semibold">Age</label>
        <input id="age_display" class="form-control bg-light" readonly value="0 Years 0 Months 0 Days">
    </div>

    
    <div class="col-12">
        <h5 class="section-heading animated-red-border"><i class="bi bi-file-earmark-arrow-up me-2"></i>
            Documents Uploads</h5>
    </div>

    <?php $__currentLoopData = ['photo' => 'Photo', 'passport_scan' => 'Passport Image', 'nid_scan' => 'NID Image']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-md-4">
            <label class="form-label fw-semibold"><?php echo e($label); ?></label>
            <input type="file" name="documents[<?php echo e($type); ?>]"
                class="form-control <?php $__errorArgs = ['documents.' . $type];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" accept=".pdf,.jpg,.jpeg,.png">
            <?php $__errorArgs = ['documents.' . $type];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <div class="col-12 mt-3">
        <label class="form-label fw-semibold">Remarks</label>
        <textarea name="remarks" class="form-control" rows="2" placeholder="Any additional notes..."><?php echo e(old('remarks', $recruitment->remarks ?? '')); ?></textarea>
    </div>

    
    <div class="col-12">
        <h5 class="section-heading animated-red-border"><i class="bi bi-translate me-2"></i> Language
            Proficiency</h5>
    </div>

    <?php $__currentLoopData = ['Understanding Bengali', 'Speaking in Bengali', 'Writing in Bengali', 'Understanding English', 'Speaking in English', 'Writing in English', 'Understanding Other Language']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-md-4">
            <label class="form-label text-muted small mb-1"><?php echo e($language); ?></label>
            <select name="language_proficiency[<?php echo e($i); ?>]" class="form-select form-select-sm">
                <?php $__currentLoopData = ['No', 'Low', 'Medium', 'High']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $level): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option <?php if(old("language_proficiency.$i", 'Low') === $level): echo 'selected'; endif; ?>><?php echo e($level); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    
    <div class="col-12">
        <h5 class="section-heading animated-red-border"><i class="bi bi-mortarboard me-2"></i> Experience &
            Education</h5>
    </div>

    <div class="col-md-3">
        <label class="form-label fw-semibold">Local Experience in Years</label>
        <input type="number" min="0" name="local_experience_years" class="form-control"
            value="<?php echo e(old('local_experience_years', $recruitment->local_experience_years ?? 3)); ?>">
    </div>
    <div class="col-md-3">
        <label class="form-label fw-semibold">Education Type</label>
        <select name="education_type" class="form-select">
            <option>Basic/Technical</option>
            <option>Special Training if any</option>
        </select>
    </div>

    <?php $__currentLoopData = ['education_degree' => 'Degree/Certificate', 'education_school' => 'School/Board/University', 'education_grade' => 'Division/CGPA', 'education_year' => 'Year']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $name => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-md-3">
            <label class="form-label fw-semibold"><?php echo e($label); ?></label>
            <input name="<?php echo e($name); ?>" class="form-control"
                value="<?php echo e(old($name, $recruitment->$name ?? '')); ?>">
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<script>
    // ✅ Passport Expiry & Age Calculation Logic
    (() => {
        const issue = document.querySelector('[name="passport_issue_date"]'),
            expiry = document.querySelector('[name="passport_expiry_date"]'),
            valid = document.getElementById('passport_validity'),
            dob = document.querySelector('[name="dob"]'),
            age = document.getElementById('age_display');

        function syncExpiry() {
            if (!issue.value) return;
            const d = new Date(issue.value + 'T00:00:00');
            d.setFullYear(d.getFullYear() + Number(valid.value));
            expiry.value = d.toISOString().slice(0, 10);
        }

        function syncAge() {
            if (!dob.value) {
                age.value = '0 Years 0 Months 0 Days';
                return;
            }
            let a = new Date(dob.value + 'T00:00:00'),
                b = new Date(),
                y = b.getFullYear() - a.getFullYear(),
                m = b.getMonth() - a.getMonth(),
                d = b.getDate() - a.getDate();
            if (d < 0) {
                m--;
                d += new Date(b.getFullYear(), b.getMonth(), 0).getDate();
            }
            if (m < 0) {
                y--;
                m += 12;
            }
            age.value = `${y} Years ${m} Months ${d} Days`;
        }

        if (issue && valid) {
            issue.addEventListener('change', syncExpiry);
            valid.addEventListener('change', syncExpiry);
        }
        if (dob) {
            dob.addEventListener('change', syncAge);
            syncAge();
        }
    })();

    // ✅ Package & Registration Type Toggle Logic
    (() => {
        const type = document.getElementById('service_type'),
            packageSelect = document.getElementById('service_package_id'),
            quote = document.getElementById('package_quote'),
            agent = document.getElementById('agent_id');

        if (!type || !packageSelect) return;

        function sync() {
            const packageMode = type.value !== 'overseas';

            document.querySelectorAll('.package-field').forEach(el => el.classList.toggle('d-none', !packageMode));
            packageSelect.required = packageMode;
            agent.required = type.value === 'overseas';
            document.getElementById('agent-required').classList.toggle('d-none', !agent.required);

            document.querySelectorAll('.overseas-required').forEach(el => {
                el.classList.toggle('d-none', type.value !== 'overseas' && !['branch_id', 'country_id']
                    .includes(el.closest('.col-md-3')?.querySelector('select')?.name));
            });

            [...packageSelect.options].forEach(option => {
                if (!option.value) return;
                option.hidden = option.dataset.type !== type.value;
            });

            if (packageSelect.selectedOptions[0]?.dataset.type !== type.value) packageSelect.value = '';

            const option = packageSelect.selectedOptions[0];
            quote.value = option ? `${option.dataset.currency} ${Number(option.dataset.price).toFixed(2)}` : '';
        }

        type.addEventListener('change', sync);
        packageSelect.addEventListener('change', sync);
        sync();
    })();
</script>
<?php /**PATH R:\xampp\htdocs\manpower-erp-new\resources\views/recruitments/_form.blade.php ENDPATH**/ ?>