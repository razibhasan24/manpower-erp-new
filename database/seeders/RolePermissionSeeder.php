<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * Phase 04/05 — roles and permissions (Phase 01 sections 4 and 8).
 *
 * Exactly 7 roles. There is NO Sub-Agent role. Permissions are named
 * module.action. Super Admin receives every permission; every other role
 * receives only what is listed here. Branch/country scoping is enforced
 * separately by query scopes and Policies, never by these permissions.
 */
class RolePermissionSeeder extends Seeder
{
    /** @var array<string, string[]> module => actions */
    public const MODULE_ACTIONS = [
        'dashboard' => ['view'],
        'core' => ['view', 'manage'],
        'branch' => ['view', 'create', 'edit', 'delete', 'manage'],
        'user' => ['view', 'create', 'edit', 'delete', 'manage'],
        'role' => ['view', 'create', 'edit', 'delete', 'manage'],
        'setting' => ['view', 'edit', 'manage'],
        'company-profile' => ['view', 'edit', 'manage'],
        'backup' => ['view', 'create', 'download', 'delete', 'manage'],
        'agent' => ['view', 'create', 'edit', 'delete', 'print', 'export'],
        'passport-holder' => ['view', 'create', 'edit', 'delete', 'print', 'export', 'download'],
        'recruitment' => ['view', 'create', 'edit', 'delete', 'approve', 'reject', 'return', 'print', 'export'],
        'selection' => ['view', 'create', 'edit', 'approve', 'reject', 'return', 'download'],
        'delegation' => ['view', 'create', 'edit', 'approve', 'reject', 'return', 'download'],
        'medical' => ['view', 'create', 'edit', 'approve', 'reject', 'return', 'download'],
        'medical-centre' => ['view', 'create', 'edit', 'delete'],
        'police-clearance' => ['view', 'create', 'edit', 'approve', 'reject', 'return', 'download'],
        'training' => ['view', 'create', 'edit', 'approve', 'reject', 'return', 'download'],
        'training-centre' => ['view', 'create', 'edit', 'delete'],
        'training-course' => ['view', 'create', 'edit', 'delete'],
        'fingerprint' => ['view', 'create', 'edit', 'approve', 'reject', 'return', 'download'],
        'manpower-approval' => ['view', 'create', 'edit', 'approve', 'reject', 'return', 'download'],
        'visa' => ['view', 'create', 'edit', 'approve', 'reject', 'return', 'print', 'export', 'download'],
        'flight' => ['view', 'create', 'edit', 'approve', 'reject', 'return', 'download'],
        'passport-return' => ['view', 'create', 'edit', 'approve', 'reject', 'download'],
        'visa-return' => ['view', 'create', 'edit', 'approve', 'reject', 'download'],
        'post-arrival' => ['view', 'create', 'edit', 'approve', 'reject', 'download'],
        'accounting' => ['view', 'manage'],
        'account' => ['view', 'create', 'update', 'delete', 'manage'],
        'account_type' => ['view', 'create', 'update', 'delete', 'manage'],
        'voucher' => ['view', 'create', 'edit', 'reverse', 'approve', 'reject', 'print', 'export'],
        'ledger' => ['view', 'export', 'print'],
        'report' => ['view', 'export', 'print'],
        'hrm' => ['view', 'create', 'edit', 'delete', 'approve', 'reject'],
        'department' => ['view', 'create', 'update', 'delete'],
        'designation' => ['view', 'create', 'update', 'delete'],
        'employee' => ['view', 'create', 'update', 'delete'],
        'attendance' => ['view', 'create', 'update', 'delete'],
        'holiday' => ['view', 'create', 'update', 'delete'],
        'leave' => ['view', 'create', 'update', 'delete', 'approve', 'reject'],
        'salary_structure' => ['view', 'create', 'update', 'delete'],
        'payroll' => ['view', 'create', 'edit', 'pay', 'approve', 'reject', 'print', 'export'],
        'visitor' => ['view', 'create', 'edit', 'update', 'delete'],
        'audit-log' => ['view', 'export'],
        'document' => ['view', 'download', 'create', 'delete'],
    ];

    /**
     * Permissions each role receives. 'all' means every permission.
     * 'all-except' lists permission names the role does NOT receive.
     */
    public const ROLE_PERMISSIONS = [
        'Super Admin' => ['all'],
        'Admin' => ['all-except' => [
            'setting.manage', 'company-profile.manage', 'backup.create',
            'backup.delete', 'backup.manage', 'audit-log.view', 'audit-log.export',
            'role.delete',
        ]],
        'Branch Manager' => [
            'dashboard.view', 'branch.view',
            'agent.view', 'agent.create', 'agent.edit',
            'passport-holder.view', 'passport-holder.create', 'passport-holder.edit',
            'recruitment.view', 'recruitment.create', 'recruitment.edit',
            'recruitment.approve', 'recruitment.reject', 'recruitment.return',
            'selection.view', 'selection.approve', 'selection.reject', 'selection.return', 'selection.download',
            'delegation.view', 'delegation.approve', 'delegation.reject', 'delegation.return', 'delegation.download',
            'medical.view', 'medical.approve', 'medical.reject', 'medical.return', 'medical.download',
            'medical-centre.view', 'medical-centre.create', 'medical-centre.edit', 'medical-centre.delete',
            'police-clearance.view', 'police-clearance.approve', 'police-clearance.reject', 'police-clearance.return', 'police-clearance.download',
            'training.view', 'training.approve', 'training.reject', 'training.return', 'training.download',
            'training-centre.view', 'training-centre.create', 'training-centre.edit', 'training-centre.delete',
            'training-course.view', 'training-course.create', 'training-course.edit', 'training-course.delete',
            'fingerprint.view', 'fingerprint.approve', 'fingerprint.reject', 'fingerprint.return', 'fingerprint.download',
            'manpower-approval.view', 'manpower-approval.approve', 'manpower-approval.reject', 'manpower-approval.return', 'manpower-approval.download',
            'visa.view', 'visa.approve', 'visa.reject', 'visa.return', 'visa.download',
            'flight.view', 'flight.approve', 'flight.reject', 'flight.return', 'flight.download',
            'passport-return.view', 'passport-return.approve', 'passport-return.reject', 'passport-return.download',
            'visa-return.view', 'visa-return.approve', 'visa-return.reject', 'visa-return.download',
            'post-arrival.view', 'post-arrival.approve', 'post-arrival.reject', 'post-arrival.download',
            'report.view', 'voucher.view', 'ledger.view',
            'visitor.view', 'visitor.create', 'visitor.edit', 'visitor.update',
            'document.view', 'document.download',
        ],
        'Accounts Staff' => [
            'dashboard.view', 'accounting.view', 'accounting.manage',
            'account.view', 'account.create', 'account.update', 'account.delete',
            'account_type.view',
            'voucher.view', 'voucher.create', 'voucher.edit', 'voucher.reverse',
            'voucher.approve', 'voucher.reject', 'voucher.print', 'voucher.export',
            'ledger.view', 'ledger.export', 'ledger.print',
            'report.view', 'report.export', 'report.print',
            'recruitment.view', 'agent.view', 'passport-holder.view',
            'payroll.view', 'payroll.pay', 'payroll.approve',
            'document.view', 'document.download',
        ],
        'General Staff' => [
            'dashboard.view',
            'passport-holder.view', 'passport-holder.create',
            'recruitment.view', 'recruitment.create', 'recruitment.edit',
            'document.view', 'visitor.view', 'visitor.create',
        ],
        'Agent' => [
            // MERGE FIX 2026-11-13 (coordinator): 'voucher.view' REMOVED.
            // VoucherPolicy::view() allows anyone with voucher.view to view
            // ANY voucher, so granting it to Agents let Agent A view Agent
            // B's vouchers (mandatory isolation test 2, Phase 01 §8.3).
            // Agents see their own payments/ledger only, via
            // VoucherPolicy::viewSubLedger() gated by 'ledger.view'.
            'dashboard.view', 'agent.view',
            'passport-holder.view', 'passport-holder.create',
            'recruitment.view', 'document.view', 'document.download',
            'ledger.view', 'report.view',
        ],
        'Passport Holder' => [
            // MERGE FIX 2026-11-13 (coordinator): 'voucher.view' REMOVED
            // for the same reason — a Passport Holder must see only their
            // own ledger/payments via viewSubLedger(), never any voucher.
            'dashboard.view', 'passport-holder.view', 'recruitment.view',
            'document.view', 'document.download', 'ledger.view',
        ],
    ];

    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $all = [];
        foreach (self::MODULE_ACTIONS as $module => $actions) {
            foreach ($actions as $action) {
                $all[] = Permission::firstOrCreate(['name' => "{$module}.{$action}"]);
            }
        }

        $allNames = collect($all)->pluck('name')->all();

        foreach (self::ROLE_PERMISSIONS as $roleName => $spec) {
            $role = Role::firstOrCreate(['name' => $roleName]);

            if ($spec === ['all']) {
                $role->syncPermissions($allNames);
                continue;
            }

            if (isset($spec['all-except'])) {
                $role->syncPermissions(array_diff($allNames, $spec['all-except']));
                continue;
            }

            $role->syncPermissions($spec);
        }
    }
}
