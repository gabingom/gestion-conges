<?php

namespace App\Http\Controllers;

use App\Models\Conge;
use App\Models\Agent;
use Illuminate\Http\Request;

class CongeController extends Controller
{
    /**
     * Afficher la liste des demandes de congés avec pagination.
     */
    public function index()
    {
        // Utilisation de paginate(10) pour de meilleures performances futures
        $conges = Conge::with('agent')->latest()->paginate(10);
        return view('conges.index', compact('conges'));
    }

    /**
     * Afficher le formulaire de création d'une demande.
     */
    public function create()
    {
        $agents = Agent::all();
        return view('conges.create', compact('agents'));
    }

    /**
     * Enregistrer une nouvelle demande de congé.
     */
    public function store(Request $request)
    {
        $request->validate([
            'agent_id' => 'required|exists:agents,id',
            'type_conge' => 'required|string',
            'jours_a_prendre' => 'required|integer|min:1',
            'date_cessation' => 'required|date',
            'statut' => 'required|in:En attente,Approuvé,Refusé',
            'commentaire' => 'nullable|string',
            'justificatif' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
        ]);

        $justificatifPath = null;
        if ($request->hasFile('justificatif')) {
            $justificatifPath = $request->file('justificatif')->store('justificatifs', 'public');
        }

        $isDeductible = $request->has('deductible') ? 1 : 0;
        $isExceptionnel = ($request->type_conge !== 'Annuel') ? 1 : 0;

        Conge::create([
            'agent_id' => $request->agent_id,
            'type_conge' => $request->type_conge,
            'justificatif_path' => $justificatifPath,
            'jours_a_prendre' => $request->jours_a_prendre,
            'date_cessation' => $request->date_cessation,
            'statut' => $request->statut,
            'deductible' => $isDeductible,
            'exceptionnel' => $isExceptionnel,
            'commentaire' => $request->commentaire,
        ]);

        return redirect()->route('conges.index')
            ->with('success', 'La demande de congé a été enregistrée avec succès.');
    }

    /**
     * Afficher les détails d'une demande spécifique (RÉPARÉ).
     */
    public function show(Conge $conge)
    {
        // Ligne magique : charge l'agent lié pour remplir la carte de droite !
        $conge->load('agent');

        return view('conges.show', compact('conge'));
    }

    /**
     * Afficher le formulaire de modification.
     */
    public function edit(Conge $conge)
    {
        $agents = Agent::all();
        return view('conges.edit', compact('conge', 'agents'));
    }

    /**
     * Mettre à jour la demande de congé.
     */
    public function update(Request $request, Conge $conge)
    {
        $request->validate([
            'agent_id' => 'required|exists:agents,id',
            'type_conge' => 'required|string',
            'jours_a_prendre' => 'required|integer|min:1',
            'date_cessation' => 'required|date',
            'statut' => 'required|in:En attente,Approuvé,Refusé',
            'commentaire' => 'nullable|string',
            'justificatif' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
        ]);

        if ($request->hasFile('justificatif')) {
            $justificatifPath = $request->file('justificatif')->store('justificatifs', 'public');
            $conge->justificatif_path = $justificatifPath;
        }

        $isDeductible = $request->has('deductible') ? 1 : 0;
        $isExceptionnel = ($request->type_conge !== 'Annuel') ? 1 : 0;

        $conge->update([
            'agent_id' => $request->agent_id,
            'type_conge' => $request->type_conge,
            'jours_a_prendre' => $request->jours_a_prendre,
            'date_cessation' => $request->date_cessation,
            'statut' => $request->statut,
            'deductible' => $isDeductible,
            'exceptionnel' => $isExceptionnel,
            'commentaire' => $request->commentaire,
        ]);

        return redirect()->route('conges.index')
            ->with('success', 'La demande de congé a été mise à jour.');
    }

    /**
     * Supprimer la demande de congé.
     */
    public function destroy(Conge $conge)
    {
        $conge->delete();
        return redirect()->route('conges.index')
            ->with('success', 'La demande de congé a été supprimée.');
    }

    /**
     * Approuver une demande de congé.
     */
    public function approuver(Conge $conge)
    {
        $conge->update(['statut' => 'Approuvé']);
        return back()->with('success', 'La demande de congé a été approuvée.');
    }

    /**
     * Refuser une demande de congé.
     */
    public function refuser(Conge $conge)
    {
        $conge->update(['statut' => 'Refusé']);
        return back()->with('success', 'La demande de congé a été refusée.');
    }
}