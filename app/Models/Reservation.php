<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    // Champs autorisés pour la création en masse
    protected $fillable = [
        'user_id',
        'resource_id',
        'start_date',
        'end_date',
        'justification',
        'status',           // pending, approved, rejected, active, completed
        'manager_feedback'  // Motif de refus ou commentaire
    ];

    // Relation : Une réservation appartient à un utilisateur
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relation : Une réservation concerne une ressource
    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }
}