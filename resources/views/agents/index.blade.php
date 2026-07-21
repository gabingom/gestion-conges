@extends('layouts.app')

@section('title', 'Agents')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h2><i class="bi bi-people"></i> Liste des Agents</h2>
        <nav class="breadcrumb"><span>Agents</span></nav>
    </div>
    <a href="{{ route('agents.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Nouvel Agent
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Matricule</th>
                    <th>Nom & Prénom</th>
                    <th>Affectation</th>
                    <th>Type</th>
                    <th>Sexe</th>
                    <th>Jours dus</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($agents as $agent)
                <tr>
                    <td><span class="badge bg-secondary">{{ $agent->matricule_solde }}</span></td>
                    <td><strong>{{ $agent->nom }} {{ $agent->prenom }}</strong></td>
                    <td>{{ $agent->lieu_affectation }}</td>
                    <td>
                        <span class="badge {{ $agent->type_agent == 'titulaire' ? 'bg-dark' : 'bg-secondary' }}">
                            {{ ucfirst($agent->type_agent) }}
                        </span>
                    </td>
                    <td>
                        <span class="badge {{ $agent->sexe == 'femme' ? 'badge-mariage' : 'bg-secondary' }}">
                            {{ ucfirst($agent->sexe) }}
                        </span>
                    </td>
                    <td><span class="badge bg-success">{{ $agent->jours_conges_dus }} j</span></td>
                    <td>
                        <a href="{{ route('agents.show', $agent) }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('agents.edit', $agent) }}" class="btn btn-sm btn-outline-warning">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('agents.destroy', $agent) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                onclick="return confirm('Supprimer cet agent ?')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">Aucun agent enregistré</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white">
        {{ $agents->links() }}
    </div>
</div>
@endsection
