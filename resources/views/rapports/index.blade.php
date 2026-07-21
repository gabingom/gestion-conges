@extends('layouts.app')

@section('title', 'Rapports et Statistiques')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h2><i class="bi bi-file-earmark-bar-graph text-muted me-2"></i> Rapports & Analyses</h2>
        <nav class="breadcrumb"><span>RH / Génération de rapports</span></nav>
    </div>
</div>

<!-- Section des filtres -->
<div class="card mb-4 shadow-sm border-0">
    <div class="card-header bg-transparent fw-bold text-dark">
        <i class="bi bi-filter-left me-1"></i> Critères de génération du rapport
    </div>
    <div class="card-body">
        <form action="{{ route('rapports.generer') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Période du</label>
                <input type="date" name="date_debut" class="form-control" value="{{ request('date_debut') }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Au</label>
                <input type="date" name="date_fin" class="form-control" value="{{ request('date_fin') }}">
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-danger flex-fill">
                    <i class="bi bi-gear-wide-connected me-1"></i> Générer le rapport
                </button>
                @if(request()->has('date_debut'))
                    <a href="{{ route('rapports.export-pdf', request()->all()) }}" class="btn btn-outline-danger" target="_blank" title="Exporter en PDF">
                        <i class="bi bi-file-earmark-pdf-fill"></i> PDF
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Grille des statistiques globales (s'affiche uniquement si un rapport est généré) -->
@if(isset($stats))
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 bg-success-subtle text-success p-3 rounded-3 d-flex flex-row align-items-center justify-content-between">
            <div>
                <h6 class="text-uppercase mb-1 small fw-bold">Congés Approuvés</h6>
                <h3 class="mb-0 fw-bold">{{ $stats['conges_approuves'] ?? 0 }}</h3>
            </div>
            <i class="bi bi-calendar-check-fill display-6 opacity-50"></i>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 bg-danger-subtle text-danger p-3 rounded-3 d-flex flex-row align-items-center justify-content-between">
            <div>
                <h6 class="text-uppercase mb-1 small fw-bold">Total Jours Absences</h6>
                <h3 class="mb-0 fw-bold">{{ $stats['total_jours_absences'] ?? 0 }} j</h3>
            </div>
            <i class="bi bi-clock-fill display-6 opacity-50"></i>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 bg-warning-subtle text-warning-heading p-3 rounded-3 d-flex flex-row align-items-center justify-content-between">
            <div>
                <h6 class="text-uppercase mb-1 small fw-bold">Demandes en Attente</h6>
                <h3 class="mb-0 fw-bold">{{ $stats['conges_en_attente'] ?? 0 }}</h3>
            </div>
            <i class="bi bi-hourglass-split display-6 opacity-50"></i>
        </div>
    </div>
</div>

<!-- Tableau des mouvements sur la période -->
<div class="card shadow-sm border-0">
    <div class="card-header bg-transparent fw-bold text-dark">
        Détails des mouvements sur la période sélectionnée
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Agent</th>
                        <th>Type d'événement</th>
                        <th>Durée</th>
                        <th>Du ... Au ...</th>
                        <th>Statut / Motif</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mouvements ?? [] as $mouvement)
                    <tr>
                        <td>
                            <strong class="text-dark">{{ $mouvement['agent_nom'] }}</strong>
                            <div class="text-muted small">{{ $mouvement['agent_matricule'] }}</div>
                        </td>
                        <td>
                            <span class="badge {{ $mouvement['type'] === 'Congé' ? 'bg-primary-subtle text-primary' : 'bg-purple-subtle text-purple' }} px-2 py-1">
                                {{ $mouvement['type'] }}
                            </span>
                        </td>
                        <td><span class="fw-bold">{{ $mouvement['jours'] }} j</span></td>
                        <td>
                            <small class="text-muted">
                                {{ \Carbon\Carbon::parse($mouvement['debut'])->format('d/m/Y') }} au {{ \Carbon\Carbon::parse($mouvement['fin'])->format('d/m/Y') }}
                            </small>
                        </td>
                        <td>
                            <span class="text-muted small">{{ $mouvement['details'] }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            Aucun mouvement (congé ou absence) trouvé pour ces dates.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@else
<div class="card border-0 shadow-sm text-center py-5 bg-light">
    <div class="card-body">
        <i class="bi bi-arrow-up-circle display-4 text-muted opacity-50 mb-3 d-block"></i>
        <h5 class="text-dark fw-bold">Aucun rapport généré</h5>
        <p class="text-muted mb-0">Veuillez sélectionner une période ci-dessus pour compiler les statistiques des ressources humaines.</p>
    </div>
</div>
@endif
@endsection
