<?php

namespace App\Models;

use App\Models\Concerns\HasDataIsolation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payroll extends Model
{
    use HasFactory, HasDataIsolation, SoftDeletes;

    protected $table = 'payrolls';

    protected $fillable = [
        'employee_id',
        'month',
        'gross',
        'deductions',
        'bonus',
        'overtime',
        'net',
        'status',
        'paid_at',
        'voucher_id',
    ];

    protected $casts = [
        'gross' => 'decimal:2',
        'deductions' => 'decimal:2',
        'bonus' => 'decimal:2',
        'overtime' => 'decimal:2',
        'net' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function voucher(): BelongsTo
    {
        return $this->belongsTo(Voucher::class, 'voucher_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(PayrollItem::class, 'payroll_id');
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
