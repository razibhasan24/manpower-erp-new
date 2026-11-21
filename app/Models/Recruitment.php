<?php

namespace App\Models;

use App\Models\Concerns\HasDataIsolation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Recruitment extends Model
{
    use HasFactory, HasDataIsolation, SoftDeletes;

    protected $table = 'recruitments';

    protected $fillable = [
        'tracking_number',
        'passport_holder_id',
        'agent_id',
        'branch_id',
        'country_id',
        'company_id',
        'position_id',
        'expected_salary',
        'financial_responsible_type',
        'financial_responsible_id',
        'current_stage',
        'current_status',
        'remarks',
        'salary',
        'company_service_charge',
        'agent_charge',
        'medical_cost',
        'training_cost',
        'visa_cost',
        'other_cost',
        'total_cost',
        'advance_amount',
        'remaining_due',
        'local_experience_years', 'education_type', 'education_degree', 'education_school', 'education_grade', 'education_year', 'language_proficiency',
    ];

    protected $casts = [
        'expected_salary' => 'decimal:2',
        'salary' => 'decimal:2',
        'company_service_charge' => 'decimal:2',
        'agent_charge' => 'decimal:2',
        'medical_cost' => 'decimal:2',
        'training_cost' => 'decimal:2',
        'visa_cost' => 'decimal:2',
        'other_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'advance_amount' => 'decimal:2',
        'remaining_due' => 'decimal:2',
        'language_proficiency' => 'array',
    ];

    public function passportHolder(): BelongsTo
    {
        return $this->belongsTo(PassportHolder::class, 'passport_holder_id');
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

    public function stageHistories(): HasMany
    {
        return $this->hasMany(RecruitmentStageHistory::class, 'recruitment_id');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(RecruitmentDocument::class, 'recruitment_id');
    }

    public function selection(): HasOne
    {
        return $this->hasOne(Selection::class, 'recruitment_id');
    }

    public function delegation(): HasOne
    {
        return $this->hasOne(Delegation::class, 'recruitment_id');
    }

    public function medical(): HasOne
    {
        return $this->hasOne(Medical::class, 'recruitment_id');
    }

    public function policeClearance(): HasOne
    {
        return $this->hasOne(PoliceClearance::class, 'recruitment_id');
    }

    public function training(): HasOne
    {
        return $this->hasOne(Training::class, 'recruitment_id');
    }

    public function fingerprint(): HasOne
    {
        return $this->hasOne(Fingerprint::class, 'recruitment_id');
    }

    public function manpowerApproval(): HasOne
    {
        return $this->hasOne(ManpowerApproval::class, 'recruitment_id');
    }

    public function visa(): HasOne
    {
        return $this->hasOne(Visa::class, 'recruitment_id');
    }

    public function flight(): HasOne
    {
        return $this->hasOne(Flight::class, 'recruitment_id');
    }

    public function postArrival(): HasOne
    {
        return $this->hasOne(PostArrival::class, 'recruitment_id');
    }

    public function passportReturn(): HasOne
    {
        return $this->hasOne(PassportReturn::class, 'recruitment_id');
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
            $holderId = $this->isolationHolderId($user);

            return $holderId ? $query->where('passport_holder_id', $holderId) : $query->whereRaw('1 = 0');
        }

        if ($user->hasRole('Agent')) {
            $agentId = $this->isolationAgentId($user);

            return $agentId ? $query->where('agent_id', $agentId) : $query->whereRaw('1 = 0');
        }

        return $this->isolationApplyBranchCountry($query, $user, true, true);
    }
}
