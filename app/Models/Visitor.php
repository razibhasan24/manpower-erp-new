<?php

namespace App\Models;

use App\Models\Concerns\HasDataIsolation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Visitor extends Model
{
    use HasFactory, HasDataIsolation, SoftDeletes;

    protected $table = 'visitors';

    protected $fillable = [
        'name',
        'phone',
        'purpose',
        'person_to_meet',
        'entry_time',
        'exit_time',
        'pass_number',
        'status',
        'branch_id',
    ];

    protected $casts = [
        'entry_time' => 'datetime',
        'exit_time' => 'datetime',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function passes(): HasMany
    {
        return $this->hasMany(VisitorPass::class, 'visitor_id');
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

        return $this->isolationApplyBranchCountry($query, $user, true, false);
    }
}
