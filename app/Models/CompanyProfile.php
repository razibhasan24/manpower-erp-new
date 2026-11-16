<?php

namespace App\Models;

use App\Models\Concerns\HasDataIsolation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyProfile extends Model
{
    use HasFactory, HasDataIsolation;

    protected $table = 'company_profiles';

    protected $fillable = [
        'name',
        'software_name',
        'software_short_name',
        'logo_path',
        'favicon_path',
        'login_background_path',
        'address',
        'phone',
        'email',
        'website',
        'primary_color',
        'secondary_color',
        'currency',
        'language',
        'timezone',
        'date_format',
        'invoice_prefix',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];


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
