<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    use HasFactory;

    protected $fillable = [
        'label',
        'category',
        'description',
        'specifications',
        'location',
        'status',
        'condition',
        'internal_notes',
        'manager_id',
    ];

    protected $casts = [
        'specifications' => 'array',
    ];

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function unavailabilityPeriods()
    {
        return $this->hasMany(UnavailabilityPeriod::class)->orderBy('start_date', 'desc');
    }

    /**
     * Vérifie si une période d'indisponibilité est active en ce moment.
     */
    public function isCurrentlyUnavailable(): bool
    {
        $now = now();
        return $this->unavailabilityPeriods()
            ->where('start_date', '<=', $now)
            ->where('end_date', '>=', $now)
            ->exists();
    }

    /**
     * Retourne le label de la condition avec sa couleur.
     */
    public function conditionLabel(): array
    {
        return match($this->condition) {
            'dégradé' => ['label' => '⚠️ Dégradé',  'color' => '#e67e22'],
            'critique' => ['label' => '🔴 Critique', 'color' => '#e74c3c'],
            default    => ['label' => '✅ Bon',       'color' => '#2ecc71'],
        };
    }
}