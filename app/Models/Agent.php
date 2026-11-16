<?php

namespace App\Models;

use App\Models\Concerns\HasDataIsolation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Agent extends Model
{
    use HasFactory, HasDataIsolation, SoftDeletes;

    protected $table = 'agents';

    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'email',
        'address',
        'nid',
        'trade_license',
        'status',
        'branch_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function countries(): BelongsToMany
    {
        return $this->belongsToMany(Country::class, 'agent_country');
    }

    public function passportHolders(): HasMany
    {
        return $this->hasMany(PassportHolder::class, 'agent_id');
    }

    public function recruitments(): HasMany
    {
        return $this->hasMany(Recruitment::class, 'agent_id');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(AgentDocument::class, 'agent_id');
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

        if ($user->hasRole('Agent')) {
            return $query->where('user_id', $user->id);
        }

        return $this->isolationApplyBranchCountry($query, $user, true, false);
    }
}
