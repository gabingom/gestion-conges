@extends('layouts.app')

@section('title', 'Détails de la Demande de Congé')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2><i class="bi bi-file-earmark-text text-muted me-2"></i> Demande de Congé</h2>
        <nav class="breadcrumb"><span>Congés / Détails</span></nav>
    </div>
    <a href="{{ route('conges.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Retour à la liste
    </a>
</div>

<div class="row g-4">
    <div class="col-md-8">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-transparent fw-bold text-dark py-3 d-flex justify-content-between align-items-center">
                <span>Détails de la demande</span>
                @if($conge->statut == 'En attente')
                    <span class="badge bg-warning text-dark px-3 py-2"><i class="bi bi-clock-history me-1"></i> En attente</span>
                @elseif($conge->statut == 'Approuvé')
                    <span class="badge bg-success px-3 py-2"><i class="bi bi-check-circle me-1"></i> Approuvé</span>
                @else
                    <span class="badge bg-danger px-3 py-2"><i class="bi bi-x-circle me-1"></i> Refusé</span>
                @endif
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <small class="text-muted d-block">Type de congé</small>
                        <span class="fw-semibold fs-5 text-danger">{{ $conge->type_conge }}</span>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Nombre de jours</small>
                        <span class="fw-semibold fs-5">{{ $conge->jours_a_prendre }} jour(s)</span>
                    </div>

                    <hr class="text-muted my-3">

                    <div class="col-md-6">
                        <small class="text-muted d-block"><i class="bi bi-calendar-minus me-1"></i> Date de cessation</small>
                        <span class="fw-semibold">{{ \Carbon\Carbon::parse($conge->date_cessation)->format('d/m/Y') }}</span>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block"><i class="bi bi-calendar-plus me-1"></i> Date de reprise</small>
                        <span class="fw-semibold">
                            {{ $conge->date_reprise ? \Carbon\Carbon::parse($conge->date_reprise)->format('d/m/Y') : 'Non spécifiée' }}
                        </span>
                    </div>

                    <hr class="text-muted my-3">

                    <div class="col-md-6">
                        <small class="text-muted d-block">Congé exceptionnel</small>
                        <span class="badge {{ $conge->exceptionnel ? 'bg-info text-dark' : 'bg-secondary' }}">
                            {{ $conge->exceptionnel ? 'Oui' : 'Non' }}
                        </span>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Déductible du solde</small>
                        <span class="badge {{ $conge->deductible ? 'bg-warning text-dark' : 'bg-light text-dark' }}">
                            {{ $conge->deductible ? 'Oui' : 'Non' }}
                        </span>
                    </div>

                    @if($conge->commentaire)
                        <div class="col-12 mt-3">
                            <small class="text-muted d-block">Commentaire / Motif</small>
                            <div class="p-3 bg-light rounded italic">
                                "{{ $conge->commentaire }}"
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-transparent fw-bold text-dark py-3">
                <i class="bi bi-person me-1 text-muted"></i> Agent demandeur
            </div>
            <div class="card-body">
                @if($conge->agent)
                    <h5 class="card-title text-primary mb-1 fw-bold">{{ $conge->agent->prenom }} {{ $conge->agent->nom }}</h5>

                    <ul class="list-group list-group-flush small mt-3">
                        <li class="list-group-item px-0 d-flex justify-content-between">
                            <span class="text-muted">Matricule Solde :</span>
                            <span class="fw-semibold text-dark">{{ $conge->agent->matricule_solde ?? 'Non renseigné' }}</span>
                        </li>
                        <li class="list-group-item px-0 d-flex justify-content-between">
                            <span class="text-muted">Affectation :</span>
                            <span class="fw-semibold text-dark">{{ $conge->agent->lieu_affectation ?? 'Non renseigné' }}</span>
                        </li>
                        <li class="list-group-item px-0 d-flex justify-content-between">
                            <span class="text-muted">Type d'agent :</span>
                            <span class="fw-semibold text-dark">{{ $conge->agent->type_agent ?? 'Non renseigné' }}</span>
                        </li>
                        <li class="list-group-item px-0 d-flex justify-content-between">
                            <span class="text-muted">Sexe :</span>
                            <span class="fw-semibold text-dark">{{ $conge->agent->sexe ?? 'Non renseigné' }}</span>
                        </li>
                    </ul>
                @else
                    <span class="text-muted italic text-center d-block py-3">Informations de l'agent indisponibles</span>
                @endif
            </div>
        </div>

        @unless(in_array($conge->statut, ['Approuvé','approuve','Refusé','refuse']))
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header bg-transparent fw-bold text-dark py-3">
                <i class="bi bi-check2-square me-1 text-muted"></i> Traiter la demande
            </div>
            <div class="card-body d-flex gap-2">
                <form action="{{ route('conges.approuver', $conge) }}" method="POST" class="flex-fill">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn btn-success w-100"><i class="bi bi-check-lg me-1"></i> Approuver</button>
                </form>
                <form action="{{ route('conges.refuser', $conge) }}" method="POST" class="flex-fill" onsubmit="return confirm('Refuser cette demande ?')">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn btn-danger w-100"><i class="bi bi-x-lg me-1"></i> Refuser</button>
                </form>
            </div>
        </div>
        @endunless
    </div>
</div>
@endsection