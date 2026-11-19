<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Support\Menu;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Clearly identified development-status page for a planned module.
 *
 * This controller exists so the sidebar never contains a dead link and an
 * unfinished module is never presented as a working business feature. It
 * renders no CRUD forms and no action buttons. Permission is checked here
 * on the server, not only hidden in the menu.
 */
class ModuleStatusController extends Controller
{
    public function show(string $module): View|RedirectResponse
    {
        $definition = Menu::module($module);

        abort_if($definition === null, 404);

        $user = auth()->user();

        abort_if($user === null, 403);

        foreach ((array) ($definition['permission'] ?? []) as $permission) {
            abort_unless($user->can($permission), 403, 'You are not authorised to view this module.');
        }

        $workingModules = [
            'medical-centres' => 'medical-centres.index',
            'training-centres' => 'training-centres.index',
            'training-courses' => 'training-courses.index',
            'visa-lots' => 'visa-register.lots',
            'work-permits' => 'visa-register.work-permits',
            'appointments' => 'visa-register.appointments',
            'visa-documents' => 'visa-register.documents',
            'passport-received' => 'passport-received.index',
            'airlines' => 'airlines.index',
            'travel-agencies' => 'travel-agencies.index',
            'airport-pickup' => 'airport-pickup.index',
            'accommodation' => 'accommodation.index',
            'employer-information' => 'employer-information.index',
            'follow-ups' => 'follow-ups.index',
            'candidate-feedback' => 'candidate-feedback.index',
            'post-arrival-complaints' => 'post-arrival-complaints.index',
            'country-user-permissions' => 'country-user-permissions.index',
            'country-workflow-settings' => 'country-workflow-settings.index',
            'agent-documents' => 'agent-documents.index',
            'agent-payments' => 'reports.financial.sub-ledger.due-list',
            'passport-holder-documents' => 'passport-holder-documents.index',
            'passport-holder-payments' => 'reports.financial.sub-ledger.due-list',
            'agent-collections' => 'reports.financial.sub-ledger.due-list',
            'candidate-collections' => 'reports.financial.sub-ledger.due-list',
            'vendor-payments' => 'reports.financial.sub-ledger.due-list',
            'bank-accounts' => 'accounting.accounts.index',
            'bank-transfers' => 'accounting.vouchers.index',
            'bank-deposits' => 'accounting.vouchers.index',
            'refund-management' => 'accounting.vouchers.index',
            'advance-payments' => 'accounting.vouchers.index',
            'daily-expenses' => 'accounting.vouchers.index',
            'daily-income' => 'accounting.vouchers.index',
            'visitor-pass' => 'visitor-passes.index',
            'audit-logs' => 'audit-logs.index',
            'login-history' => 'login-history.index',
            'user-activity' => 'user-activity.index',
            'branch-user-permissions' => 'branch-user-permissions.index',
            'roles' => 'roles.index',
            'users' => 'users.index',
            'permissions' => 'permissions.index',
            'system-configuration' => 'configuration-modules.show',
            'pdf-settings' => 'configuration-modules.show',
            'invoice-settings' => 'configuration-modules.show',
            'email-settings' => 'configuration-modules.show',
            'notification-settings' => 'configuration-modules.show',
            'workflow-settings' => 'configuration-modules.show',
        ];

        if (isset($workingModules[$module])) {
            if (str_ends_with($workingModules[$module], '.show') && str_starts_with($workingModules[$module], 'configuration-modules.')) {
                return redirect()->route($workingModules[$module], ['module' => $module]);
            }
            $params = match ($module) {
                'agent-payments', 'agent-collections' => ['type' => 'agent'],
                'passport-holder-payments', 'candidate-collections' => ['type' => 'passport_holder'],
                'vendor-payments' => ['type' => 'vendor'],
                'bank-transfers', 'bank-deposits' => ['voucher_type' => 'contra'],
                'refund-management', 'advance-payments', 'daily-expenses' => ['voucher_type' => 'payment'],
                'daily-income' => ['voucher_type' => 'receipt'],
                default => [],
            };

            return redirect()->route($workingModules[$module], $params);
        }

        return view('modules.status', [
            'moduleKey' => $module,
            'module' => $definition,
        ]);
    }
}
