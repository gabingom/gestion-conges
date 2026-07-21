@extends('layouts.app')

@section('title', 'Profil de l\'Agent')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h2>Fiche Individuelle de l'Agent</h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('agents.index') }}" class="text-decoration-none">Agents</a></li>
            <li class="breadcrumb-item active">{{ $agent->prenom }} {{ $agent->nom }}</li>
        </ol>
    </div>
    <a href="{{ route('agents.index') }}" class="btn btn-primary">
        <i class="bi bi-arrow-left-short"></i> Retour à la liste
    </a>
</div>

@if(session('new_account_password'))
<div class="card mb-4" style="border-left:4px solid var(--accent);">
    <div class="card-body">
        <h5 class="fw-bold mb-2" style="color:var(--dark);">
            <i class="bi bi-key-fill text-danger me-2"></i> Identifiants de connexion de l'employé
        </h5>
        <p class="small text-muted mb-3">
            Communiquez ces informations à l'agent. Le mot de passe ne sera plus affiché par la suite.
        </p>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label small text-muted mb-1">Identifiant (email)</label>
                <input type="text" class="form-control fw-bold" readonly value="{{ session('new_account_email') }}">
            </div>
            <div class="col-md-6">
                <label class="form-label small text-muted mb-1">Mot de passe temporaire</label>
                <input type="text" class="form-control fw-bold" readonly value="{{ session('new_account_password') }}">
            </div>
        </div>
    </div>
</div>
@endif

<div class="row">
    <div class="col-xl-4 col-lg-5 mb-4">
        <div class="card text-center p-4 mb-4">
            <div class="rounded-circle d-inline-flex align-items-center justify-content-center mx-auto mb-3 text-white shadow-sm" style="width: 85px; height: 85px; background: var(--dark-2);">
                <i class="bi bi-person text-white" style="font-size: 2.5rem;"></i>
            </div>
            <h4 class="fw-bold mb-1" style="color: var(--dark);">{{ $agent->prenom }} {{ $agent->nom }}</h4>
            <p class="small text-muted mb-3"><i class="bi bi-geo-alt-fill text-danger"></i> {{ $agent->lieu_affectation }}</p>
            <span class="badge px-3 py-2 text-capitalize mb-2 shadow-sm" style="background: var(--accent); font-size: 0.85rem;">
                {{ $agent->type_agent }}
            </span>
        </div>

        <div class="card p-3">
            <div class="card-header bg-transparent fw-bold border-0 text-uppercase tracking-wider px-2" style="font-size: 0.75rem; color: var(--text-muted);">
                <i class="bi bi-calculator me-1 text-danger"></i> Droits aux congés
            </div>
            <div class="card-body p-2">
                <div class="row text-center my-1">
                    <div class="col-6 border-end">
                        <div class="stat-value fw-bold text-success" style="font-size: 2.2rem; line-height: 1.1;">{{ $agent->jours_conges_dus }}</div>
                        <div class="small fw-semibold mt-1" style="font-size: 0.75rem; color: var(--text-muted);">Jours acquis</div>
                    </div>
                    <div class="col-6">
                        <div class="stat-value fw-bold text-secondary" style="font-size: 2.2rem; line-height: 1.1;">{{ $agent->jours_reportes }}</div>
                        <div class="small fw-semibold mt-1" style="font-size: 0.75rem; color: var(--text-muted);">Jours reportés</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-8 col-lg-7">
        <div class="card p-4 mb-4">
            <h5 class="fw-bold mb-3" style="color: var(--dark); font-size: 1rem;">
                <i class="bi bi-info-circle-fill text-muted me-2"></i> Informations administratives
            </h5>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label d-block mb-0 text-muted small">Matricule Solde</label>
                    <span class="fw-bold text-dark">{{ $agent->matricule_solde }}</span>
                </div>
                <div class="col-md-6">
                    <label class="form-label d-block mb-0 text-muted small">Sexe</label>
                    <span class="fw-bold text-dark text-capitalize">{{ $agent->sexe }}</span>
                </div>
                <div class="col-md-6">
                    <label class="form-label d-block mb-0 text-muted small">Nombre d'enfants</label>
                    <span class="fw-bold text-dark">{{ $agent->nombre_enfants }} enfant(s)</span>
                </div>
                <div class="col-md-6">
                    <label class="form-label d-block mb-0 text-muted small">Date de prise de service</label>
                    <span class="fw-bold text-dark">{{ $agent->date_prise_service ? $agent->date_prise_service->format('d/m/Y') : '-' }}</span>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-transparent border-0 pt-3 px-4">
                <ul class="nav nav-tabs card-header-tabs" id="agentDetailTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active fw-bold border-0 text-dark py-2" id="conges-tab" data-bs-toggle="tab" data-bs-target="#conges-pane" type="button" role="tab" style="border-bottom: 3px solid var(--accent) !important;">
                            <i class="bi bi-calendar-check-fill me-2" style="color: var(--accent);"></i>Congés ({{ $agent->conges->count() }})
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-bold border-0 text-dark py-2" id="absences-tab" data-bs-toggle="tab" data-bs-target="#absences-pane" type="button" role="tab">
                            <i class="bi bi-calendar-x-fill me-2" style="color: var(--dark-2);"></i>Absences ({{ $agent->absences->count() }})
                        </button>
                    </li>
                </ul>
            </div>
            <div class="card-body p-4 tab-content" id="agentDetailTabsContent">

                <div class="tab-pane fade show active" id="conges-pane" role="tabpanel">
                    @if($agent->conges->isEmpty())
                        <p class="text-muted mb-0 py-3 text-center"><i class="bi bi-inbox me-1"></i> Aucun congé enregistré pour cet agent.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>Jours</th>
                                        <th>Cessation</th>
                                        <th>Reprise prévue</th>
                                        <th>Statut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($agent->conges as $conge)
                                        <tr>
                                            <td class="fw-bold">{{ $conge->jours_a_prendre }} jours</td>
                                            <td>{{ $conge->date_cessation ? $conge->date_cessation->format('d/m/Y') : '-' }}</td>
                                            <td>{{ $conge->date_reprise ? $conge->date_reprise->format('d/m/Y') : '-' }}</td>
                                            <td>
                                                @if($conge->statut === 'approuve')
                                                    <span class="badge bg-success-subtle text-success px-2 py-1">Approuvé</span>
                                                @elseif($conge->statut === 'refuse')
                                                    <span class="badge bg-danger-subtle text-danger px-2 py-1">Refusé</span>
                                                @else
                                                    <span class="badge bg-warning-subtle text-warning px-2 py-1">En attente</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                <div class="tab-pane fade" id="absences-pane" role="tabpanel">
                    @if($agent->absences->isEmpty())
                        <p class="text-muted mb-0 py-3 text-center"><i class="bi bi-inbox me-1"></i> Aucune absence enregistrée pour cet agent.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>Motif</th>
                                        <th>Début</th>
                                        <th>Durée</th>
                                        <th>Déductible</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($agent->absences as $absence)
                                        <tr>
                                            <td>
                                                <span class="badge badge-{{ $absence->motif }} px-2 py-1 text-capitalize">
                                                    {{ str_replace('_', ' ', $absence->motif) }}
                                                </span>
                                            </td>
                                            <td>{{ $absence->date_debut ? $absence->date_debut->format('d/m/Y') : '-' }}</td>
                                            <td class="fw-bold">{{ $absence->nombre_jours }} jours</td>
                                            <td>
                                                @if($absence->deductible)
                                                    <span class="text-danger fw-bold"><i class="bi bi-dash-circle-fill"></i> Oui</span>
                                                @else
                                                    <span class="text-success fw-bold"><i class="bi bi-check-circle-fill"></i> Non</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
