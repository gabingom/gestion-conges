@extends('layouts.app')

@section('title', 'Rapport Généré')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2><i class="bi bi-file-earmark-bar-graph text-muted me-2"></i> Rapport des Agents</h2>
        <nav class="breadcrumb"><span>Rapports / Résultat de la génération</span></nav>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('rapports.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Nouvelle recherche
        </a>
        <a href="{{ route('rapports.export-pdf', request()->query()) }}" class="btn btn-danger btn-sm">
            <i class="bi bi-file-earmark-pdf me-1"></i> Exporter en PDF
        </a>
    </div>
</div>

{{-- Bandeau récapitulatif des critères de filtrage actifs --}}
<div class="alert alert-light border shadow-sm mb-4 py-2 px-3 d-flex flex-wrap gap-3 align-items-center">
    <span class="text-muted small fw-bold text-uppercase">Filtres actifs :</span>
    <span class="badge bg-secondary text-white">Structure : {{ ucfirst($type ?? 'Toutes') }}</span>
    @if(request('date_debut') && request('date_fin'))
        <span class="badge bg-dark text-white">
            Période : {{ \Carbon\Carbon::parse(request('date_debut'))->format('d/m/Y') }} au {{ \Carbon\Carbon::parse(request('date_fin'))->format('d/m/Y') }}
        </span>
    @endif
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-transparent py-3 fw-bold text-dark d-flex justify-content-between align-items-center">
        <span><i class="bi bi-table me-1 text-muted"></i> Liste des agents correspondants</span>
        <span class="badge bg-primary px-3 py-2">{{ $agents->count() }} agent(s) trouvé(s)</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Agent</th>
                        <th>Matricule Solde</th>
                        <th>Lieu d'affectation</th>
                        <th>Type d'agent</th>
                        <th>Sexe</th>
                        <th class="pe-4 text-end">Date Prise Service</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($agents as $agent)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold text-primary">{{ $agent->prenom }} {{ $agent->nom }}</div>
                            </td>
                            <td>
                                <code class="text-dark fw-semibold">{{ $agent->matricule_solde ?? '-' }}</code>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">{{ $agent->lieu_affectation ?? 'Non spécifié' }}</span>
                            </td>
                            <td>{{ $agent->type_agent ?? '-' }}</td>
                            <td>
                                <span class="badge {{ $agent->sexe == 'M' ? 'bg-info-subtle text-info' : 'bg-danger-subtle text-danger' }} px-2 py-1">
                                    {{ $agent->sexe ?? '-' }}
                                </span>
                            </td>
                            <td class="pe-4 text-end text-muted small">
                                {{ $agent->date_prise_service ? \Carbon\Carbon::parse($agent->date_prise_service)->format('d/m/Y') : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-exclamation-circle text-warning fs-1 d-block mb-2"></i>
                                Aucun agent ne correspond aux critères sélectionnés.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
