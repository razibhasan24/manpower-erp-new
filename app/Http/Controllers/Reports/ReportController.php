<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reports\ReportFilterRequest;
use App\Services\ReportExportService;
use App\Services\ReportService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

/**
 * ReportController — Phase 28.
 *
 * Thin controller: validate filters → authorise (report.view + the
 * underlying module permission) → ReportService runs the report →
 * respond (screen / PDF / Excel / print). The SAME permission check
 * guards the screen view and every export (prompt §25).
 *
 * Report keys are the registry keys in ReportService::definitions().
 * Financial reports are Group D / Phase 25 and are not routed here.
 */
class ReportController extends Controller
{
    public function __construct(
        protected ReportService $reports,
        protected ReportExportService $exports,
    ) {}

    /**
     * Report index: grouped list of every report the user may open.
     */
    public function index()
    {
        abort_unless(auth()->user()->can('report.view'), 403);

        $definitions = collect(ReportService::definitions())
            ->filter(fn (array $d, string $key) => $this->mayView($key, $d))
            ->groupBy('group');

        return view('reports.index', ['groups' => $definitions]);
    }

    public function show(ReportFilterRequest $request, string $report)
    {
        $definition = ReportService::definition($report);
        $this->authorizeReport($report, $definition);

        $result = $this->reports->run($report, $request->user(), $request->validated());

        return view('reports.show', [
            'report' => $report,
            'title' => $definition['title'],
            'columns' => $result['columns'],
            'rows' => $result['rows'],
            'summary' => $result['summary'],
            'filters' => $result['filters'],
            'filterOptions' => $this->filterOptions($request->user()),
        ]);
    }

    public function pdf(ReportFilterRequest $request, string $report)
    {
        $definition = ReportService::definition($report);
        $this->authorizeExport($report, $definition);

        $result = $this->reports->run($report, $request->user(), $request->validated());
        $this->auditExport($request, $report, 'pdf');

        return $this->exports->pdf($definition['title'], $result['columns'], $result['rows'], $result['summary'], $result['filters']);
    }

    public function excel(ReportFilterRequest $request, string $report)
    {
        $definition = ReportService::definition($report);
        $this->authorizeExport($report, $definition);

        $result = $this->reports->run($report, $request->user(), $request->validated());
        $this->auditExport($request, $report, 'excel');

        return $this->exports->excel($definition['title'], $result['columns'], $result['rows'], $result['summary']);
    }

    public function print(ReportFilterRequest $request, string $report)
    {
        $definition = ReportService::definition($report);
        $this->authorizeReport($report, $definition);

        $result = $this->reports->run($report, $request->user(), $request->validated());

        return view('reports.print', $this->exports->printData(
            $definition['title'], $result['columns'], $result['rows'], $result['summary'], $result['filters']
        ));
    }

    // ------------------------------------------------------------------

    protected function mayView(string $key, array $definition): bool
    {
        $user = auth()->user();

        if ($user === null || ! $user->can('report.view') || ! $user->can($definition['permission'])) {
            return false;
        }

        // Company-wide agent dues are not a passport-holder report. The
        // holder uses the scoped candidate ledger in My Portal instead.
        if ($key === 'agent-due' && $user->hasRole('Passport Holder')) {
            return false;
        }

        return true;
    }

    protected function authorizeReport(string $report, array $definition): void
    {
        $user = auth()->user();

        abort_unless($user->can('report.view'), 403);
        abort_unless($user->can($definition['permission']), 403);

        // The due report exposes company-wide agent balances. A passport
        // holder has a scoped candidate ledger in My Portal instead.
        abort_if($report === 'agent-due' && $user->hasRole('Passport Holder'), 403);
    }

    protected function authorizeExport(string $report, array $definition): void
    {
        $this->authorizeReport($report, $definition);
        abort_unless(auth()->user()->can('report.export'), 403, 'You do not have permission to export reports.');
    }

    protected function auditExport(ReportFilterRequest $request, string $report, string $format): void
    {
        Log::info('Report exported', [
            'report' => $report,
            'format' => $format,
            'user_id' => $request->user()->id,
            'ip' => $request->ip(),
        ]);

        if (Schema::hasTable('audit_logs')) {
            \Illuminate\Support\Facades\DB::table('audit_logs')->insert([
                'user_id' => $request->user()->id,
                'action' => 'report.exported',
                'auditable_type' => 'report:'.$report,
                'auditable_id' => null,
                'old_values' => null,
                'new_values' => json_encode(['format' => $format, 'filters' => $request->validated()]),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'created_at' => now(),
            ]);
        }
    }

    protected function filterOptions(\App\Models\User $user): array
    {
        return [
            'countries' => \App\Models\Country::orderBy('name')->get(['id', 'name']),
            'branches' => \App\Models\Branch::orderBy('name')->get(['id', 'name']),
            'agents' => \App\Models\Agent::orderBy('name')->get(['id', 'name']),
        ];
    }
}
