<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

/**
 * ReportExport — Phase 28.
 * One generic Excel export used by every report: the caller passes the
 * report title, the column labels (in display order) and the rows
 * (plain arrays keyed by the same column keys). A summary block is
 * appended below the data when provided.
 */
class ReportExport implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize
{
    public function __construct(
        protected string $title,
        protected array $columns,
        protected array $rows,
        protected array $summary = [],
    ) {}

    public function collection(): Collection
    {
        $keys = array_keys($this->columns);

        $data = collect($this->rows)->map(fn (array $row) => array_map(
            fn (string $key) => $row[$key] ?? '',
            $keys,
        ));

        if (! empty($this->summary)) {
            $data->push([]);
            $data->push(['Summary']);
            foreach ($this->summary as $label => $value) {
                $data->push([$label, is_array($value) ? json_encode($value) : (string) $value]);
            }
        }

        return $data;
    }

    public function headings(): array
    {
        return array_values($this->columns);
    }

    public function title(): string
    {
        return mb_substr($this->title, 0, 31);
    }
}
