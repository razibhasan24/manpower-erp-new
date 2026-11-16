<?php

namespace App\Models;

use App\Models\Concerns\HasDataIsolation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Selection extends Model
{
    use HasFactory, HasDataIsolation, SoftDeletes;

    protected $table = 'selections';

    protected $fillable = [
        'recruitment_id',
        'grade',
        'grading_criteria_snapshot',
        'remarks',
        'decided_by',
        'decided_at',
        'status',
    ];

    protected $casts = [
        'grading_criteria_snapshot' => 'array',
        'decided_at' => 'datetime',
    ];

    public function recruitment(): BelongsTo
    {
        return $this->belongsTo(Recruitment::class, 'recruitment_id');
    }

    public function decidedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'decided_by');
    }

    public function grades(): HasMany
    {
        return $this->hasMany(SelectionGrade::class, 'selection_id');
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
