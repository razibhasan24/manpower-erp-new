<?php

namespace App\Services;

use App\Exports\ReportExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

/**
 * ReportExportService — Phase 28.
 *
 * The single place that turns a report result (columns + rows + summary,
 * produced by ReportService) into a downloadable PDF, Excel workbook, or
 * print view. Screen, PDF, Excel and print all render from the SAME rows,
 * so an export can never disagree with what the user saw on screen.
 *
 * Packages (pinned in Phase 01 §3, to be installed by `composer install`
 * on the deployment machine — see docs/DEPLOYMENT.md):
 *   barryvdh/laravel-dompdf  (PDF)
 *   maatwebsite/excel        (Excel)
 *
 * Every export is audit-logged by the calling controller, and the caller
 * must have already passed the same permission check as the on-screen
 * report (prompt §25: "Enforce permissions on exported reports too").
 */
class ReportExportService
{
    /**
     * Download a report as PDF.
     */
    public function pdf(string $title, array $columns, array $rows, array $summary = [], array $filters = []): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $pdf = Pdf::loadView('reports.pdf', [
            'title' => $title,
            'columns' => $columns,
            'rows' => $rows,
            'summary' => $summary,
            'filters' => $filters,
            'generatedAt' => now()->format('d M Y, h:i A'),
            'company' => $this->companyBranding(),
        ])->setPaper('a4', 'landscape');

        $filename = $this->filename($title, 'pdf');

        Log::info('Report exported as PDF', ['title' => $title, 'rows' => count($rows)]);

        return $pdf->download($filename);
    }

    /**
     * Download a report as an Excel workbook (.xlsx).
     */
    public function excel(string $title, array $columns, array $rows, array $summary = []): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $filename = $this->filename($title, 'xlsx');

        Log::info('Report exported as Excel', ['title' => $title, 'rows' => count($rows)]);

        return Excel::download(new ReportExport($title, $columns, $rows, $summary), $filename);
    }

    /**
     * Data for the printable HTML view (reports.print).
     */
    public function printData(string $title, array $columns, array $rows, array $summary = [], array $filters = []): array
    {
        return [
            'title' => $title,
            'columns' => $columns,
            'rows' => $rows,
            'summary' => $summary,
            'filters' => $filters,
            'generatedAt' => now()->format('d M Y, h:i A'),
            'company' => $this->companyBranding(),
        ];
    }

    protected function filename(string $title, string $extension): string
    {
        $slug = \Illuminate\Support\Str::slug($title);

        return $slug.'-'.now()->format('Y-m-d-His').'.'.$extension;
    }

    /**
     * Branding comes from settings / company profile (Phase 01 §9).
     * Nothing is hard-coded: if no profile exists yet, generic labels are
     * used so the export still works.
     */
    protected function companyBranding(): array
    {
        if (! \Illuminate\Support\Facades\Schema::hasTable('company_profiles')) {
            return ['name' => config('app.name'), 'address' => '', 'phone' => '', 'email' => ''];
        }

        $profile = \App\Models\CompanyProfile::query()->where('is_active', true)->first()
            ?? \App\Models\CompanyProfile::query()->first();

        if (! $profile) {
            return ['name' => config('app.name'), 'address' => '', 'phone' => '', 'email' => ''];
        }

        return [
            'name' => $profile->name,
            'address' => $profile->address ?? '',
            'phone' => $profile->phone ?? '',
            'email' => $profile->email ?? '',
        ];
    }
}
