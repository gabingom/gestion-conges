<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\IdentifiantsAgent;

class AgentController extends Controller
{
    /**
     * Liste des agents (pagination).
     */
    public function index()
    {
        $agents = Agent::orderBy('created_at', 'desc')->paginate(10);
        return view('agents.index', compact('agents'));
    }

    /**
     * Formulaire de création.
     */
    public function create()
    {
        return view('agents.create');
    }

    /**
     * Enregistre un agent ET crée automatiquement son compte de connexion (rôle employé).
     * L'identifiant et le mot de passe générés sont affichés une seule fois à l'admin.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom'                => 'required|string|max:255',
            'prenom'             => 'required|string|max:255',
            'matricule_solde'    => 'required|string|max:50|unique:agents,matricule_solde',
            'sexe'               => 'required|in:homme,femme',
            'type_agent'         => 'required|in:titulaire,contractuel',
            'nombre_enfants'     => 'nullable|integer|min:0',
            'lieu_affectation'   => 'required|string|max:255',
            'date_prise_service' => 'required|date',
            'email'              => 'required|email|max:255|unique:users,email',
        ]);

        // 1) Identifiant de connexion : l'adresse réelle de l'agent
        $email = $request->email;

        // 2) Mot de passe temporaire
        $motDePasse = Str::password(10, true, true, false);

        // 3) Création du compte employé
        $user = User::create([
            'name'     => $request->prenom . ' ' . $request->nom,
            'email'    => $email,
            'password' => Hash::make($motDePasse),
            'role'     => 'employe',
            'must_change_password' => true,   // devra le changer à la 1re connexion
        ]);

        // 4) Création de la fiche agent liée
        $agent = Agent::create([
            'user_id'            => $user->id,
            'nom'                => $request->nom,
            'prenom'             => $request->prenom,
            'matricule_solde'    => $request->matricule_solde,
            'sexe'               => $request->sexe,
            'type_agent'         => $request->type_agent,
            'nombre_enfants'     => $request->nombre_enfants ?? 0,
            'lieu_affectation'   => $request->lieu_affectation,
            'date_prise_service' => $request->date_prise_service,
        ]);

        // 5) Envoi des identifiants par courriel
        $envoye = true;
        try {
            Mail::to($email)->send(new IdentifiantsAgent(
                $request->prenom . ' ' . $request->nom,
                $email,
                $motDePasse,
                route('login')
            ));
        } catch (\Throwable $e) {
            $envoye = false;
            Log::error("Envoi des identifiants impossible pour {$email} : " . $e->getMessage());
        }

        // 6) Retour vers la fiche, avec les identifiants affichés une seule fois
        return redirect()->route('agents.show', $agent->id)
            ->with('success', $envoye
                ? "Agent créé. Les identifiants ont été envoyés à {$email}."
                : "Agent créé, mais l'envoi du courriel a échoué. Communiquez les identifiants manuellement.")
            ->with('mail_envoye', $envoye)
            ->with('new_account_email', $email)
            ->with('new_account_password', $motDePasse);
    }

    /**
     * Fiche d'un agent.
     */
    public function show(Agent $agent)
    {
        return view('agents.show', compact('agent'));
    }

    /**
     * Formulaire d'édition.
     */
    public function edit(Agent $agent)
    {
        return view('agents.edit', compact('agent'));
    }

    /**
     * Mise à jour de la fiche agent.
     */
    public function update(Request $request, Agent $agent)
    {
        $request->validate([
            'nom'                => 'required|string|max:255',
            'prenom'             => 'required|string|max:255',
            'matricule_solde'    => 'required|string|max:50|unique:agents,matricule_solde,' . $agent->id,
            'sexe'               => 'required|in:homme,femme',
            'type_agent'         => 'required|in:titulaire,contractuel',
            'nombre_enfants'     => 'nullable|integer|min:0',
            'lieu_affectation'   => 'required|string|max:255',
            'date_prise_service' => 'required|date',
            'jours_conges_dus'   => 'nullable|integer|min:0',
            'jours_reportes'     => 'nullable|integer|min:0',
        ]);

        $agent->update($request->only([
            'nom', 'prenom', 'matricule_solde', 'sexe', 'type_agent',
            'nombre_enfants', 'lieu_affectation', 'date_prise_service',
            'jours_conges_dus', 'jours_reportes',
        ]));

        return redirect()->route('agents.index')
            ->with('success', 'Les informations de l\'agent ont été mises à jour !');
    }

    /**
     * Suppression de l'agent (et de son compte lié).
     */
    public function destroy(Agent $agent)
    {
        if ($agent->user) {
            $agent->user->delete();
        }
        $agent->delete();

        return redirect()->route('agents.index')
            ->with('success', 'L\'agent a été supprimé avec succès !');
    }

}
