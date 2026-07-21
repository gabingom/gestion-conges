@extends('layouts.app')

@section('title', 'Suivi des Congés')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2><i class="bi bi-calendar-check text-muted me-2"></i> Gestion des Congés</h2>
        <nav class="breadcrumb"><span>Congés / Liste</span></nav>
    </div>
    <a href="{{ route('conges.create') }}" class="btn btn-danger btn-sm">
        <i class="bi bi-plus-circle me-1"></i> Nouvelle Demande
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-3" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card shadow-sm border-0">
    <div class="card-header bg-transparent d-flex justify-content-between align-items-center py-3">
        <span class="fw-bold text-dark">Suivi des demandes</span>
        <span class="badge bg-secondary-subtle text-dark px-3 py-1.5 rounded-pill">{{ $conges->count() }} demande(s)</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-secondary">
                    <tr>
                        <th>Agent</th>
                        <th>Type / Jours</th>
                        <th>Cessation</th>
                        <th>Statut</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($conges as $conge)
                        <tr>
                            <td>
                                <div class="fw-bold text-dark">
                                    {{ $conge->agent->prenom ?? '' }} {{ $conge->agent->nom ?? 'Agent Inconnu' }}
                                </div>
                                <small class="text-muted">{{ $conge->agent->matricule_solde ?? '' }}</small>
                            </td>
                            <td>
                                <span class="fw-semibold text-dark">{{ $conge->type_conge }}</span>
                                <div class="small text-muted">{{ $conge->jours_a_prendre }} jour(s)</div>
                            </td>
                            <td>
                                <div>{{ \Carbon\Carbon::parse($conge->date_cessation)->format('d/m/Y') }}</div>
                            </td>
                            <td>
                                @if($conge->statut === 'Approuvé')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 rounded-pill">Approuvé</span>
                                @elseif($conge->statut === 'Refusé')
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1 rounded-pill">Refusé</span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2.5 py-1 rounded-pill">En attente</span>
                                @endif
                            </td>
                            <td class="text-end pe-3">
                                <div class="d-inline-flex gap-1">
                                    <a href="{{ route('conges.show', $conge->id) }}" class="btn btn-outline-secondary btn-sm" title="Voir les détails">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if($conge->justificatif)
                                        <a href="{{ asset('storage/' . $conge->justificatif) }}" target="_blank" class="btn btn-outline-info btn-sm" title="Justificatif">
                                            <i class="bi bi-file-earmark-pdf"></i>
                                        </a>
                                    @endif
                                    @unless(in_array($conge->statut, ['Approuvé','approuve','Refusé','refuse']))
                                        <form action="{{ route('conges.approuver', $conge) }}" method="POST" class="d-inline">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="btn btn-success btn-sm" title="Approuver"><i class="bi bi-check-lg"></i></button>
                                        </form>
                                        <form action="{{ route('conges.refuser', $conge) }}" method="POST" class="d-inline" onsubmit="return confirm('Refuser cette demande de congé ?')">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Refuser"><i class="bi bi-x-lg"></i></button>
                                        </form>
                                    @endunless
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-3 d-block mb-2 text-secondary"></i>
                                Aucune demande de congé enregistrée pour le moment.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection