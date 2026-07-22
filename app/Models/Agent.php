<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Agent extends Model
{
    /* Règles métier (règlement intérieur) */
    public const DROIT_ANNUEL      = 24;   // jours acquis par année de service
    public const CUMUL_MAX         = 72;   // cumul maximal (3 ans)
    public const MATERNITE_AVANT   = 42;   // 6 semaines avant accouchement
    public const MATERNITE_APRES   = 56;   // 8 semaines après accouchement

    protected $fillable = [
        'user_id',
        'nom',
        'prenom',
        'matricule_solde',
        'lieu_affectation',
        'type_agent',
        'sexe',
        'nombre_enfants',
        'date_prise_service',
        'jours_conges_dus',
        'jours_reportes',
    ];

    protected $casts = [
        'date_prise_service' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function conges()
    {
        return $this->hasMany(Conge::class);
    }

    public function absences()
    {
        return $this->hasMany(Absence::class);
    }

    /* ==================== ANCIENNETÉ ==================== */

    /** Nombre d'années de service complètes. */
    public function anneesService(): int
    {
        if (!$this->date_prise_service) {
            return 0;
        }
        return (int) $this->date_prise_service->diffInYears(Carbon::now());
    }

    /** L'agent a-t-il accompli au moins 1 an de service ? */
    public function aUnAnDeService(): bool
    {
        return $this->anneesService() >= 1;
    }

    /** Date à laquelle l'agent ouvrira droit au congé annuel. */
    public function dateOuvertureDroit(): ?Carbon
    {
        return $this->date_prise_service
            ? $this->date_prise_service->copy()->addYear()
            : null;
    }

    /* ==================== DROITS AUX CONGÉS ==================== */

    /**
     * Jour(s) supplémentaire(s) pour une mère d'enfants de moins de 14 ans :
     * 1 jour par enfant.
     */
    public function bonusEnfants(): int
    {
        if ($this->sexe !== 'femme') {
            return 0;
        }
        return (int) ($this->nombre_enfants ?? 0);
    }

    /**
     * Total des jours de congé annuel acquis.
     *
     * Aucun droit n'est ouvert avant 1 année de service accomplie :
     * ni les 24 jours annuels, ni le bonus pour enfants à charge.
     * Au-delà : 24 jours par année, plafonné à 72 jours (3 ans),
     * augmenté d'un jour par enfant de moins de 14 ans (mères).
     */
    public function joursAcquis(): int
    {
        // Droits non encore ouverts -> aucun jour acquis
        if (!$this->aUnAnDeService()) {
            return 0;
        }

        $acquis = $this->anneesService() * self::DROIT_ANNUEL;
        $acquis = min($acquis, self::CUMUL_MAX);

        return $acquis + $this->bonusEnfants();
    }

    /** Jours de congé annuel déjà consommés (demandes approuvées + en attente). */
    public function joursConsommes(): int
    {
        return (int) $this->conges()
            ->where('deductible', true)
            ->whereNotIn('statut', ['Refusé', 'refuse'])
            ->sum('jours_a_prendre');
    }

    /** Solde de congé annuel restant. */
    public function soldeDisponible(): int
    {
        return max(0, $this->joursAcquis() - $this->joursConsommes());
    }

    /** L'agent atteint-il le plafond de cumul ? */
    public function plafondAtteint(): bool
    {
        return $this->anneesService() >= 3;
    }
}
