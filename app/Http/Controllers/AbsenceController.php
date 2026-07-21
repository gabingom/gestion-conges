<?php

namespace App\Http\Controllers;

use App\Models\Absence;
use App\Models\Agent;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AbsenceController extends Controller
{
    public function index()
    {
        $absences = Absence::with('agent')->latest()->paginate(10);
        return view('absences.index', compact('absences'));
    }

    public function create()
    {
        $agents = Agent::all();
        return view('absences.create', compact('agents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'agent_id' => 'required|exists:agents,id',
            'motif' => 'required|string',
            'date_debut' => 'required|date',
            'nombre_jours' => 'nullable|integer|min:1',
            'commentaire' => 'nullable|string',
            'document_justificatif' => 'nullable|string',
        ]);

        // Appliquer automatiquement les jours et la déductibilité selon le motif
        $joursFixe = Absence::joursParMotif($request->motif);
        $nombreJours = $joursFixe ?? $request->nombre_jours;
        $deductible = !in_array($request->motif, Absence::motifsNonDeductibles());

        // Calcul date de fin
        $dateFin = Carbon::parse($request->date_debut)
            ->addDays($nombreJours - 1)
            ->format('Y-m-d');

        Absence::create([
            'agent_id' => $request->agent_id,
            'nombre_jours' => $nombreJours,
            'date_debut' => $request->date_debut,
            'date_fin' => $dateFin,
            'motif' => $request->motif,
            'deductible' => $deductible,
            'valide_par_medecin' => $request->boolean('valide_par_medecin'),
            'rappelable' => $request->boolean('rappelable'),
            'document_justificatif' => $request->document_justificatif,
            'commentaire' => $request->commentaire,
        ]);

        // Déduire des congés uniquement si déductible
        if ($deductible) {
            $agent = Agent::findOrFail($request->agent_id);
            $agent->jours_conges_dus = max(0, $agent->jours_conges_dus - $nombreJours);
            $agent->save();
        }

        return redirect()->route('absences.index')
            ->with('success', 'Absence enregistrée avec succès ! (' . $nombreJours . ' jours, ' . ($deductible ? 'déductible' : 'non déductible') . ')');
    }

    public function show(Absence $absence)
    {
        return view('absences.show', compact('absence'));
    }

    public function edit(Absence $absence)
    {
        $agents = Agent::all();
        return view('absences.edit', compact('absence', 'agents'));
    }

    public function update(Request $request, Absence $absence)
    {
        $request->validate([
            'agent_id' => 'required|exists:agents,id',
            'motif' => 'required|string',
            'date_debut' => 'required|date',
            'nombre_jours' => 'nullable|integer|min:1',
            'commentaire' => 'nullable|string',
        ]);

        $joursFixe = Absence::joursParMotif($request->motif);
        $nombreJours = $joursFixe ?? $request->nombre_jours;
        $deductible = !in_array($request->motif, Absence::motifsNonDeductibles());

        $dateFin = Carbon::parse($request->date_debut)
            ->addDays($nombreJours - 1)
            ->format('Y-m-d');

        $absence->update([
            'agent_id' => $request->agent_id,
            'nombre_jours' => $nombreJours,
            'date_debut' => $request->date_debut,
            'date_fin' => $dateFin,
            'motif' => $request->motif,
            'deductible' => $deductible,
            'valide_par_medecin' => $request->boolean('valide_par_medecin'),
            'rappelable' => $request->boolean('rappelable'),
            'document_justificatif' => $request->document_justificatif,
            'commentaire' => $request->commentaire,
        ]);

        return redirect()->route('absences.index')
            ->with('success', 'Absence modifiée avec succès !');
    }

    public function destroy(Absence $absence)
    {
        $absence->delete();
        return redirect()->route('absences.index')
            ->with('success', 'Absence supprimée avec succès !');
    }

    /**
     * Approuver une demande d'absence.
     */
    public function approuver(Absence $absence)
    {
        $absence->update(['statut' => 'approuve']);
        return back()->with('success', "La demande d'absence a été approuvée.");
    }

    /**
     * Refuser une demande d'absence.
     */
    public function refuser(Absence $absence)
    {
        $absence->update(['statut' => 'refuse']);
        return back()->with('success', "La demande d'absence a été refusée.");
    }
}