@extends('layouts.employe')

@section('title', 'Mes absences')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2><i class="bi bi-calendar-x text-muted me-2"></i> Mes absences</h2>
        <nav class="breadcrumb"><span>Mon espace / Mes absences</span></nav>
    </div>
    @if($agent)
    <a href="{{ route('employe.absences.create') }}" class="btn btn-danger btn-sm">
        <i class="bi bi-plus-circle me-1"></i> Nouvelle demande
    </a>
    @endif
</div>

@if(!$agent)
    <div class="alert alert-warning"><i class="bi bi-exclamation-triangle-fill me-2"></i> Aucune fiche agent rattachée à votre compte.</div>
@else
<div class="card shadow-sm border-0">
    <div class="card-header bg-transparent d-flex justify-content-between align-items-center py-3">
        <span class="fw-bold text-dark">Historique de mes demandes</span>
        <span class="badge bg-secondary-subtle text-dark px-3 py-1 rounded-pill">{{ $absences->total() }} demande(s)</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-secondary">
                    <tr>
                        <th>Motif</th>
                        <th>Début</th>
                        <th>Durée</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($absences as $absence)
                        <tr>
                            <td>
                                <span class="badge badge-{{ $absence->motif }} px-2 py-1 text-capitalize">
                                    {{ str_replace('_', ' ', $absence->motif) }}
                                </span>
                            </td>
                            <td>{{ $absence->date_debut ? $absence->date_debut->format('d/m/Y') : '-' }}</td>
                            <td class="fw-bold">{{ $absence->nombre_jours }} jour(s)</td>
                            <td>
                                @if(in_array($absence->statut, ['approuve','Approuvé']))
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill">Approuvée</span>
                                @elseif(in_array($absence->statut, ['refuse','Refusé']))
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 rounded-pill">Refusée</span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1 rounded-pill">En attente</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-3 d-block mb-2 text-secondary"></i>
                                Vous n'avez encore envoyé aucune demande d'absence.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="mt-3">{{ $absences->links() }}</div>
@endif
@endsection
