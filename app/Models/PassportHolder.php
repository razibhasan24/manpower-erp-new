<?php

namespace App\Models;

use App\Models\Concerns\HasDataIsolation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PassportHolder extends Model
{
    use HasFactory, HasDataIsolation, SoftDeletes;

    protected $table = 'passport_holders';

    protected $fillable = [
        'user_id',
        'agent_id',
        'branch_id',
        'country_id',
        'company_id',
        'position_id',
        'full_name',
        'father_name',
        'mother_name',
        'dob',
        'gender',
        'nationality',
        'phone',
        'email',
        'present_address',
        'permanent_address',
        'nid',
        'passport_number',
        'passport_issue_date',
        'passport_expiry_date',
        'photo_path',
        'referer', 'religion', 'marital_status', 'height', 'weight', 'village_road', 'post_office', 'police_station', 'district',
        'status',
    ];

    protected $casts = [
        'dob' => 'date',
        'passport_issue_date' => 'date',
        'passport_expiry_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(Agent::class, 'agent_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class, 'position_id');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(PassportHolderDocument::class, 'passport_holder_id');
    }

    public function recruitments(): HasMany
    {
        return $this->hasMany(Recruitment::class, 'passport_holder_id');
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

        if ($user->hasRole('Passport Holder')) {
            return $query->where('user_id', $user->id);
        }

        if ($user->hasRole('Agent')) {
            $agentId = $this->isolationAgentId($user);

            return $agentId ? $query->where('agent_id', $agentId) : $query->whereRaw('1 = 0');
        }

        return $this->isolationApplyBranchCountry($query, $user, true, true);
    }
}
