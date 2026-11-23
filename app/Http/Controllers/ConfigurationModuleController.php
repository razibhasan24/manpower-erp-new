<?php

namespace App\Http\Controllers;

use App\Models\CompanyProfile;
use App\Models\Setting;
use App\Services\HeaderNotificationService;
use App\Services\WorkflowService;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ConfigurationModuleController extends Controller
{
    private const MODULES = [
        'system-configuration' => 'System Configuration',
        'pdf-settings' => 'PDF Settings',
        'invoice-settings' => 'Invoice Settings',
        'email-settings' => 'Email Settings',
        'notification-settings' => 'Notification Settings',
        'workflow-settings' => 'Workflow Settings',
    ];

    public function show(Request $request, string $module, HeaderNotificationService $notifications): View
    {
        abort_unless(isset(self::MODULES[$module]), 404);
        abort_unless($request->user()->can('setting.edit'), 403);

        $details = match ($module) {
            'system-configuration' => [
                'items' => [
                    __('Application environment') => app()->environment(),
                    __('Application timezone') => config('app.timezone'),
                    __('Database driver') => config('database.default'),
                    __('Queue driver') => config('queue.default'),
                    __('Cache driver') => config('cache.default'),
                    __('Session driver') => config('session.driver'),
                    __('Configuration cached') => app()->configurationIsCached() ? __('Yes') : __('No'),
                    __('Maintenance mode') => app()->isDownForMaintenance() ? __('Enabled') : __('Disabled'),
                ],
                'notice' => __('Environment secrets and database credentials are not displayed here.'),
            ],
            'pdf-settings' => [
                'items' => [
                    __('PDF engine available') => class_exists(\Barryvdh\DomPDF\Facade\Pdf::class) ? __('Yes') : __('No'),
                    __('Default paper size') => config('dompdf.options.default_paper_size'),
                    __('Default orientation') => config('dompdf.options.default_paper_orientation'),
                    __('Default font') => config('dompdf.options.default_font'),
                    __('Remote assets') => config('dompdf.options.enable_remote') ? __('Enabled') : __('Disabled'),
                    __('PHP in templates') => config('dompdf.options.enable_php') ? __('Enabled') : __('Disabled'),
                ],
                'notice' => __('PDF layout preferences currently come from application configuration; there is no editable PDF settings form.'),
            ],
            'email-settings' => [
                'items' => [
                    __('Mail transport') => config('mail.default'),
                    __('SMTP host') => config('mail.mailers.smtp.host') ?: '—',
                    __('SMTP port') => config('mail.mailers.smtp.port') ?: '—',
                    __('Encryption') => config('mail.mailers.smtp.encryption') ?: '—',
                    __('From address') => config('mail.from.address') ?: '—',
                    __('From name') => config('mail.from.name') ?: '—',
                ],
                'notice' => __('Mail settings are read from environment configuration. Passwords and credentials are never shown on this page.'),
            ],
            'invoice-settings' => $this->invoiceDetails(),
            'notification-settings' => [
                'items' => [
                    __('SMS driver') => config('services.sms.driver'),
                    __('SMS sender ID') => config('services.sms.sender') ?: '—',
                    __('SMS gateway endpoint') => config('services.sms.endpoint') ? parse_url(config('services.sms.endpoint'), PHP_URL_HOST) : '—',
                    __('Automatic workflow SMS') => config('services.sms.driver') === 'http' && config('services.sms.endpoint') ? __('Enabled') : __('Not connected'),
                ],
                'notifications' => $notifications->for($request->user()),
                'notice' => __('SMS is sent to the phone entered on registration for every workflow status change, and to the agent phone when an agent account is created. Configure SMS_HTTP_ENDPOINT, SMS_HTTP_TOKEN and SMS_SENDER_ID in .env for your gateway, then clear the config cache. SMS credentials are not displayed here.'),
            ],
            'workflow-settings' => [
                'stages' => WorkflowService::STAGES,
                'stageStatuses' => WorkflowService::STAGE_STATUSES,
                'notice' => __('The recruitment workflow currently uses the same 15 stages and rules for all countries.'),
            ],
        };

        return view('configuration-modules.show', [
            'moduleKey' => $module,
            'title' => __(self::MODULES[$module]),
            'details' => $details,
        ]);
    }

    private function invoiceDetails(): array
    {
        try {
            $settings = Schema::hasTable('settings')
                ? Setting::whereIn('key', ['invoice_prefix', 'voucher_prefix'])->pluck('value', 'key')
                : collect();
            $profile = Schema::hasTable('company_profiles')
                ? CompanyProfile::where('is_active', true)->first()
                : null;
        } catch (\Throwable $exception) {
            report($exception);
            $settings = collect();
            $profile = null;
        }

        return [
            'items' => [
                __('Invoice prefix') => $settings->get('invoice_prefix', $profile?->invoice_prefix ?? 'INV'),
                __('Voucher prefix') => $settings->get('voucher_prefix', 'VCH'),
                __('Company profile prefix') => $profile?->invoice_prefix ?? '—',
            ],
            'notice' => __('Invoice and voucher prefixes are managed through general settings and the company profile.'),
        ];
    }
}
