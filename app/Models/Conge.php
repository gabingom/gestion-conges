<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conge extends Model
{
    protected $fillable = [
        'agent_id',
        'type_conge',       // Ajouté
        'justificatif_path', // Ajouté
        'jours_a_prendre',   // Ton champ d'origine
        'date_cessation',    // Ton champ d'origine
        'date_reprise',
        'statut',
        'commentaire',
        'exceptionnel',
        'deductible',
    ];

    protected $casts = [
        'date_cessation' => 'date',
        'date_reprise' => 'date',
        'exceptionnel' => 'boolean',
        'deductible' => 'boolean',
    ];

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }
}
