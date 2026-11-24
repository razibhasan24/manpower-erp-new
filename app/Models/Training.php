<?php

namespace App\Models;

use App\Models\Concerns\HasDataIsolation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Training extends Model
{
    use HasFactory, HasDataIsolation, SoftDeletes;

    protected $table = 'trainings';

    protected $fillable = [
        'recruitment_id',
        'training_centre_id',
        'course_id',
        'start_date',
        'end_date',
        'attendance_percentage',
        'result',
        'certificate_path',
        'remarks',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'attendance_percentage' => 'decimal:2',
    ];

    public function recruitment(): BelongsTo
    {
        return $this->belongsTo(Recruitment::class, 'recruitment_id');
    }

    public function centre(): BelongsTo
    {
        return $this->belongsTo(TrainingCentre::class, 'training_centre_id');
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(TrainingCourse::class, 'course_id');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(TrainingAttendance::class);
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(TrainingCertificate::class);
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
