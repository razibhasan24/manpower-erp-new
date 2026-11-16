<?php

namespace App\Models;

use App\Models\Concerns\HasDataIsolation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Complaint extends Model
{
    use HasFactory, HasDataIsolation, SoftDeletes;

    protected $table = 'complaints';

    protected $fillable = [
        'post_arrival_id',
        'recruitment_id',
        'subject',
        'description',
        'status',
        'raised_by',
        'resolved_by',
        'resolved_at',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    public function postArrival(): BelongsTo
    {
        return $this->belongsTo(PostArrival::class, 'post_arrival_id');
    }

    public function recruitment(): BelongsTo
    {
        return $this->belongsTo(Recruitment::class, 'recruitment_id');
    }

    public function raisedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'raised_by');
    }

    public function resolvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by');
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
