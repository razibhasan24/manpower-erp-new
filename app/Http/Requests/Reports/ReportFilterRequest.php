<?php

namespace App\Http\Requests\Reports;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Shared filters for every Phase 28 report (prompt §25):
 * date range, country, branch, agent, status, free-text search.
 * All filters are optional; invalid values are rejected, never ignored.
 */
class ReportFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('report.view');
    }

    public function rules(): array
    {
        return [
            'date_from' => ['nullable', 'date', 'before_or_equal:date_to'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
            'country_id' => ['nullable', 'integer', 'exists:countries,id'],
            'branch_id' => ['nullable', 'integer', 'exists:branches,id'],
            'agent_id' => ['nullable', 'integer', 'exists:agents,id'],
            'status' => ['nullable', 'string', 'max:50'],
            'search' => ['nullable', 'string', 'max:100'],
        ];
    }
}
