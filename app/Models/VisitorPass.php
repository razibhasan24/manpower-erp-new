<?php

namespace App\Models;

use App\Models\Concerns\HasDataIsolation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class VisitorPass extends Model
{
    use HasFactory, HasDataIsolation;

    protected $table = 'visitor_passes';

    protected $fillable = [
        'visitor_id',
        'pass_number',
        'issued_at',
        'returned_at',
        'status',
    ];

    protected $casts = [
        'issued_at' => 'datetime',
        'returned_at' => 'datetime',
    ];

    public function visitor(): BelongsTo
    {
        return $this->belongsTo(Visitor::class, 'visitor_id');
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

        return $query->whereHas('visitor', function ($q) use ($user) {
            $q->visibleTo($user);
        });
    }
}
