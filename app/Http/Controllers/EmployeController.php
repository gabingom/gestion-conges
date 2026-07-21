<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Conge;
use App\Models\Absence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class EmployeController extends Controller
{
    /**
     * Récupère la fiche agent liée au compte connecté.
     * Si aucune fiche n'est liée, on renvoie null (géré dans les vues).
     */
    private function agent()
    {
        return Auth::user()->agent;
    }

    /* ===================== MON PROFIL ===================== */

    public function profil()
    {
        $agent = $this->agent();
        return view('employe.profil', compact('agent'));
    }

    /* ===================== RÈGLEMENT INTÉRIEUR ===================== */

    public function reglement()
    {
        $agent = $this->agent();
        return view('employe.reglement', compact('agent'));
    }

    /* ===================== MES CONGÉS ===================== */

    public function mesConges()
    {
        $agent  = $this->agent();
        $conges = $agent
            ? $agent->conges()->latest()->paginate(10)
            : collect();

        return view('employe.conges.index', compact('agent', 'conges'));
    }

    public function createConge()
    {
        $agent = $this->agent();
        return view('employe.conges.create', compact('agent'));
    }

    public function storeConge(Request $request)
    {
        $agent = $this->agent();
        if (!$agent) {
            return back()->with('error', "Aucune fiche agent n'est rattachée à votre compte. Contactez l'administration.");
        }

        $type = $request->type_conge;

        /* ============ CONGÉ DE MATERNITÉ ============
           Durée fixée par le Code du travail : 6 semaines avant
           et 8 semaines après l'accouchement. L'employée saisit
           uniquement la date prévue d'accouchement, l'application
           calcule le début, la fin et le nombre de jours.        */
        if ($type === 'Maternite') {

            if ($agent->sexe !== 'femme') {
                return back()->withInput()->with('error',
                    "Le congé de maternité est réservé aux agents de sexe féminin.");
            }

            $request->validate([
                'type_conge'         => 'required|string',
                'date_accouchement'  => 'required|date',
                'jours_prolongation' => 'nullable|integer|min:1',
                'commentaire'        => 'nullable|string',
                'justificatif'       => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
            ]);

            $accouchement  = Carbon::parse($request->date_accouchement);
            $prolongation  = (int) ($request->jours_prolongation ?? 0);

            // Toute prolongation exige un certificat médical
            if ($prolongation > 0 && !$request->hasFile('justificatif')) {
                return back()->withInput()->with('error',
                    "Code du travail : le congé de maternité est de "
                    . (Agent::MATERNITE_AVANT + Agent::MATERNITE_APRES) . " jours "
                    . "(6 semaines avant et 8 semaines après l'accouchement). "
                    . "Toute prolongation doit être justifiée par un certificat médical.");
            }

            $debut = $accouchement->copy()->subDays(Agent::MATERNITE_AVANT);
            $fin   = $accouchement->copy()->addDays(Agent::MATERNITE_APRES + $prolongation);
            $jours = Agent::MATERNITE_AVANT + Agent::MATERNITE_APRES + $prolongation;

            $justificatifPath = $request->hasFile('justificatif')
                ? $request->file('justificatif')->store('justificatifs', 'public')
                : null;

            Conge::create([
                'agent_id'          => $agent->id,
                'type_conge'        => 'Maternite',
                'justificatif_path' => $justificatifPath,
                'jours_a_prendre'   => $jours,
                'date_cessation'    => $debut->format('Y-m-d'),
                'date_reprise'      => $fin->format('Y-m-d'),
                'statut'            => 'En attente',
                'deductible'        => false,   // non déductible des 24 jours
                'exceptionnel'      => true,
                'commentaire'       => $request->commentaire,
            ]);

            return redirect()->route('employe.conges')->with('success',
                "Demande de congé de maternité envoyée : du " . $debut->format('d/m/Y')
                . " au " . $fin->format('d/m/Y') . " ({$jours} jours).");
        }

        /* ============ AUTRES CONGÉS ============ */
        $request->validate([
            'type_conge'      => 'required|string',
            'jours_a_prendre' => 'required|integer|min:1',
            'date_cessation'  => 'required|date',
            'commentaire'     => 'nullable|string',
            'justificatif'    => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
        ]);

        $jours = (int) $request->jours_a_prendre;

        if ($type === 'Annuel') {

            if (!$agent->aUnAnDeService()) {
                $date = $agent->dateOuvertureDroit();
                return back()->withInput()->with('error',
                    "Code du travail : vous devez avoir accompli au moins 1 an de service "
                    . "pour prétendre à un congé annuel."
                    . ($date ? " Vos droits seront ouverts à partir du " . $date->format('d/m/Y') . "." : ""));
            }

            $solde = $agent->soldeDisponible();
            if ($jours > $solde) {
                return back()->withInput()->with('error',
                    "Code du travail : votre solde disponible est de {$solde} jour(s). "
                    . "Vous ne pouvez pas demander {$jours} jour(s). "
                    . "Rappel : le cumul des congés est plafonné à " . Agent::CUMUL_MAX . " jours (3 ans de service).");
            }
        }

        $justificatifPath = $request->hasFile('justificatif')
            ? $request->file('justificatif')->store('justificatifs', 'public')
            : null;

        $deductible = ($type === 'Annuel');
        $debut = Carbon::parse($request->date_cessation);

        Conge::create([
            'agent_id'          => $agent->id,
            'type_conge'        => $type,
            'justificatif_path' => $justificatifPath,
            'jours_a_prendre'   => $jours,
            'date_cessation'    => $debut->format('Y-m-d'),
            'date_reprise'      => $debut->copy()->addDays($jours)->format('Y-m-d'),
            'statut'            => 'En attente',
            'deductible'        => $deductible,
            'exceptionnel'      => !$deductible,
            'commentaire'       => $request->commentaire,
        ]);

        return redirect()->route('employe.conges')
            ->with('success', "Votre demande de congé a été envoyée à l'administration. Statut : en attente.");
    }

    /* ===================== MES ABSENCES ===================== */

    public function mesAbsences()
    {
        $agent    = $this->agent();
        $absences = $agent
            ? $agent->absences()->latest()->paginate(10)
            : collect();

        return view('employe.absences.index', compact('agent', 'absences'));
    }

    public function createAbsence()
    {
        $agent = $this->agent();
        return view('employe.absences.create', compact('agent'));
    }

    public function storeAbsence(Request $request)
    {
        $agent = $this->agent();
        if (!$agent) {
            return back()->with('error', "Aucune fiche agent n'est rattachée à votre compte. Contactez l'administration.");
        }

        $request->validate([
            'motif'        => 'required|string',
            'date_debut'   => 'required|date',
            'nombre_jours' => 'nullable|integer|min:1',
            'commentaire'  => 'nullable|string',
        ]);

        // Jours fixes selon le motif (règles métier déjà définies dans le modèle Absence)
        $joursFixe   = Absence::joursParMotif($request->motif);
        $nombreJours = $joursFixe ?? ($request->nombre_jours ?? 1);
        $deductible  = !in_array($request->motif, Absence::motifsNonDeductibles());

        $dateFin = Carbon::parse($request->date_debut)
            ->addDays($nombreJours - 1)
            ->format('Y-m-d');

        Absence::create([
            'agent_id'      => $agent->id,
            'statut'        => 'en_attente',   // demande en attente de validation admin
            'nombre_jours'  => $nombreJours,
            'date_debut'    => $request->date_debut,
            'date_fin'      => $dateFin,
            'motif'         => $request->motif,
            'deductible'    => $deductible,
            'commentaire'   => $request->commentaire,
        ]);
        // NB : pas de déduction des jours de congé ici -> elle se fera côté admin à la validation.

        return redirect()->route('employe.absences')
            ->with('success', "Votre demande d'absence a été envoyée à l'administration. Statut : en attente.");
    }
}
