<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnavailabilityPeriod extends Model
{
    protected $fillable = [
        'resource_id',
        'created_by',
        'reason',
        'type',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date'   => 'datetime',
    ];

    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Vérifie si la période est actuellement active (en cours).
     */
    public function isActive(): bool
    {
        $now = now();
        return $this->start_date <= $now && $this->end_date >= $now;
    }
}
