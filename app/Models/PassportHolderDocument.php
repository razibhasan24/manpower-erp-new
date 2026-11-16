<?php

namespace App\Models;

use App\Models\Concerns\HasDataIsolation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PassportHolderDocument extends Model
{
    use HasFactory, HasDataIsolation, SoftDeletes;

    protected $table = 'passport_holder_documents';

    protected $fillable = [
        'passport_holder_id',
        'type',
        'file_path',
        'original_name',
        'mime_type',
        'size',
        'uploaded_by',
    ];

    protected $casts = [
        'size' => 'integer',
    ];

    public function passportHolder(): BelongsTo
    {
        return $this->belongsTo(PassportHolder::class, 'passport_holder_id');
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
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

        return $query->whereHas('passportHolder', function ($q) use ($user) {
            $q->visibleTo($user);
        });
    }
}
