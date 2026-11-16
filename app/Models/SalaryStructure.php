<?php

namespace App\Models;

use App\Models\Concerns\HasDataIsolation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalaryStructure extends Model
{
    use HasFactory, HasDataIsolation, SoftDeletes;

    protected $table = 'salary_structures';

    protected $fillable = [
        'employee_id',
        'basic',
        'allowances',
        'deductions',
        'effective_from',
        'status',
    ];

    protected $casts = [
        'basic' => 'decimal:2',
        'allowances' => 'array',
        'deductions' => 'array',
        'effective_from' => 'date',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
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

        return $query->whereHas('employee', function ($q) use ($user) {
            $q->visibleTo($user);
        });
    }
}
