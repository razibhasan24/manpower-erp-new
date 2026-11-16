<?php

namespace App\Models;

use App\Models\Concerns\HasDataIsolation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, HasDataIsolation, SoftDeletes;

    protected $table = 'companies';

    protected $fillable = [
        'name',
        'country_id',
        'status',
        'address',
        'phone',
        'email',
        'website',
        'contact_person',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function positions(): HasMany
    {
        return $this->hasMany(Position::class, 'company_id');
    }

    public function recruitments(): HasMany
    {
        return $this->hasMany(Recruitment::class, 'company_id');
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

        return $this->isolationApplyBranchCountry($query, $user, true, true);
    }
}
