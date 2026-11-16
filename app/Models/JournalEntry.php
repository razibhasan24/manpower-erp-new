<?php

namespace App\Models;

use App\Models\Concerns\HasDataIsolation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JournalEntry extends Model
{
    use HasFactory, HasDataIsolation, SoftDeletes;

    protected $table = 'journal_entries';

    protected $fillable = [
        'voucher_id',
        'entry_date',
        'narration',
        'branch_id',
    ];

    protected $casts = [
        'entry_date' => 'date',
    ];

    public function voucher(): BelongsTo
    {
        return $this->belongsTo(Voucher::class, 'voucher_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function lines(): HasMany
    {
        return $this->hasMany(JournalEntryLine::class, 'journal_entry_id');
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
            return $query->whereHas('voucher', function ($q) use ($user) {
                $q->visibleTo($user);
            });
        }

        return $this->isolationApplyBranchCountry($query, $user, true, false);
    }
}
