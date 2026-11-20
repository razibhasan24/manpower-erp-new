<?php

namespace App\Models;

use App\Models\Concerns\HasDataIsolation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArrivalSupport extends Model
{
    use HasFactory, HasDataIsolation, SoftDeletes;

    protected $table = 'arrival_supports';

    protected $fillable = [
        'post_arrival_id',
        'type',
        'description',
        'status',
        'resolved_at',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    public function postArrival(): BelongsTo
    {
        return $this->belongsTo(PostArrival::class, 'post_arrival_id');
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

        return $query->whereHas('postArrival', function ($q) use ($user) {
            $q->whereHas('recruitment', function ($q2) use ($user) {
                $q2->visibleTo($user);
            });
        });
    }
}
