@extends('layouts.employe')

@section('title', 'Mes congés')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2><i class="bi bi-calendar-check text-muted me-2"></i> Mes congés</h2>
        <nav class="breadcrumb"><span>Mon espace / Mes congés</span></nav>
    </div>
    @if($agent)
    <a href="{{ route('employe.conges.create') }}" class="btn btn-danger btn-sm">
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
        <span class="badge bg-secondary-subtle text-dark px-3 py-1 rounded-pill">{{ $conges->total() }} demande(s)</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-secondary">
                    <tr>
                        <th>Type</th>
                        <th>Jours</th>
                        <th>Date de cessation</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($conges as $conge)
                        <tr>
                            <td class="fw-semibold text-dark">{{ $conge->type_conge }}</td>
                            <td>{{ $conge->jours_a_prendre }} jour(s)</td>
                            <td>{{ \Carbon\Carbon::parse($conge->date_cessation)->format('d/m/Y') }}</td>
                            <td>
                                @if(in_array($conge->statut, ['Approuvé','approuve']))
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill">Approuvé</span>
                                @elseif(in_array($conge->statut, ['Refusé','refuse']))
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 rounded-pill">Refusé</span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1 rounded-pill">En attente</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-3 d-block mb-2 text-secondary"></i>
                                Vous n'avez encore envoyé aucune demande de congé.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="mt-3">{{ $conges->links() }}</div>
@endif
@endsection
