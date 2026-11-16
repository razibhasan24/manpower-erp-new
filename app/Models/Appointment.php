<?php

namespace App\Models;

use App\Models\Concerns\HasDataIsolation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    use HasFactory, HasDataIsolation, SoftDeletes;

    protected $table = 'appointments';

    protected $fillable = [
        'visa_id',
        'appointment_date',
        'location',
        'status',
        'remarks',
    ];

    protected $casts = [
        'appointment_date' => 'datetime',
    ];

    public function visa(): BelongsTo
    {
        return $this->belongsTo(Visa::class, 'visa_id');
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

        return $query->whereHas('visa', function ($q) use ($user) {
            $q->whereHas('recruitment', function ($q2) use ($user) {
                $q2->visibleTo($user);
            });
        });
    }
}
