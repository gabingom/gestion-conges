<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Conge;
use App\Models\Absence;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class RapportController extends Controller
{
    /** Les quatre rapports disponibles. */
    public const RAPPORTS = [
        'agents'   => 'Agents inscrits',
        'conges'   => 'Demandes de congé',
        'absences' => 'Demandes d\'absence',
        'complet'  => 'Rapport complet',
    ];

    public function index()
    {
        return view('rapports.index');
    }

    /**
     * Aperçu à l'écran avant génération du PDF.
     */
    public function generer(Request $request)
    {
        $data = $this->collecter($request);
        return view('rapports.generer', $data);
    }

    /**
     * Téléchargement du PDF.
     */
    public function exportPdf(Request $request)
    {
        $data = $this->collecter($request);

        $html = view('rapports.pdf', $data)->render();

        $pdf = Pdf::loadHTML($html);
        $pdf->setPaper('A4', $data['type'] === 'agents' ? 'landscape' : 'portrait');

        $nom = 'rapport-' . $data['type']
             . ($data['date_debut'] ? '-du-' . $data['date_debut'] . '-au-' . $data['date_fin'] : '')
             . '.pdf';

        return $pdf->download($nom);
    }

    /**
     * Rassemble les données selon le type de rapport et la période.
     */
    private function collecter(Request $request): array
    {
        $request->validate([
            'type'       => 'required|in:agents,conges,absences,complet',
            'date_debut' => 'nullable|date',
            'date_fin'   => 'nullable|date|after_or_equal:date_debut',
            'affectation'=> 'nullable|string',
        ]);

        $type        = $request->type;
        $dateDebut   = $request->date_debut;
        $dateFin     = $request->date_fin;
        $affectation = $request->affectation;

        $agents = $conges = $absences = collect();
        $statsConges = $statsAbsences = [];

        /* ---------- AGENTS ---------- */
        if (in_array($type, ['agents', 'complet'])) {
            $q = Agent::query();

            if ($affectation) {
                $q->where('lieu_affectation', 'like', '%' . $affectation . '%');
            }
            // Pour les agents, la période porte sur la date de prise de service
            if ($dateDebut && $dateFin) {
                $q->whereBetween('date_prise_service', [$dateDebut, $dateFin]);
            }

            $agents = $q->orderBy('nom')->get();
        }

        /* ---------- CONGÉS ---------- */
        if (in_array($type, ['conges', 'complet'])) {
            $q = Conge::with('agent');

            if ($dateDebut && $dateFin) {
                $q->whereBetween('created_at', [
                    Carbon::parse($dateDebut)->startOfDay(),
                    Carbon::parse($dateFin)->endOfDay(),
                ]);
            }
            if ($affectation) {
                $q->whereHas('agent', function ($sq) use ($affectation) {
                    $sq->where('lieu_affectation', 'like', '%' . $affectation . '%');
                });
            }

            $conges = $q->orderBy('created_at', 'desc')->get();

            $statsConges = [
                'recues'     => $conges->count(),
                'approuvees' => $conges->filter(fn($c) => $this->estApprouve($c->statut))->count(),
                'refusees'   => $conges->filter(fn($c) => $this->estRefuse($c->statut))->count(),
                'attente'    => $conges->filter(fn($c) => $this->estEnAttente($c->statut))->count(),
                'jours'      => $conges->filter(fn($c) => $this->estApprouve($c->statut))->sum('jours_a_prendre'),
            ];
        }

        /* ---------- ABSENCES ---------- */
        if (in_array($type, ['absences', 'complet'])) {
            $q = Absence::with('agent');

            if ($dateDebut && $dateFin) {
                $q->whereBetween('created_at', [
                    Carbon::parse($dateDebut)->startOfDay(),
                    Carbon::parse($dateFin)->endOfDay(),
                ]);
            }
            if ($affectation) {
                $q->whereHas('agent', function ($sq) use ($affectation) {
                    $sq->where('lieu_affectation', 'like', '%' . $affectation . '%');
                });
            }

            $absences = $q->orderBy('created_at', 'desc')->get();

            $statsAbsences = [
                'recues'     => $absences->count(),
                'approuvees' => $absences->filter(fn($a) => $this->estApprouve($a->statut))->count(),
                'refusees'   => $absences->filter(fn($a) => $this->estRefuse($a->statut))->count(),
                'attente'    => $absences->filter(fn($a) => $this->estEnAttente($a->statut))->count(),
                'jours'      => $absences->filter(fn($a) => $this->estApprouve($a->statut))->sum('nombre_jours'),
            ];
        }

        return [
            'type'          => $type,
            'titre'         => self::RAPPORTS[$type],
            'date_debut'    => $dateDebut,
            'date_fin'      => $dateFin,
            'affectation'   => $affectation,
            'agents'        => $agents,
            'conges'        => $conges,
            'absences'      => $absences,
            'statsConges'   => $statsConges,
            'statsAbsences' => $statsAbsences,
            'genereLe'      => now(),
        ];
    }

    /* --- Les statuts existent sous deux écritures dans la base --- */
    private function estApprouve(?string $s): bool
    {
        return in_array($s, ['Approuvé', 'approuve']);
    }

    private function estRefuse(?string $s): bool
    {
        return in_array($s, ['Refusé', 'refuse']);
    }

    private function estEnAttente(?string $s): bool
    {
        return !$this->estApprouve($s) && !$this->estRefuse($s);
    }
}
