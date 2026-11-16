<?php

namespace App\Models;

use App\Models\Concerns\HasDataIsolation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ManpowerApproval extends Model
{
    use HasFactory, HasDataIsolation, SoftDeletes;

    protected $table = 'manpower_approvals';

    protected $fillable = [
        'recruitment_id',
        'approval_reference',
        'approval_date',
        'status',
        'documents_path',
        'remarks',
    ];

    protected $casts = [
        'approval_date' => 'date',
    ];

    public function recruitment(): BelongsTo
    {
        return $this->belongsTo(Recruitment::class, 'recruitment_id');
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
