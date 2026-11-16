<?php

namespace App\Models;

use App\Models\Concerns\HasDataIsolation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{
    use HasFactory, HasDataIsolation, SoftDeletes;

    protected $table = 'branches';

    protected $fillable = [
        'name',
        'code',
        'address',
        'phone',
        'email',
        'manager_id',
        'status',
    ];

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'branch_id');
    }

    public function agents(): HasMany
    {
        return $this->hasMany(Agent::class, 'branch_id');
    }

    public function recruitments(): HasMany
    {
        return $this->hasMany(Recruitment::class, 'branch_id');
    }

    public function vouchers(): HasMany
    {
        return $this->hasMany(Voucher::class, 'branch_id');
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class, 'branch_id');
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

        $branchIds = $this->isolationBranchIds($user);

        if ($branchIds !== []) {
            $query->whereIn('id', $branchIds);
        }

        return $query;
    }
}
