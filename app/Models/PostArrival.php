<?php

namespace App\Models;

use App\Models\Concerns\HasDataIsolation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostArrival extends Model
{
    use HasFactory, HasDataIsolation, SoftDeletes;

    protected $table = 'post_arrivals';

    protected $fillable = [
        'recruitment_id',
        'arrival_date',
        'airport_pickup',
        'employer_contact',
        'accommodation',
        'feedback',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'arrival_date' => 'date',
        'airport_pickup' => 'boolean',
    ];

    public function recruitment(): BelongsTo
    {
        return $this->belongsTo(Recruitment::class, 'recruitment_id');
    }

    public function supports(): HasMany
    {
        return $this->hasMany(ArrivalSupport::class, 'post_arrival_id');
    }

    public function complaints(): HasMany
    {
        return $this->hasMany(Complaint::class, 'post_arrival_id');
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
