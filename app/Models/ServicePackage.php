<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServicePackage extends Model
{
    public const TYPES = ['student', 'hajj_umrah'];

    protected $fillable = [
        'type', 'code', 'name', 'duration_days', 'currency', 'price',
        'description', 'inclusions', 'is_active',
    ];

    protected $casts = [
        'duration_days' => 'integer',
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];
}
