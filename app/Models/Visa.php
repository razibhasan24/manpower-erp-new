<?php

namespace App\Models;

use App\Models\Concerns\HasDataIsolation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Visa extends Model
{
    use HasFactory, HasDataIsolation, SoftDeletes;

    protected $table = 'visas';

    protected $fillable = [
        'recruitment_id',
        'visa_lot_id',
        'mofa_status',
        'submission_date',
        'approval_date',
        'status',
        'remarks',
    ];

    protected $casts = [
        'submission_date' => 'date',
        'approval_date' => 'date',
    ];

    public function recruitment(): BelongsTo
    {
        return $this->belongsTo(Recruitment::class, 'recruitment_id');
    }

    public function lot(): BelongsTo
    {
        return $this->belongsTo(VisaLot::class, 'visa_lot_id');
    }

    public function workPermit(): HasOne
    {
        return $this->hasOne(WorkPermit::class, 'visa_id');
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class, 'visa_id');
    }

    public function mofaProcesses(): HasMany
    {
        return $this->hasMany(MofaProcess::class, 'visa_id');
    }

    public function visaReturn(): HasOne
    {
        return $this->hasOne(VisaReturn::class, 'visa_id');
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

        return $query->whereHas('recruitment', function ($q) use ($user) {
            $q->visibleTo($user);
        });
    }
}
