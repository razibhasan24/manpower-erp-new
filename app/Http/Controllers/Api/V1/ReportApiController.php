<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Reports\ReportFilterRequest;
use App\Services\ReportService;
use Illuminate\Http\Request;

/**
 * Reports API (Phase 30). Read-only JSON access to the same Phase 28
 * reports the web UI shows, with the same permission check and the
 * same scoped rows. Exports (PDF/Excel) stay on the web UI.
 */
class ReportApiController extends ApiController
{
    public function index()
    {
        abort_unless(auth()->user()->can('report.view'), 403);

        $user = auth()->user();

        $definitions = collect(ReportService::definitions())
            ->filter(fn (array $d, string $key) => $user->can($d['permission'])
                && ! ($key === 'agent-due' && $user->hasRole('Passport Holder')))
            ->map(fn (array $d, string $key) => [
                'key' => $key,
                'title' => $d['title'],
                'group' => $d['group'],
            ])->values();

        return $this->success($definitions);
    }

    public function show(ReportFilterRequest $request, string $report, ReportService $service)
    {
        $definition = ReportService::definition($report);

        abort_unless($request->user()->can('report.view'), 403);
        abort_unless($request->user()->can($definition['permission']), 403);
        abort_if($report === 'agent-due' && $request->user()->hasRole('Passport Holder'), 403);

        $result = $service->run($report, $request->user(), $request->validated());

        return $this->success([
            'report' => $report,
            'title' => $definition['title'],
            'columns' => $result['columns'],
            'rows' => $result['rows'],
            'summary' => $result['summary'],
            'filters' => $result['filters'],
        ]);
    }
}
