<?php

namespace App\Models;

use App\Models\Concerns\HasDataIsolation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use HasFactory, HasDataIsolation, SoftDeletes;

    protected $table = 'accounts';

    protected $fillable = [
        'code',
        'name',
        'parent_id',
        'account_type_id',
        'normal_balance',
        'status',
        'description',
    ];

    public function type(): BelongsTo
    {
        return $this->belongsTo(AccountType::class, 'account_type_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Account::class, 'parent_id');
    }

    public function lines(): HasMany
    {
        return $this->hasMany(JournalEntryLine::class, 'account_id');
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
