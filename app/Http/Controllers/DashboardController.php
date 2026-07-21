<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Conge;
use App\Models\Absence;

class DashboardController extends Controller
{
    public function index()
    {
        $totalAgents = Agent::count();
        $totalConges = Conge::count();
        $totalAbsences = Absence::count();
        $congesEnAttente = Conge::where('statut', 'en_attente')->count();
        $congesApprouves = Conge::where('statut', 'approuve')->count();
        $congesRefuses = Conge::where('statut', 'refuse')->count();
        $dernierAgents = Agent::latest()->take(5)->get();

        // Sécurité : On ignore les affectations vides pour ne pas casser l'affichage de Chart.js
        $agentsParAffectation = Agent::selectRaw('lieu_affectation, count(*) as total')
            ->whereNotNull('lieu_affectation')
            ->where('lieu_affectation', '<>', '')
            ->groupBy('lieu_affectation')
            ->get();

        return view('dashboard', compact(
            'totalAgents',
            'totalConges',
            'totalAbsences',
            'congesEnAttente',
            'congesApprouves',
            'congesRefuses',
            'dernierAgents',
            'agentsParAffectation'
        ));
    }
}
