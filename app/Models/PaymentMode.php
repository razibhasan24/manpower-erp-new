<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentMode extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'mode_type', 'account_id', 'status'];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}
