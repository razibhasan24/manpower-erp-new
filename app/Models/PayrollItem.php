<?php

namespace App\Models;

use App\Models\Concerns\HasDataIsolation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class PayrollItem extends Model
{
    use HasFactory, HasDataIsolation;

    protected $table = 'payroll_items';

    protected $fillable = [
        'payroll_id',
        'label',
        'type',
        'amount',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function payroll(): BelongsTo
    {
        return $this->belongsTo(Payroll::class, 'payroll_id');
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

        return $query->whereHas('payroll', function ($q) use ($user) {
            $q->whereHas('employee', function ($q2) use ($user) {
                $q2->visibleTo($user);
            });
        });
    }
}
