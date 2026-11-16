<?php

namespace App\Models;

use App\Models\Concerns\HasDataIsolation;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasRoles, HasDataIsolation, Notifiable;

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'branch_id',
        'status',
        'last_login_at',
        'must_change_password',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'password' => 'hashed',
        'must_change_password' => 'boolean',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function agent(): HasOne
    {
        return $this->hasOne(Agent::class, 'user_id');
    }

    public function passportHolder(): HasOne
    {
        return $this->hasOne(PassportHolder::class, 'user_id');
    }

    public function employee(): HasOne
    {
        return $this->hasOne(Employee::class, 'user_id');
    }

    public function loginHistories(): HasMany
    {
        return $this->hasMany(LoginHistory::class, 'user_id');
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class, 'user_id');
    }

    /**
     * Send the branded, single-use password reset link (Phase 01 auth).
     * The token, expiry and throttling are still owned by Laravel's broker.
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new \App\Notifications\ResetPasswordNotification((string) $token));
    }

    public function branches(): BelongsToMany
    {
        return $this->belongsToMany(Branch::class, 'user_branch');
    }

    public function countries(): BelongsToMany
    {
        return $this->belongsToMany(Country::class, 'user_country');
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

        if ($user->hasRole('Agent') || $user->hasRole('Passport Holder')) {
            return $query->where('id', $user->id);
        }

        return $this->isolationApplyBranchCountry($query, $user, true, false);
    }
}
