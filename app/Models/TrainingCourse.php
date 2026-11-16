<?php

namespace App\Models;

use App\Models\Concerns\HasDataIsolation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class TrainingCourse extends Model
{
    use HasFactory, HasDataIsolation;

    protected $table = 'training_courses';

    protected $fillable = [
        'training_centre_id',
        'title',
        'duration_days',
        'fee',
        'status',
    ];

    protected $casts = [
        'duration_days' => 'integer',
        'fee' => 'decimal:2',
    ];

    public function centre(): BelongsTo
    {
        return $this->belongsTo(TrainingCentre::class, 'training_centre_id');
    }

    /**
     * Data-isolation scope (Phase 01 section 8). List queries must start
     * from Recruitment::visibleTo($user) style scopes so an Agent or
     * Passport Holder can never select another party's rows.
     */
    public function scopeVisibleTo($query, User $user)
    {
        // No owner, branch or country column on this table (master data or
        // append-only record). Row access is enforced by the module Policy
        // and route permission middleware, not by a query filter.
        return $query;
    }
}
