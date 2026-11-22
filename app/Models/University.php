<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class University extends Model
{
    use SoftDeletes;

    protected $fillable = ['code', 'name', 'country', 'city', 'contact_email', 'contact_phone', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];
}
