<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrainingCertificate extends Model
{
    protected $fillable = ['training_id', 'certificate_number', 'issued_at', 'file_path', 'original_name', 'mime_type', 'size', 'created_by', 'updated_by'];

    protected $casts = ['issued_at' => 'date', 'size' => 'integer'];

    public function training(): BelongsTo
    {
        return $this->belongsTo(Training::class);
    }
}
