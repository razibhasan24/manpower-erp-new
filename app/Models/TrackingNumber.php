<?php

namespace App\Models;

use App\Models\Concerns\HasDataIsolation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class TrackingNumber extends Model
{
    use HasFactory, HasDataIsolation;

    protected $table = 'tracking_numbers';

    protected $fillable = [
        'tracking_number',
        'recruitment_id',
        'branch_id',
        'year',
        'sequence',
    ];

    protected $casts = [
        'year' => 'integer',
        'sequence' => 'integer',
    ];

    public function recruitment(): BelongsTo
    {
        return $this->belongsTo(Recruitment::class, 'recruitment_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
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
