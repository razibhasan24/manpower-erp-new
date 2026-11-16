<?php

namespace App\Models;

use App\Models\Concerns\HasDataIsolation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class SelectionGrade extends Model
{
    use HasFactory, HasDataIsolation;

    protected $table = 'selection_grades';

    protected $fillable = [
        'selection_id',
        'grading_criterion_id',
        'score',
        'remarks',
    ];

    protected $casts = [
        'score' => 'decimal:2',
    ];

    public function selection(): BelongsTo
    {
        return $this->belongsTo(Selection::class, 'selection_id');
    }

    public function criterion(): BelongsTo
    {
        return $this->belongsTo(GradingCriterion::class, 'grading_criterion_id');
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

        return $query->whereHas('selection', function ($q) use ($user) {
            $q->whereHas('recruitment', function ($q2) use ($user) {
                $q2->visibleTo($user);
            });
        });
    }
}
