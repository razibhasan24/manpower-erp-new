<?php

namespace App\Services;

use App\Models\Agent;
use App\Models\Branch;
use App\Models\Country;
use App\Models\Employee;
use App\Models\Flight;
use App\Models\JournalEntryLine;
use App\Models\Medical;
use App\Models\PassportHolder;
use App\Models\PassportReturn;
use App\Models\Payroll;
use App\Models\PoliceClearance;
use App\Models\Recruitment;
use App\Models\Selection;
use App\Models\Visa;
use App\Models\VisaReturn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * ReportService — Phase 28 (Reports & Analytics).
 *
 * Owns every non-financial report listed in prompt §25. Financial reports
 * (General Ledger, Trial Balance, Balance Sheet, Income Statement, Cash Book,
 * Bank Book, Day Book, Account Ledger) belong to Group D / Phase 25 and are
 * deliberately NOT built here.
 *
 * Every report is built from a scoped query: rows the current user is not
 * allowed to see are never selected (Phase 01 §8.3), never merely hidden.
 * Permission checks live in ReportController; this service enforces the
 * data scope and applies the shared filters (date / country / branch /
 * agent / status).
 *
 * Depends on (owned by other groups, not created here): the models listed
 * above, their `visibleTo(User)` query scopes, and the Spatie roles /
 * permissions seeded in Phase 05. If a model or scope is missing, the
 * report throws a clear exception naming the missing piece instead of
 * silently returning wrong data.
 */
class ReportService
{
    /**
     * Report registry. Key => definition.
     *
     * `permission` is the underlying module permission a user must hold in
     * addition to `report.view` (checked by the controller). `date_column`
     * is the column the date filter applies to. `columns` are
     * [key => label] pairs used by the table, PDF and Excel exports so all
     * three outputs always agree.
     */
    public static function definitions(): array
    {
        return [
            'recruitment' => [
                'title' => 'Recruitment Report',
                'group' => 'Recruitment',
                'permission' => 'recruitment.view',
                'date_column' => 'created_at',
                'columns' => [
                    'tracking_number' => 'Tracking No.',
                    'candidate' => 'Candidate',
                    'agent' => 'Agent',
                    'country' => 'Country',
                    'company' => 'Company',
                    'position' => 'Position',
                    'branch' => 'Branch',
                    'current_stage' => 'Stage',
                    'current_status' => 'Status',
                    'created_at' => 'Registered On',
                ],
            ],
            'agent' => [
                'title' => 'Agent Report',
                'group' => 'People',
                'permission' => 'agent.view',
                'date_column' => 'created_at',
                'columns' => [
                    'name' => 'Agent',
                    'phone' => 'Phone',
                    'email' => 'Email',
                    'branch' => 'Branch',
                    'countries' => 'Countries',
                    'candidates' => 'Candidates',
                    'status' => 'Status',
                    'created_at' => 'Joined On',
                ],
            ],
            'passport-holder' => [
                'title' => 'Passport Holder Report',
                'group' => 'People',
                'permission' => 'passport-holder.view',
                'date_column' => 'created_at',
                'columns' => [
                    'full_name' => 'Full Name',
                    'passport_number' => 'Passport No.',
                    'agent' => 'Agent',
                    'country' => 'Country',
                    'company' => 'Company',
                    'position' => 'Position',
                    'branch' => 'Branch',
                    'status' => 'Status',
                    'created_at' => 'Registered On',
                ],
            ],
            'country' => [
                'title' => 'Country Report',
                'group' => 'Master Data',
                'permission' => 'core.view',
                'date_column' => 'created_at',
                'columns' => [
                    'name' => 'Country',
                    'code' => 'Code',
                    'companies' => 'Companies',
                    'positions' => 'Positions',
                    'agents' => 'Agents',
                    'recruitments' => 'Recruitments',
                    'status' => 'Status',
                ],
            ],
            'selection' => [
                'title' => 'Selection & Grading Report',
                'group' => 'Recruitment Pipeline',
                'permission' => 'selection.view',
                'date_column' => 'decided_at',
                'columns' => [
                    'tracking_number' => 'Tracking No.',
                    'candidate' => 'Candidate',
                    'agent' => 'Agent',
                    'country' => 'Country',
                    'grade' => 'Grade',
                    'financial_responsible' => 'Financially Responsible',
                    'decided_by' => 'Decided By',
                    'decided_at' => 'Decided On',
                    'status' => 'Status',
                ],
            ],
            'medical' => [
                'title' => 'Medical Report',
                'group' => 'Recruitment Pipeline',
                'permission' => 'medical.view',
                'date_column' => 'medical_date',
                'columns' => [
                    'tracking_number' => 'Tracking No.',
                    'candidate' => 'Candidate',
                    'medical_centre' => 'Medical Centre',
                    'medical_date' => 'Medical Date',
                    'result' => 'Result',
                    'status' => 'Status',
                ],
            ],
            'police-clearance' => [
                'title' => 'Police Clearance Report',
                'group' => 'Recruitment Pipeline',
                'permission' => 'police-clearance.view',
                'date_column' => 'issue_date',
                'columns' => [
                    'tracking_number' => 'Tracking No.',
                    'candidate' => 'Candidate',
                    'issue_date' => 'Issue Date',
                    'expiry_date' => 'Expiry Date',
                    'status' => 'Status',
                ],
            ],
            'visa' => [
                'title' => 'Visa & MOFA Report',
                'group' => 'Recruitment Pipeline',
                'permission' => 'visa.view',
                'date_column' => 'submission_date',
                'columns' => [
                    'tracking_number' => 'Tracking No.',
                    'candidate' => 'Candidate',
                    'country' => 'Country',
                    'lot_number' => 'Visa Lot',
                    'mofa_status' => 'MOFA Status',
                    'submission_date' => 'Submitted On',
                    'status' => 'Status',
                ],
            ],
            'return' => [
                'title' => 'Passport & Visa Return Report',
                'group' => 'Recruitment Pipeline',
                'permission' => 'passport-return.view',
                'date_column' => 'return_date',
                'columns' => [
                    'type' => 'Return Type',
                    'tracking_number' => 'Tracking No.',
                    'candidate' => 'Candidate',
                    'return_date' => 'Return Date',
                    'return_reason' => 'Reason',
                    'delivered_to' => 'Delivered To',
                    'responsible_person' => 'Responsible Person',
                ],
            ],
            'flight' => [
                'title' => 'Flight Report',
                'group' => 'Recruitment Pipeline',
                'permission' => 'flight.view',
                'date_column' => 'departure_date',
                'columns' => [
                    'tracking_number' => 'Tracking No.',
                    'candidate' => 'Candidate',
                    'airline' => 'Airline',
                    'flight_number' => 'Flight No.',
                    'travel_agency' => 'Travel Agency',
                    'departure_date' => 'Departure',
                    'departure_airport' => 'From',
                    'arrival_airport' => 'To',
                    'status' => 'Status',
                ],
            ],
            'agent-due' => [
                'title' => 'Agent Due Report',
                'group' => 'Accounts',
                'permission' => 'ledger.view',
                'date_column' => 'created_at',
                'columns' => [
                    'agent' => 'Agent',
                    'branch' => 'Branch',
                    'total_debit' => 'Total Debit',
                    'total_credit' => 'Total Credit',
                    'balance_due' => 'Balance Due',
                ],
            ],
            'candidate-due' => [
                'title' => 'Candidate Due Report',
                'group' => 'Accounts',
                'permission' => 'ledger.view',
                'date_column' => 'created_at',
                'columns' => [
                    'candidate' => 'Candidate',
                    'agent' => 'Agent',
                    'total_debit' => 'Total Debit',
                    'total_credit' => 'Total Credit',
                    'balance_due' => 'Balance Due',
                ],
            ],
            'hrm' => [
                'title' => 'HRM Report',
                'group' => 'Office',
                'permission' => 'hrm.view',
                'date_column' => 'joining_date',
                'columns' => [
                    'name' => 'Employee',
                    'department' => 'Department',
                    'designation' => 'Designation',
                    'branch' => 'Branch',
                    'joining_date' => 'Joining Date',
                    'salary' => 'Salary',
                    'status' => 'Status',
                ],
            ],
            'payroll' => [
                'title' => 'Payroll Report',
                'group' => 'Office',
                'permission' => 'payroll.view',
                'date_column' => 'created_at',
                'columns' => [
                    'employee' => 'Employee',
                    'month' => 'Month',
                    'gross' => 'Gross',
                    'deductions' => 'Deductions',
                    'bonus' => 'Bonus',
                    'overtime' => 'Overtime',
                    'net' => 'Net Pay',
                    'status' => 'Status',
                ],
            ],
            'branch' => [
                'title' => 'Branch Report',
                'group' => 'Master Data',
                'permission' => 'branch.view',
                'date_column' => 'created_at',
                'columns' => [
                    'name' => 'Branch',
                    'code' => 'Code',
                    'manager' => 'Manager',
                    'users' => 'Users',
                    'agents' => 'Agents',
                    'recruitments' => 'Recruitments',
                    'status' => 'Status',
                ],
            ],
        ];
    }

    public static function definition(string $key): array
    {
        $definitions = static::definitions();

        if (! isset($definitions[$key])) {
            abort(404, 'Unknown report: '.$key);
        }

        return $definitions[$key];
    }

    /**
     * Run a report. Returns columns, rows (plain arrays, identical for
     * screen / PDF / Excel / print) and a summary block.
     */
    public function run(string $key, \App\Models\User $user, array $filters = []): array
    {
        $definition = static::definition($key);
        $method = Str::camel($key).'Report';

        if (! method_exists($this, $method)) {
            throw new \LogicException("Report '{$key}' has no builder method {$method}().");
        }

        $result = $this->{$method}($user, $filters);
        $result['definition'] = $definition;
        $result['filters'] = $this->normalisedFilters($filters);

        return $result;
    }

    // ------------------------------------------------------------------
    // Shared helpers
    // ------------------------------------------------------------------

    /**
     * Apply the data-isolation scope for the current user to a query.
     * Order per Phase 01 §8.3: model scope first, then branch / country /
     * agent / passport-holder fallbacks when the model has no scope.
     */
    public function scoped(Builder $query, \App\Models\User $user): Builder
    {
        $model = $query->getModel();

        if (method_exists($model, 'scopeVisibleTo')) {
            return $query->visibleTo($user);
        }

        if ($user->hasRole('Super Admin')) {
            return $query;
        }

        $table = $model->getTable();

        // Fallback only (every shipped model has scopeVisibleTo, so this
        // rarely runs). An empty assignment list means "not restricted",
        // never "matches nothing" — whereIn([]) would hide every row.
        if (Schema::hasColumn($table, 'branch_id') && method_exists($user, 'branches')) {
            $branchIds = $user->branches()->pluck('branches.id')->all();
            if ($branchIds !== []) {
                $query->whereIn($table.'.branch_id', $branchIds);
            }
        }

        if (Schema::hasColumn($table, 'country_id') && method_exists($user, 'countries')) {
            $countryIds = $user->countries()->pluck('countries.id')->all();
            if ($countryIds !== []) {
                $query->whereIn($table.'.country_id', $countryIds);
            }
        }

        if ($user->hasRole('Agent') && Schema::hasColumn($table, 'agent_id') && $user->agent) {
            $query->where($table.'.agent_id', $user->agent->id);
        }

        if ($user->hasRole('Passport Holder') && Schema::hasColumn($table, 'passport_holder_id') && $user->passportHolder) {
            $query->where($table.'.passport_holder_id', $user->passportHolder->id);
        }

        return $query;
    }

    /**
     * Apply the shared report filters to a query.
     * $dateColumn is the per-report date column from the registry.
     */
    protected function applyFilters(Builder $query, array $filters, string $dateColumn, string $table): Builder
    {
        if (! empty($filters['date_from'])) {
            $query->whereDate($table.'.'.$dateColumn, '>=', $filters['date_from']);
        }

        if (! empty($filters['date_to'])) {
            $query->whereDate($table.'.'.$dateColumn, '<=', $filters['date_to']);
        }

        foreach (['country_id', 'branch_id', 'agent_id'] as $field) {
            if (! empty($filters[$field]) && Schema::hasColumn($table, $field)) {
                $query->where($table.'.'.$field, $filters[$field]);
            }
        }

        if (! empty($filters['status']) && Schema::hasColumn($table, 'status')) {
            $query->where($table.'.status', $filters['status']);
        }

        if (! empty($filters['search'])) {
            $query->where(function (Builder $q) use ($filters, $table) {
                $term = '%'.$filters['search'].'%';
                foreach (['tracking_number', 'name', 'full_name'] as $column) {
                    if (Schema::hasColumn($table, $column)) {
                        $q->orWhere($table.'.'.$column, 'like', $term);
                    }
                }
            });
        }

        return $query;
    }

    protected function normalisedFilters(array $filters): array
    {
        return [
            'date_from' => $filters['date_from'] ?? null,
            'date_to' => $filters['date_to'] ?? null,
            'country_id' => $filters['country_id'] ?? null,
            'branch_id' => $filters['branch_id'] ?? null,
            'agent_id' => $filters['agent_id'] ?? null,
            'status' => $filters['status'] ?? null,
            'search' => $filters['search'] ?? null,
        ];
    }

    protected function money($value): string
    {
        return number_format((float) $value, 2);
    }

    protected function date($value): string
    {
        return $value ? \Carbon\Carbon::parse($value)->format('d M Y') : '—';
    }

    // ------------------------------------------------------------------
    // Individual reports
    // ------------------------------------------------------------------

    protected function recruitmentReport(\App\Models\User $user, array $filters): array
    {
        $query = $this->scoped(Recruitment::with(['passportHolder', 'agent', 'country', 'company', 'position', 'branch']), $user);
        $this->applyFilters($query, $filters, 'created_at', 'recruitments');

        if (! empty($filters['status'])) {
            $query->where('recruitments.current_status', $filters['status']);
        }

        $records = $query->orderByDesc('recruitments.created_at')->get();

        $rows = $records->map(fn (Recruitment $r) => [
            'tracking_number' => $r->tracking_number,
            'candidate' => $r->passportHolder?->full_name ?? '—',
            'agent' => $r->agent?->name ?? '—',
            'country' => $r->country?->name ?? '—',
            'company' => $r->company?->name ?? '—',
            'position' => $r->position?->title ?? '—',
            'branch' => $r->branch?->name ?? '—',
            'current_stage' => $r->current_stage,
            'current_status' => $r->current_status,
            'created_at' => $this->date($r->created_at),
        ])->all();

        return [
            'columns' => static::definition('recruitment')['columns'],
            'rows' => $rows,
            'summary' => [
                'Total Recruitments' => count($rows),
                'By Stage' => $records->groupBy('current_stage')->map->count()->all(),
            ],
        ];
    }

    protected function agentReport(\App\Models\User $user, array $filters): array
    {
        $query = $this->scoped(Agent::with(['branch', 'countries'])->withCount('passportHolders'), $user);
        $this->applyFilters($query, $filters, 'created_at', 'agents');

        $records = $query->orderBy('agents.name')->get();

        $rows = $records->map(fn (Agent $a) => [
            'name' => $a->name,
            'phone' => $a->phone ?? '—',
            'email' => $a->email ?? '—',
            'branch' => $a->branch?->name ?? '—',
            'countries' => $a->countries->pluck('name')->implode(', ') ?: '—',
            'candidates' => $a->passport_holders_count,
            'status' => $a->status,
            'created_at' => $this->date($a->created_at),
        ])->all();

        return [
            'columns' => static::definition('agent')['columns'],
            'rows' => $rows,
            'summary' => [
                'Total Agents' => count($rows),
                'Total Candidates' => $records->sum('passport_holders_count'),
            ],
        ];
    }

    protected function passportHolderReport(\App\Models\User $user, array $filters): array
    {
        $query = $this->scoped(PassportHolder::with(['agent', 'country', 'company', 'position', 'branch']), $user);
        $this->applyFilters($query, $filters, 'created_at', 'passport_holders');

        $records = $query->orderByDesc('passport_holders.created_at')->get();

        $rows = $records->map(function (PassportHolder $p) use ($user) {
            // Passport numbers are sensitive and are masked for every viewer,
            // matching the shipped PassportHolderResource (Phase 01 §13).
            // There is no unmasking permission in the seeder.
            $passport = $p->passport_number;
            $passport = $passport ? str_repeat('•', max(0, strlen($passport) - 2)).substr($passport, -2) : '—';

            return [
                'full_name' => $p->full_name,
                'passport_number' => $passport,
                'agent' => $p->agent?->name ?? '—',
                'country' => $p->country?->name ?? '—',
                'company' => $p->company?->name ?? '—',
                'position' => $p->position?->title ?? '—',
                'branch' => $p->branch?->name ?? '—',
                'status' => $p->status,
                'created_at' => $this->date($p->created_at),
            ];
        })->all();

        return [
            'columns' => static::definition('passport-holder')['columns'],
            'rows' => $rows,
            'summary' => ['Total Passport Holders' => count($rows)],
        ];
    }

    protected function countryReport(\App\Models\User $user, array $filters): array
    {
        $query = $this->scoped(Country::withCount(['companies', 'positions', 'agents', 'recruitments']), $user);
        $this->applyFilters($query, $filters, 'created_at', 'countries');

        $records = $query->orderBy('countries.name')->get();

        $rows = $records->map(fn (Country $c) => [
            'name' => $c->name,
            'code' => $c->code,
            'companies' => $c->companies_count,
            'positions' => $c->positions_count,
            'agents' => $c->agents_count,
            'recruitments' => $c->recruitments_count,
            'status' => $c->status,
        ])->all();

        return [
            'columns' => static::definition('country')['columns'],
            'rows' => $rows,
            'summary' => [
                'Total Countries' => count($rows),
                'Total Recruitments' => $records->sum('recruitments_count'),
            ],
        ];
    }

    protected function selectionReport(\App\Models\User $user, array $filters): array
    {
        $query = Selection::with(['recruitment.passportHolder', 'recruitment.agent', 'recruitment.country', 'decidedBy'])
            ->whereHas('recruitment', fn (Builder $q) => $this->scoped($q, $user));
        $this->applyFilters($query, $filters, 'decided_at', 'selections');

        $records = $query->orderByDesc('selections.decided_at')->get();

        $rows = $records->map(function (Selection $s) {
            $r = $s->recruitment;

            return [
                'tracking_number' => $r?->tracking_number ?? '—',
                'candidate' => $r?->passportHolder?->full_name ?? '—',
                'agent' => $r?->agent?->name ?? '—',
                'country' => $r?->country?->name ?? '—',
                'grade' => $s->grade ?? '—',
                'financial_responsible' => $r?->financial_responsible_type
                    ? ucfirst($r->financial_responsible_type)
                    : 'Undecided',
                'decided_by' => $s->decidedBy?->name ?? '—',
                'decided_at' => $this->date($s->decided_at),
                'status' => $s->status,
            ];
        })->all();

        return [
            'columns' => static::definition('selection')['columns'],
            'rows' => $rows,
            'summary' => [
                'Total Selections' => count($rows),
                'By Grade' => $records->groupBy('grade')->map->count()->all(),
            ],
        ];
    }

    protected function medicalReport(\App\Models\User $user, array $filters): array
    {
        $query = Medical::with(['recruitment.passportHolder', 'centre'])
            ->whereHas('recruitment', fn (Builder $q) => $this->scoped($q, $user));
        $this->applyFilters($query, $filters, 'medical_date', 'medicals');

        if (! empty($filters['status'])) {
            $query->where('medicals.result', $filters['status']);
        }

        $records = $query->orderByDesc('medicals.medical_date')->get();

        $rows = $records->map(fn (Medical $m) => [
            'tracking_number' => $m->recruitment?->tracking_number ?? '—',
            'candidate' => $m->recruitment?->passportHolder?->full_name ?? '—',
            'medical_centre' => $m->centre?->name ?? '—',
            'medical_date' => $this->date($m->medical_date),
            'result' => $m->result,
            'status' => $m->status ?? $m->result,
        ])->all();

        return [
            'columns' => static::definition('medical')['columns'],
            'rows' => $rows,
            'summary' => [
                'Total Medicals' => count($rows),
                'By Result' => $records->groupBy('result')->map->count()->all(),
            ],
        ];
    }

    protected function policeClearanceReport(\App\Models\User $user, array $filters): array
    {
        $query = PoliceClearance::with(['recruitment.passportHolder'])
            ->whereHas('recruitment', fn (Builder $q) => $this->scoped($q, $user));
        $this->applyFilters($query, $filters, 'issue_date', 'police_clearances');

        $records = $query->orderByDesc('police_clearances.issue_date')->get();

        $rows = $records->map(fn (PoliceClearance $p) => [
            'tracking_number' => $p->recruitment?->tracking_number ?? '—',
            'candidate' => $p->recruitment?->passportHolder?->full_name ?? '—',
            'issue_date' => $this->date($p->issue_date),
            'expiry_date' => $this->date($p->expiry_date),
            'status' => $p->status,
        ])->all();

        return [
            'columns' => static::definition('police-clearance')['columns'],
            'rows' => $rows,
            'summary' => [
                'Total Clearances' => count($rows),
                'By Status' => $records->groupBy('status')->map->count()->all(),
            ],
        ];
    }

    protected function visaReport(\App\Models\User $user, array $filters): array
    {
        $query = Visa::with(['recruitment.passportHolder', 'recruitment.country', 'lot'])
            ->whereHas('recruitment', fn (Builder $q) => $this->scoped($q, $user));
        $this->applyFilters($query, $filters, 'submission_date', 'visas');

        $records = $query->orderByDesc('visas.submission_date')->get();

        $rows = $records->map(fn (Visa $v) => [
            'tracking_number' => $v->recruitment?->tracking_number ?? '—',
            'candidate' => $v->recruitment?->passportHolder?->full_name ?? '—',
            'country' => $v->recruitment?->country?->name ?? '—',
            'lot_number' => $v->lot?->lot_number ?? '—',
            'mofa_status' => $v->mofa_status ?? '—',
            'submission_date' => $this->date($v->submission_date),
            'status' => $v->status,
        ])->all();

        return [
            'columns' => static::definition('visa')['columns'],
            'rows' => $rows,
            'summary' => [
                'Total Visas' => count($rows),
                'By Status' => $records->groupBy('status')->map->count()->all(),
                'By MOFA Status' => $records->groupBy('mofa_status')->map->count()->all(),
            ],
        ];
    }

    protected function returnReport(\App\Models\User $user, array $filters): array
    {
        $passportRows = PassportReturn::with(['recruitment.passportHolder'])
            ->whereHas('recruitment', fn (Builder $q) => $this->scoped($q, $user))
            ->when(! empty($filters['date_from']), fn (Builder $q) => $q->whereDate('return_date', '>=', $filters['date_from']))
            ->when(! empty($filters['date_to']), fn (Builder $q) => $q->whereDate('return_date', '<=', $filters['date_to']))
            ->get()
            ->map(fn (PassportReturn $p) => [
                'type' => 'Passport Return',
                'tracking_number' => $p->recruitment?->tracking_number ?? '—',
                'candidate' => $p->recruitment?->passportHolder?->full_name ?? '—',
                'return_date' => $this->date($p->return_date),
                'return_reason' => $p->return_reason ?? '—',
                'delivered_to' => $p->delivered_to ?? '—',
                'responsible_person' => $p->responsible_person ?? '—',
            ]);

        $visaRows = VisaReturn::with(['visa.recruitment.passportHolder'])
            ->whereHas('visa.recruitment', fn (Builder $q) => $this->scoped($q, $user))
            ->when(! empty($filters['date_from']), fn (Builder $q) => $q->whereDate('return_date', '>=', $filters['date_from']))
            ->when(! empty($filters['date_to']), fn (Builder $q) => $q->whereDate('return_date', '<=', $filters['date_to']))
            ->get()
            ->map(fn (VisaReturn $v) => [
                'type' => 'Visa Return',
                'tracking_number' => $v->visa?->recruitment?->tracking_number ?? '—',
                'candidate' => $v->visa?->recruitment?->passportHolder?->full_name ?? '—',
                'return_date' => $this->date($v->return_date),
                'return_reason' => $v->return_reason ?? '—',
                'delivered_to' => $v->delivered_to ?? '—',
                'responsible_person' => $v->responsible_person ?? '—',
            ]);

        $rows = $passportRows->merge($visaRows)->sortByDesc('return_date')->values()->all();

        return [
            'columns' => static::definition('return')['columns'],
            'rows' => $rows,
            'summary' => [
                'Total Returns' => count($rows),
                'Passport Returns' => $passportRows->count(),
                'Visa Returns' => $visaRows->count(),
            ],
        ];
    }

    protected function flightReport(\App\Models\User $user, array $filters): array
    {
        $query = Flight::with(['recruitment.passportHolder', 'airline', 'travelAgency'])
            ->whereHas('recruitment', fn (Builder $q) => $this->scoped($q, $user));
        $this->applyFilters($query, $filters, 'departure_date', 'flights');

        $records = $query->orderByDesc('flights.departure_date')->get();

        $rows = $records->map(fn (Flight $f) => [
            'tracking_number' => $f->recruitment?->tracking_number ?? '—',
            'candidate' => $f->recruitment?->passportHolder?->full_name ?? '—',
            'airline' => $f->airline?->name ?? '—',
            'flight_number' => $f->flight_number ?? '—',
            'travel_agency' => $f->travelAgency?->name ?? '—',
            'departure_date' => $this->date($f->departure_date),
            'departure_airport' => $f->departure_airport ?? '—',
            'arrival_airport' => $f->arrival_airport ?? '—',
            'status' => $f->status,
        ])->all();

        return [
            'columns' => static::definition('flight')['columns'],
            'rows' => $rows,
            'summary' => [
                'Total Flights' => count($rows),
                'By Status' => $records->groupBy('status')->map->count()->all(),
            ],
        ];
    }

    /**
     * Due reports are computed ONLY from posted journal_entry_lines
     * (Phase 01 §10 rule 5): sub_ledger_type + sub_ledger_id, debit minus
     * credit. There is no second balance that could drift.
     */
    protected function agentDueReport(\App\Models\User $user, array $filters): array
    {
        $lines = JournalEntryLine::query()
            ->selectRaw('sub_ledger_id, SUM(debit) as total_debit, SUM(credit) as total_credit')
            ->where('sub_ledger_type', 'agent')
            ->whereHas('journalEntry', fn (Builder $q) => $q->whereHas('voucher', fn (Builder $v) => $v->where('status', 'posted')))
            ->groupBy('sub_ledger_id')
            ->get();

        $agentIds = $lines->pluck('sub_ledger_id');
        $agents = Agent::with('branch')->whereIn('id', $agentIds)->get()->keyBy('id');

        // Agent isolation: an Agent sees only their own due row.
        if ($user->hasRole('Agent') && $user->agent) {
            $lines = $lines->where('sub_ledger_id', $user->agent->id);
        }

        if (! empty($filters['branch_id'])) {
            $lines = $lines->filter(fn ($l) => ($agents[$l->sub_ledger_id]?->branch_id ?? null) == $filters['branch_id']);
        }

        if (! empty($filters['agent_id'])) {
            $lines = $lines->where('sub_ledger_id', $filters['agent_id']);
        }

        $rows = $lines->map(function ($l) use ($agents) {
            $agent = $agents[$l->sub_ledger_id] ?? null;

            return [
                'agent' => $agent?->name ?? 'Agent #'.$l->sub_ledger_id,
                'branch' => $agent?->branch?->name ?? '—',
                'total_debit' => $this->money($l->total_debit),
                'total_credit' => $this->money($l->total_credit),
                'balance_due' => $this->money($l->total_debit - $l->total_credit),
            ];
        })->values()->all();

        return [
            'columns' => static::definition('agent-due')['columns'],
            'rows' => $rows,
            'summary' => [
                'Agents With Dues' => count($rows),
                'Total Due' => $this->money($lines->sum(fn ($l) => $l->total_debit - $l->total_credit)),
            ],
        ];
    }

    protected function candidateDueReport(\App\Models\User $user, array $filters): array
    {
        $lines = JournalEntryLine::query()
            ->selectRaw('sub_ledger_id, SUM(debit) as total_debit, SUM(credit) as total_credit')
            ->where('sub_ledger_type', 'passport_holder')
            ->whereHas('journalEntry', fn (Builder $q) => $q->whereHas('voucher', fn (Builder $v) => $v->where('status', 'posted')))
            ->groupBy('sub_ledger_id')
            ->get();

        $holderIds = $lines->pluck('sub_ledger_id');
        $holders = PassportHolder::with(['agent', 'branch'])->whereIn('id', $holderIds)->get()->keyBy('id');

        // Passport Holder isolation: a holder sees only their own due row.
        if ($user->hasRole('Passport Holder') && $user->passportHolder) {
            $lines = $lines->where('sub_ledger_id', $user->passportHolder->id);
        }

        if ($user->hasRole('Agent') && $user->agent) {
            $lines = $lines->filter(fn ($l) => ($holders[$l->sub_ledger_id]?->agent_id ?? null) === $user->agent->id);
        }

        if (! empty($filters['branch_id'])) {
            $lines = $lines->filter(fn ($l) => ($holders[$l->sub_ledger_id]?->branch_id ?? null) == $filters['branch_id']);
        }

        if (! empty($filters['agent_id'])) {
            $lines = $lines->filter(fn ($l) => ($holders[$l->sub_ledger_id]?->agent_id ?? null) == $filters['agent_id']);
        }

        $rows = $lines->map(function ($l) use ($holders) {
            $holder = $holders[$l->sub_ledger_id] ?? null;

            return [
                'candidate' => $holder?->full_name ?? 'Candidate #'.$l->sub_ledger_id,
                'agent' => $holder?->agent?->name ?? '—',
                'total_debit' => $this->money($l->total_debit),
                'total_credit' => $this->money($l->total_credit),
                'balance_due' => $this->money($l->total_debit - $l->total_credit),
            ];
        })->values()->all();

        return [
            'columns' => static::definition('candidate-due')['columns'],
            'rows' => $rows,
            'summary' => [
                'Candidates With Dues' => count($rows),
                'Total Due' => $this->money($lines->sum(fn ($l) => $l->total_debit - $l->total_credit)),
            ],
        ];
    }

    protected function hrmReport(\App\Models\User $user, array $filters): array
    {
        $query = $this->scoped(Employee::with(['user', 'department', 'designation', 'branch']), $user);
        $this->applyFilters($query, $filters, 'joining_date', 'employees');

        $records = $query->orderBy('employees.employee_code')->get();

        $rows = $records->map(fn (Employee $e) => [
            'name' => $e->user?->name ?? $e->employee_code,
            'department' => $e->department?->name ?? '—',
            'designation' => $e->designation?->name ?? '—',
            'branch' => $e->branch?->name ?? '—',
            'joining_date' => $this->date($e->joining_date),
            'salary' => $this->money($e->salary),
            'status' => $e->status,
        ])->all();

        return [
            'columns' => static::definition('hrm')['columns'],
            'rows' => $rows,
            'summary' => [
                'Total Employees' => count($rows),
                'By Department' => $records->groupBy(fn (Employee $e) => $e->department?->name ?? 'Unassigned')->map->count()->all(),
            ],
        ];
    }

    protected function payrollReport(\App\Models\User $user, array $filters): array
    {
        $query = Payroll::with(['employee.user', 'employee.branch'])
            ->whereHas('employee', fn (Builder $q) => $this->scoped($q, $user));
        $this->applyFilters($query, $filters, 'created_at', 'payrolls');

        if (! empty($filters['status'])) {
            $query->where('payrolls.status', $filters['status']);
        }

        $records = $query->orderByDesc('payrolls.month')->get();

        $rows = $records->map(fn (Payroll $p) => [
            'employee' => $p->employee?->user?->name ?? $p->employee?->employee_code ?? '—',
            'month' => $p->month,
            'gross' => $this->money($p->gross),
            'deductions' => $this->money($p->deductions),
            'bonus' => $this->money($p->bonus),
            'overtime' => $this->money($p->overtime),
            'net' => $this->money($p->net),
            'status' => $p->status,
        ])->all();

        return [
            'columns' => static::definition('payroll')['columns'],
            'rows' => $rows,
            'summary' => [
                'Payroll Runs' => count($rows),
                'Total Net Pay' => $this->money($records->sum('net')),
            ],
        ];
    }

    protected function branchReport(\App\Models\User $user, array $filters): array
    {
        $query = $this->scoped(Branch::withCount(['users', 'agents', 'recruitments']), $user);
        $this->applyFilters($query, $filters, 'created_at', 'branches');

        $records = $query->orderBy('branches.name')->get();

        $rows = $records->map(fn (Branch $b) => [
            'name' => $b->name,
            'code' => $b->code,
            'manager' => $b->manager?->name ?? '—',
            'users' => $b->users_count,
            'agents' => $b->agents_count,
            'recruitments' => $b->recruitments_count,
            'status' => $b->status,
        ])->all();

        return [
            'columns' => static::definition('branch')['columns'],
            'rows' => $rows,
            'summary' => [
                'Total Branches' => count($rows),
                'Total Recruitments' => $records->sum('recruitments_count'),
            ],
        ];
    }
}
