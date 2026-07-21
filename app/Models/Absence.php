<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absence extends Model
{
    protected $fillable = [
        'agent_id',
        'statut',
        'nombre_jours',
        'date_debut',
        'date_fin',
        'motif',
        'deductible',
        'valide_par_medecin',
        'rappelable',
        'document_justificatif',
        'commentaire',
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'deductible' => 'boolean',
        'valide_par_medecin' => 'boolean',
        'rappelable' => 'boolean',
    ];

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    // Jours fixes selon le motif (vos règles métier)
    public static function joursParMotif(string $motif): ?int
    {
        return match($motif) {
            'mariage'          => 3,
            'naissance'        => 1,
            'bapteme'          => 1,
            'deces_ascendant'  => 2,
            'deces_descendant' => 4,
            default            => null, // variable : maladie, grossesse, accident_travail, etc.
        };
    }

    // Motifs non déductibles des 24 jours de congé
    public static function motifsNonDeductibles(): array
    {
        return [
            'mariage',
            'naissance',
            'bapteme',
            'deces_ascendant',
            'deces_descendant',
            'grossesse',
            'accident_travail',
        ];
    }
}
