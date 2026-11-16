<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

/**
 * Excel export for financial reports (Phase 25).
 * Requires maatwebsite/excel (installed in Phase 02 per Phase 01 §3;
 * Group E owns the general export plumbing — this class is the
 * report-specific export that calls it).
 */
class FinancialReportExport implements FromArray, WithHeadings, WithTitle
{
    public function __construct(
        protected string $title,
        protected array $headings,
        protected array $rows
    ) {}

    public function array(): array
    {
        return $this->rows;
    }

    public function headings(): array
    {
        return $this->headings;
    }

    public function title(): string
    {
        return mb_substr($this->title, 0, 31);
    }
}
