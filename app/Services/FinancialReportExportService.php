<?php

namespace App\Services;

use App\Exports\FinancialReportExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

/**
 * PDF/Excel export for financial reports (Phase 25).
 * PDF uses barryvdh/laravel-dompdf; Excel uses maatwebsite/excel.
 * Both packages are part of the Phase 01 §3 stack and are installed
 * in Phase 02 — if either is missing, the export throws a clear
 * exception instead of rendering a dead button.
 */
class FinancialReportExportService
{
    public function exportPdf(string $title, string $view, array $data)
    {
        if (! class_exists(Pdf::class)) {
            throw new \RuntimeException('PDF export package (barryvdh/laravel-dompdf) is not installed.');
        }

        return Pdf::loadView($view, array_merge($data, ['report_title' => $title]))
            ->setPaper('a4', 'landscape')
            ->download($this->filename($title, 'pdf'));
    }

    public function exportExcel(string $title, array $headings, array $rows)
    {
        if (! class_exists(Excel::class)) {
            throw new \RuntimeException('Excel export package (maatwebsite/excel) is not installed.');
        }

        return Excel::download(new FinancialReportExport($title, $headings, $rows), $this->filename($title, 'xlsx'));
    }

    protected function filename(string $title, string $ext): string
    {
        $slug = preg_replace('/[^a-z0-9]+/i', '-', strtolower($title));

        return trim($slug, '-') . '-' . now()->format('Ymd') . '.' . $ext;
    }
}
