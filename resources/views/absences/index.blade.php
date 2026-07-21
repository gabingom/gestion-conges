@extends('layouts.app')

@section('title', 'Gestion des Absences')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h2><i class="bi bi-clock-history text-muted me-2"></i> Gestion des Absences</h2>
        <nav class="breadcrumb"><span>RH / Liste des absences</span></nav>
    </div>
    <a href="{{ route('absences.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
        <i class="bi bi-plus-circle"></i> Enregistrer une absence
    </a>
</div>

<div class="card">
    <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
        <span class="fw-bold" style="color: var(--dark);">Registre des absences</span>
        <span class="badge bg-secondary-subtle text-dark px-3 py-1.5 rounded-pill">{{ $absences->total() }} enregistrement(s)</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Agent</th>
                        <th>Motif</th>
                        <th>Durée</th>
                        <th>Période</th>
                        <th>Type de retenue</th>
                        <th>Statut</th>
                        <th class="text-end px-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($absences as $absence)
                    <tr>
                        <td>
                            <strong class="text-dark">{{ $absence->agent->nom }} {{ $absence->agent->prenom }}</strong>
                            <div class="text-muted small">{{ $absence->agent->matricule_solde }}</div>
                        </td>
                        <td>
                            <span class="text-capitalize fw-semibold">{{ str_replace('_', ' ', $absence->motif) }}</span>
                            @if($absence->valide_par_medecin)
                                <span class="badge bg-success-subtle text-success ms-1" style="font-size: 0.65rem;"><i class="bi bi-heart-pulse"></i> Médical</span>
                            @endif
                        </td>
                        <td><span class="fw-bold text-dark">{{ $absence->nombre_jours }} j</span></td>
                        <td>
                            <small class="text-muted">
                                Du {{ \Carbon\Carbon::parse($absence->date_debut)->format('d/m/Y') }}<br>
                                Au {{ \Carbon\Carbon::parse($absence->date_fin)->format('d/m/Y') }}
                            </small>
                        </td>
                        <td>
                            @if($absence->deductible)
                                <span class="badge bg-danger-subtle text-danger px-2 py-1">Déduit des congés</span>
                            @else
                                <span class="badge bg-light text-dark border px-2 py-1">Non déductible</span>
                            @endif
                        </td>
                        <td>
                            @if(in_array($absence->statut, ['approuve','Approuvé']))
                                <span class="badge bg-success-subtle text-success px-2 py-1 rounded-pill">Approuvée</span>
                            @elseif(in_array($absence->statut, ['refuse','Refusé']))
                                <span class="badge bg-danger-subtle text-danger px-2 py-1 rounded-pill">Refusée</span>
                            @else
                                <span class="badge bg-warning-subtle text-warning px-2 py-1 rounded-pill">En attente</span>
                            @endif
                        </td>
                        <td class="text-end px-4">
                            <div class="d-inline-flex gap-1">
                                @unless(in_array($absence->statut, ['approuve','Approuvé','refuse','Refusé']))
                                    <form action="{{ route('absences.approuver', $absence) }}" method="POST" class="d-inline">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-success" title="Approuver"><i class="bi bi-check-lg"></i></button>
                                    </form>
                                    <form action="{{ route('absences.refuser', $absence) }}" method="POST" class="d-inline" onsubmit="return confirm('Refuser cette demande d\'absence ?')">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Refuser"><i class="bi bi-x-lg"></i></button>
                                    </form>
                                @endunless
                                <a href="{{ route('absences.edit', $absence) }}" class="btn btn-sm btn-outline-warning" title="Modifier">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('absences.destroy', $absence) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer cet enregistrement d\'absence ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-slash-circle display-4 d-block mb-3 text-muted" style="opacity: 0.4;"></i>
                            Aucune absence répertoriée.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($absences->hasPages())
    <div class="card-footer bg-transparent border-0 pt-3 d-flex justify-content-center">
        {{ $absences->links() }}
    </div>
    @endif
</div>
@endsection