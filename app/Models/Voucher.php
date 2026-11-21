<?php

namespace App\Models;

use App\Models\Concerns\HasDataIsolation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Voucher extends Model
{
    use HasFactory, HasDataIsolation, SoftDeletes;

    protected $table = 'vouchers';

    protected $fillable = [
        'voucher_number',
        'voucher_type',
        'voucher_date',
        'branch_id',
        'reference_type',
        'reference_id',
        'total_debit',
        'total_credit',
        'status',
        'posted_by',
        'posted_at',
        'reversed_by',
        'reversal_voucher_id',
        'narration',
        'draft_lines',
        'payment_mode_id',
        'payee',
        'attachment_path',
        'attachment_original_name',
    ];

    protected $casts = [
        'voucher_date' => 'date',
        'total_debit' => 'decimal:2',
        'total_credit' => 'decimal:2',
        'posted_at' => 'datetime',
        'draft_lines' => 'array',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function paymentMode(): BelongsTo
    {
        return $this->belongsTo(PaymentMode::class);
    }

    public function journalEntry(): HasOne
    {
        return $this->hasOne(JournalEntry::class, 'voucher_id');
    }

    public function postedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'posted_by');
    }

    public function reversedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reversed_by');
    }

    public function reversalVoucher(): BelongsTo
    {
        return $this->belongsTo(Voucher::class, 'reversal_voucher_id');
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
            $recruitmentIds = \App\Models\Recruitment::query()
                ->visibleTo($user)
                ->pluck('id')
                ->all();

            return $query->where('reference_type', \App\Models\Recruitment::class)
                ->whereIn('reference_id', $recruitmentIds);
        }

        return $this->isolationApplyBranchCountry($query, $user, true, false);
    }
}
