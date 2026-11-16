<?php

namespace App\Models;

use App\Models\Concerns\HasDataIsolation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory, HasDataIsolation;

    protected $table = 'countries';

    protected $fillable = [
        'name',
        'code',
        'status',
    ];

    public function companies(): HasMany
    {
        return $this->hasMany(Company::class, 'country_id');
    }

    public function positions(): HasMany
    {
        return $this->hasMany(Position::class, 'country_id');
    }

    public function agents(): BelongsToMany
    {
        return $this->belongsToMany(Agent::class, 'agent_country');
    }

    public function recruitments(): HasMany
    {
        return $this->hasMany(Recruitment::class, 'country_id');
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
