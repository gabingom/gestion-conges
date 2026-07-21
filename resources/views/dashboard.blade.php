@extends('layouts.app')

@section('title', 'Tableau de bord')

@section('content')
<div class="page-header">
    <h2><i class="bi bi-speedometer2"></i> Tableau de bord</h2>
    <nav class="breadcrumb"><span>Accueil</span></nav>
</div>

<!-- Statistiques -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card sc-dark">
            <div class="stat-label">Total Agents</div>
            <div class="stat-value">{{ $totalAgents }}</div>
            <i class="bi bi-people bg-icon"></i>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card sc-teal">
            <div class="stat-label">Total Congés</div>
            <div class="stat-value">{{ $totalConges }}</div>
            <i class="bi bi-calendar-check bg-icon"></i>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card sc-accent">
            <div class="stat-label">Total Absences</div>
            <div class="stat-value">{{ $totalAbsences }}</div>
            <i class="bi bi-calendar-x bg-icon"></i>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card sc-amber">
            <div class="stat-label">Congés en attente</div>
            <div class="stat-value">{{ $congesEnAttente }}</div>
            <i class="bi bi-hourglass bg-icon"></i>
        </div>
    </div>
</div>

<!-- Graphiques -->
<div class="row g-3 mb-4">
    <div class="col-md-7">
        <div class="card h-100">
            <div class="card-header">
                <i class="bi bi-bar-chart"></i> Agents par affectation
            </div>
            <div class="card-body">
                <canvas id="agentsChart" height="120"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="card h-100">
            <div class="card-header">
                <i class="bi bi-pie-chart"></i> Statut des congés
            </div>
            <div class="card-body d-flex align-items-center justify-content-center">
                <canvas id="congesChart" height="160"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Derniers agents -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-people"></i> Derniers agents ajoutés</span>
        <a href="{{ route('agents.index') }}" class="btn btn-sm btn-primary">Voir tout</a>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Matricule</th>
                    <th>Nom & Prénom</th>
                    <th>Affectation</th>
                    <th>Type</th>
                    <th>Jours dus</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($dernierAgents as $agent)
                <tr>
                    <td><span class="badge bg-secondary">{{ $agent->matricule_solde }}</span></td>
                    <td><strong>{{ $agent->nom }} {{ $agent->prenom }}</strong></td>
                    <td>{{ $agent->lieu_affectation }}</td>
                    <td>
                        <span class="badge {{ $agent->type_agent == 'titulaire' ? 'bg-dark' : 'bg-secondary' }}">
                            {{ ucfirst($agent->type_agent) }}
                        </span>
                    </td>
                    <td><span class="badge bg-success">{{ $agent->jours_conges_dus }} j</span></td>
                    <td>
                        <a href="{{ route('agents.show', $agent) }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">Aucun agent enregistré</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
    // Graphique agents par affectation
    const agentsCtx = document.getElementById('agentsChart').getContext('2d');
    new Chart(agentsCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($agentsParAffectation->pluck('lieu_affectation')->map(fn($l) => strlen($l) > 30 ? substr($l, 0, 30).'...' : $l)) !!},
            datasets: [{
                label: "Agents",
                data: {!! json_encode($agentsParAffectation->pluck('total')) !!},
                backgroundColor: ['#1a1a2e','#e94560','#00897b','#f59e0b','#9b59b6','#3498db'],
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
        }
    });

    // Graphique statut congés
    const congesCtx = document.getElementById('congesChart').getContext('2d');
    new Chart(congesCtx, {
        type: 'doughnut',
        data: {
            labels: ['Approuvés', 'En attente', 'Refusés'],
            datasets: [{
                data: [{{ $congesApprouves }}, {{ $congesEnAttente }}, {{ $congesRefuses }}],
                backgroundColor: ['#00897b', '#f59e0b', '#e94560'],
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            cutout: '65%',
            plugins: { legend: { position: 'bottom' } }
        }
    });
</script>
@endpush
@endsection
