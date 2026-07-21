<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;

class RapportController extends Controller
{
    public function index()
    {
        return view('rapports.index');
    }

    public function generer(Request $request)
    {
        $type = $request->type;
        $date_debut = $request->date_debut;
        $date_fin = $request->date_fin;

        $agents = Agent::query();

        if ($type == 'direction') {
            $agents = $agents->where('lieu_affectation', 'like', '%Direction%');
        } elseif ($type == 'ufr') {
            $agents = $agents->where('lieu_affectation', 'like', '%UFR%');
        } elseif ($type == 'rectorat') {
            $agents = $agents->where(function ($q) {
                $q->where('lieu_affectation', 'like', '%Rectorat%')
                  ->orWhere('lieu_affectation', 'like', '%Vice-Recteur%');
            });
        }

        if ($date_debut && $date_fin) {
            $agents = $agents->whereBetween('date_prise_service', [$date_debut, $date_fin]);
        }

        $agents = $agents->get();

        return view('rapports.generer', compact('agents', 'type', 'date_debut', 'date_fin'));
    }

    public function exportPdf(Request $request)
    {
        $type = $request->type ?? 'global';
        $date_debut = $request->date_debut;
        $date_fin = $request->date_fin;

        $agents = Agent::query();

        if ($type == 'direction') {
            $agents = $agents->where('lieu_affectation', 'like', '%Direction%');
        } elseif ($type == 'ufr') {
            $agents = $agents->where('lieu_affectation', 'like', '%UFR%');
        } elseif ($type == 'rectorat') {
            $agents = $agents->where(function ($q) {
                $q->where('lieu_affectation', 'like', '%Rectorat%')
                  ->orWhere('lieu_affectation', 'like', '%Vice-Recteur%');
            });
        }

        if ($date_debut && $date_fin) {
            $agents = $agents->whereBetween('date_prise_service', [$date_debut, $date_fin]);
        }

        $agents = $agents->get();

        try {
            $html = view('rapports.pdf', compact('agents', 'type', 'date_debut', 'date_fin'))->render();

            $pdf = Pdf::loadHTML($html);
            $pdf->setPaper('A4', 'landscape');

            // Nom de fichier personnalisé avec USSEIN
            $nomFichier = 'Rapport-USSEIN-' . $type . ($date_debut ? '-du-'.$date_debut.'-au-'.$date_fin : '') . '.pdf';

            return $pdf->download($nomFichier);

        } catch (Exception $e) {
            die("Erreur PDF : " . $e->getMessage());
        }
    }
}
