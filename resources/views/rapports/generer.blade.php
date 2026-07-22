@extends('layouts.app')

@section('title', 'Aperçu du rapport')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h2><i class="bi bi-file-earmark-text text-muted me-2"></i> {{ $titre }}</h2>
        <nav class="breadcrumb">
            <span>
                Rapports / Aperçu
                @if($date_debut && $date_fin)
                    — du {{ \Carbon\Carbon::parse($date_debut)->format('d/m/Y') }}
                    au {{ \Carbon\Carbon::parse($date_fin)->format('d/m/Y') }}
                @else
                    — toutes périodes
                @endif
            </span>
        </nav>
    </div>
    <div>
        <a href="{{ route('rapports.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
        <a href="{{ route('rapports.export-pdf', request()->query()) }}" class="btn btn-danger">
            <i class="bi bi-file-earmark-pdf"></i> Télécharger le PDF
        </a>
    </div>
</div>

@if($affectation)
    <p class="text-muted small mb-3">
        <i class="bi bi-funnel"></i> Filtré sur l'affectation : <strong>{{ $affectation }}</strong>
    </p>
@endif

{{-- ===================== AGENTS ===================== --}}
@if(in_array($type, ['agents', 'complet']))
<div class="card mb-4">
    <div class="card-header bg-transparent d-flex justify-content-between align-items-center py-3">
        <span class="fw-bold text-dark"><i class="bi bi-people me-1 text-muted"></i> Agents inscrits</span>
        <span class="badge bg-secondary-subtle text-dark px-3 py-1 rounded-pill">{{ $agents->count() }} agent(s)</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-secondary">
                    <tr>
                        <th>Matricule</th><th>Nom et prénom</th><th>Sexe</th>
                        <th>Type</th><th>Affectation</th><th>Prise de service</th><th>Solde</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($agents as $a)
                    <tr>
                        <td class="fw-semibold">{{ $a->matricule_solde }}</td>
                        <td>{{ $a->nom }} {{ $a->prenom }}</td>
                        <td class="text-capitalize">{{ $a->sexe }}</td>
                        <td class="text-capitalize">{{ $a->type_agent }}</td>
                        <td class="small">{{ $a->lieu_affectation }}</td>
                        <td>{{ $a->date_prise_service ? $a->date_prise_service->format('d/m/Y') : '-' }}</td>
                        <td class="fw-bold">{{ $a->soldeDisponible() }} j</td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center py-4 text-muted">Aucun agent sur cette période.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

{{-- ===================== CONGÉS ===================== --}}
@if(in_array($type, ['conges', 'complet']))
<div class="row g-3 mb-3">
    @foreach([
        ['Reçues', $statsConges['recues'] ?? 0, 'secondary'],
        ['Approuvées', $statsConges['approuvees'] ?? 0, 'success'],
        ['Refusées', $statsConges['refusees'] ?? 0, 'danger'],
        ['En attente', $statsConges['attente'] ?? 0, 'warning'],
    ] as $s)
        <div class="col-6 col-md-3">
            <div class="card text-center p-3">
                <div class="fw-bold text-{{ $s[2] }}" style="font-size:1.9rem;line-height:1;">{{ $s[1] }}</div>
                <div class="small text-muted mt-1">{{ $s[0] }}</div>
            </div>
        </div>
    @endforeach
</div>

<div class="card mb-4">
    <div class="card-header bg-transparent d-flex justify-content-between align-items-center py-3">
        <span class="fw-bold text-dark"><i class="bi bi-calendar-check me-1 text-muted"></i> Demandes de congé</span>
        <span class="small text-muted">{{ $statsConges['jours'] ?? 0 }} jour(s) accordé(s)</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-secondary">
                    <tr><th>Agent</th><th>Type</th><th>Jours</th><th>Cessation</th><th>Demandée le</th><th>Statut</th></tr>
                </thead>
                <tbody>
                @forelse($conges as $c)
                    <tr>
                        <td>{{ $c->agent ? $c->agent->nom.' '.$c->agent->prenom : '—' }}</td>
                        <td>{{ $c->type_conge }}</td>
                        <td class="fw-bold">{{ $c->jours_a_prendre }}</td>
                        <td>{{ $c->date_cessation ? \Carbon\Carbon::parse($c->date_cessation)->format('d/m/Y') : '-' }}</td>
                        <td class="small text-muted">{{ $c->created_at?->format('d/m/Y') }}</td>
                        <td>
                            @if(in_array($c->statut, ['Approuvé','approuve']))
                                <span class="badge bg-success-subtle text-success rounded-pill px-2 py-1">Approuvé</span>
                            @elseif(in_array($c->statut, ['Refusé','refuse']))
                                <span class="badge bg-danger-subtle text-danger rounded-pill px-2 py-1">Refusé</span>
                            @else
                                <span class="badge bg-warning-subtle text-warning rounded-pill px-2 py-1">En attente</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center py-4 text-muted">Aucune demande de congé sur cette période.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

{{-- ===================== ABSENCES ===================== --}}
@if(in_array($type, ['absences', 'complet']))
<div class="row g-3 mb-3">
    @foreach([
        ['Reçues', $statsAbsences['recues'] ?? 0, 'secondary'],
        ['Approuvées', $statsAbsences['approuvees'] ?? 0, 'success'],
        ['Refusées', $statsAbsences['refusees'] ?? 0, 'danger'],
        ['En attente', $statsAbsences['attente'] ?? 0, 'warning'],
    ] as $s)
        <div class="col-6 col-md-3">
            <div class="card text-center p-3">
                <div class="fw-bold text-{{ $s[2] }}" style="font-size:1.9rem;line-height:1;">{{ $s[1] }}</div>
                <div class="small text-muted mt-1">{{ $s[0] }}</div>
            </div>
        </div>
    @endforeach
</div>

<div class="card mb-4">
    <div class="card-header bg-transparent d-flex justify-content-between align-items-center py-3">
        <span class="fw-bold text-dark"><i class="bi bi-calendar-x me-1 text-muted"></i> Demandes d'absence</span>
        <span class="small text-muted">{{ $statsAbsences['jours'] ?? 0 }} jour(s) accordé(s)</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-secondary">
                    <tr><th>Agent</th><th>Motif</th><th>Jours</th><th>Début</th><th>Demandée le</th><th>Statut</th></tr>
                </thead>
                <tbody>
                @forelse($absences as $a)
                    <tr>
                        <td>{{ $a->agent ? $a->agent->nom.' '.$a->agent->prenom : '—' }}</td>
                        <td class="text-capitalize">{{ str_replace('_',' ', $a->motif) }}</td>
                        <td class="fw-bold">{{ $a->nombre_jours }}</td>
                        <td>{{ $a->date_debut ? $a->date_debut->format('d/m/Y') : '-' }}</td>
                        <td class="small text-muted">{{ $a->created_at?->format('d/m/Y') }}</td>
                        <td>
                            @if(in_array($a->statut, ['approuve','Approuvé']))
                                <span class="badge bg-success-subtle text-success rounded-pill px-2 py-1">Approuvée</span>
                            @elseif(in_array($a->statut, ['refuse','Refusé']))
                                <span class="badge bg-danger-subtle text-danger rounded-pill px-2 py-1">Refusée</span>
                            @else
                                <span class="badge bg-warning-subtle text-warning rounded-pill px-2 py-1">En attente</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center py-4 text-muted">Aucune demande d'absence sur cette période.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif
@endsection
