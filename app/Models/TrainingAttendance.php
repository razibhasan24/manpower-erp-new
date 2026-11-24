<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrainingAttendance extends Model
{
    protected $fillable = ['training_id', 'attendance_date', 'status', 'remarks', 'created_by', 'updated_by'];

    protected $casts = ['attendance_date' => 'date'];

    public function training(): BelongsTo
    {
        return $this->belongsTo(Training::class);
    }
}
