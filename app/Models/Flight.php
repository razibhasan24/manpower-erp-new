<?php

namespace App\Models;

use App\Models\Concerns\HasDataIsolation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Flight extends Model
{
    use HasFactory, HasDataIsolation, SoftDeletes;

    protected $table = 'flights';

    protected $fillable = [
        'recruitment_id',
        'airline_id',
        'flight_number',
        'travel_agency_id',
        'departure_date',
        'departure_airport',
        'arrival_airport',
        'ticket_number',
        'ticket_path',
        'noc_path',
        'status',
    ];

    protected $casts = [
        'departure_date' => 'datetime',
    ];

    public function recruitment(): BelongsTo
    {
        return $this->belongsTo(Recruitment::class, 'recruitment_id');
    }

    public function airline(): BelongsTo
    {
        return $this->belongsTo(Airline::class, 'airline_id');
    }

    public function travelAgency(): BelongsTo
    {
        return $this->belongsTo(TravelAgency::class, 'travel_agency_id');
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

        return $query->whereHas('recruitment', function ($q) use ($user) {
            $q->visibleTo($user);
        });
    }
}
