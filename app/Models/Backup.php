<?php

namespace App\Models;

use App\Models\Concerns\HasDataIsolation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Backup extends Model
{
    use HasFactory, HasDataIsolation;

    protected $table = 'backups';

    protected $fillable = [
        'filename',
        'type',
        'size',
        'created_by',
        'verified_at',
        'status',
        'path',
        'notes',
    ];

    protected $casts = [
        'size' => 'integer',
        'verified_at' => 'datetime',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
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

        return $query->where('created_by', $user->id);
    }
}
