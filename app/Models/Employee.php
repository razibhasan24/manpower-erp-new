<?php

namespace App\Models;

use App\Models\Concerns\HasDataIsolation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, HasDataIsolation, SoftDeletes;

    protected $table = 'employees';

    protected $fillable = [
        'user_id',
        'department_id',
        'designation_id',
        'branch_id',
        'employee_code',
        'joining_date',
        'salary',
        'status',
        'phone',
        'address',
    ];

    protected $casts = [
        'joining_date' => 'date',
        'salary' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function designation(): BelongsTo
    {
        return $this->belongsTo(Designation::class, 'designation_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'employee_id');
    }

    public function leaves(): HasMany
    {
        return $this->hasMany(Leave::class, 'employee_id');
    }

    public function salaryStructures(): HasMany
    {
        return $this->hasMany(SalaryStructure::class, 'employee_id');
    }

    public function payrolls(): HasMany
    {
        return $this->hasMany(Payroll::class, 'employee_id');
    }

    // MERGE FIX 2026-11-13 (coordinator): documents relation was missing
    // alongside the EmployeeDocument model/migration.
    public function documents(): HasMany
    {
        return $this->hasMany(EmployeeDocument::class, 'employee_id');
    }

    /**
     * Data-isolation scope (Phase 01 section 8). List queries must start
     * from Recruitment::visibleTo($user) style scopes so an Agent or
     * Passport Holder can never select another party's rows.
     */
    public function scopeVisibleTo($query, User $user)
    {
        if ($this->isolationSuperAdmin($user)) {
            return $query;
        }

        if ($user->hasRole('Agent') || $user->hasRole('Passport Holder')) {
            return $query->whereRaw('1 = 0');
        }

        return $this->isolationApplyBranchCountry($query, $user, true, false);
    }
}
