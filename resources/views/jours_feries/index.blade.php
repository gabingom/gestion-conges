@extends('layouts.app')

@section('title', 'Gestion des Jours Fériés')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2><i class="bi bi-calendar-event text-muted me-2"></i> Jours Fériés</h2>
        <nav class="breadcrumb"><span>Configuration / Liste des Jours Fériés</span></nav>
    </div>
    <a href="{{ route('jours-feries.create') }}" class="btn btn-danger btn-sm">
        <i class="bi bi-plus-circle me-1"></i> Ajouter un jour férié
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Nom du Jour Férié</th>
                        <th>Date</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($joursFeries as $jour)
                        <tr>
                            <td class="fw-semibold ps-4">{{ $jour->nom }}</td>
                            <td>{{ \Carbon\Carbon::parse($jour->date)->format('d/m/Y') }}</td>
                            <td class="text-end pe-4">
                                <form action="{{ route('jours-feries.destroy', $jour) }}" method="POST" onsubmit="return confirm('Voulez-vous vraiment supprimer ce jour férié ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-link text-danger p-0 m-0 align-baseline">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-5 text-muted">
                                <i class="bi bi-calendar-x display-6 d-block mb-2 text-muted"></i>
                                Aucun jour férié enregistré pour le moment.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">
    {{ $joursFeries->links() }}
</div>
@endsection
