<?php

namespace App\Models;

use App\Models\Concerns\HasDataIsolation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vendor extends Model
{
    use HasDataIsolation, SoftDeletes;

    protected $fillable = ['name', 'phone', 'email', 'address', 'branch_id', 'payable_account_id', 'status', 'created_by'];

    public function payableAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'payable_account_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function scopeVisibleTo($query, User $user)
    {
        if ($user->hasRole('Super Admin') || $user->hasRole('Admin')) {
            return $query;
        }

        return $query->when($user->branch_id, fn ($q) => $q->where('branch_id', $user->branch_id));
    }
}
