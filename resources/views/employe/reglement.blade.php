@extends('layouts.employe')

@section('title', 'Code du travail')

@section('content')
<div class="page-header">
    <h2><i class="bi bi-journal-text text-muted me-2"></i> Code du travail</h2>
    <nav class="breadcrumb"><span>Mon espace / Code du travail</span></nav>
</div>

<p class="text-muted mb-4">
    <i class="bi bi-info-circle me-1"></i>
    Les règles ci-dessous encadrent l'attribution des congés et absences. Elles sont appliquées
    automatiquement par la plateforme lors de vos demandes.
</p>

@if($agent)
<!-- Situation personnelle -->
<div class="card mb-4" style="border-left:4px solid var(--accent);">
    <div class="card-body">
        <h5 class="fw-bold mb-3" style="color:var(--dark);font-size:1rem;">
            <i class="bi bi-person-check text-muted me-2"></i> Votre situation
        </h5>
        <div class="row text-center g-3">
            <div class="col-6 col-md-3">
                <div class="fw-bold" style="font-size:1.8rem;line-height:1;color:var(--dark);">{{ $agent->anneesService() }}</div>
                <div class="small text-muted mt-1">Année(s) de service</div>
            </div>
            <div class="col-6 col-md-3">
                @if($agent->aUnAnDeService())
                    <div class="fw-bold text-success" style="font-size:1.8rem;line-height:1;">{{ $agent->joursAcquis() }}</div>
                @else
                    <div class="fw-bold" style="font-size:1.8rem;line-height:1;color:#c9ccd4;"><i class="bi bi-lock-fill"></i></div>
                @endif
                <div class="small text-muted mt-1">Jours acquis</div>
            </div>
            <div class="col-6 col-md-3">
                <div class="fw-bold text-secondary" style="font-size:1.8rem;line-height:1;">{{ $agent->joursConsommes() }}</div>
                <div class="small text-muted mt-1">Jours consommés</div>
            </div>
            <div class="col-6 col-md-3">
                @if($agent->aUnAnDeService())
                    <div class="fw-bold" style="font-size:1.8rem;line-height:1;color:var(--accent);">{{ $agent->soldeDisponible() }}</div>
                @else
                    <div class="fw-bold" style="font-size:1.8rem;line-height:1;color:#c9ccd4;"><i class="bi bi-lock-fill"></i></div>
                @endif
                <div class="small text-muted mt-1">Solde disponible</div>
            </div>
        </div>

        @unless($agent->aUnAnDeService())
            <div class="alert alert-danger mt-3 mb-0 py-2 small">
                <i class="bi bi-lock-fill me-1"></i>
                <strong>Droits non encore ouverts.</strong>
                Vous n'avez pas encore accompli 1 an de service : vous ne pouvez pas demander de congé annuel.
                @if($agent->dateOuvertureDroit())
                    Vos droits s'ouvriront le <strong>{{ $agent->dateOuvertureDroit()->format('d/m/Y') }}</strong>.
                @endif
            </div>
        @endunless

        @if($agent->plafondAtteint())
            <div class="alert alert-info mt-3 mb-0 py-2 small">
                <i class="bi bi-info-circle-fill me-1"></i>
                Vous avez atteint 3 ans de service : le cumul de vos congés est plafonné à
                {{ \App\Models\Agent::CUMUL_MAX }} jours.
            </div>
        @endif
    </div>
</div>
@endif

<!-- Congés annuels -->
<div class="card mb-4">
    <div class="card-header bg-transparent fw-bold text-dark py-3">
        <i class="bi bi-calendar-check text-muted me-2"></i> Congés annuels
    </div>
    <div class="card-body">
        <ul class="mb-0" style="line-height:2;">
            <li>Aucun congé annuel ne peut être demandé avant <strong>1 année de service accomplie</strong>.</li>
            <li>Après une année de service, l'employé a droit à <strong>24 jours de congé</strong> chaque année.</li>
            <li>Le cumul des congés est plafonné à <strong>72 jours</strong>, soit l'équivalent de 3 années de service.</li>
            <li>Une employée mère d'enfants de <strong>moins de 14 ans</strong> bénéficie d'un jour de congé supplémentaire par enfant. Ce bonus n'est acquis qu'une fois la première année de service accomplie.</li>
        </ul>
    </div>
</div>

<!-- Maternité -->
<div class="card mb-4">
    <div class="card-header bg-transparent fw-bold text-dark py-3">
        <i class="bi bi-heart-pulse text-muted me-2"></i> Congé de maternité
    </div>
    <div class="card-body">
        <ul class="mb-0" style="line-height:2;">
            <li>Conformément au Code du travail, l'employée en état de grossesse a droit à
                <strong>6 semaines de congé avant l'accouchement</strong> et
                <strong>8 semaines après l'accouchement</strong>.</li>
            <li>Cette durée est <strong>prolongeable sur avis du médecin</strong>.</li>
            <li>En cas de dépassement du nombre de jours autorisés, un
                <strong>document justificatif médical</strong> doit obligatoirement être joint à la demande.</li>
        </ul>
    </div>
</div>

<!-- Absences exceptionnelles -->
<div class="card mb-4">
    <div class="card-header bg-transparent fw-bold text-dark py-3">
        <i class="bi bi-calendar-x text-muted me-2"></i> Absences exceptionnelles
        <span class="badge bg-success-subtle text-success border border-success-subtle ms-2 rounded-pill" style="font-size:.7rem;">
            Non déductibles des 24 jours
        </span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-secondary">
                    <tr>
                        <th>Motif</th>
                        <th>Durée accordée</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td>Décès d'un descendant</td><td class="fw-bold">4 jours</td></tr>
                    <tr><td>Décès d'un ascendant</td><td class="fw-bold">2 jours</td></tr>
                    <tr><td>Mariage de l'employé</td><td class="fw-bold">3 jours</td></tr>
                    <tr><td>Naissance d'un descendant (employé marié)</td><td class="fw-bold">1 jour</td></tr>
                    <tr><td>Baptême d'un descendant (employé marié)</td><td class="fw-bold">1 jour</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Cas particuliers -->
<div class="card mb-4">
    <div class="card-header bg-transparent fw-bold text-dark py-3">
        <i class="bi bi-shield-exclamation text-muted me-2"></i> Cas particuliers
    </div>
    <div class="card-body">
        <ul class="mb-0" style="line-height:2;">
            <li><strong>Accident de travail :</strong> le nombre de jours est fixé sur instruction du médecin
                et doit être validé par l'autorité compétente.</li>
            <li><strong>Autorisation d'absence pour raisons personnelles :</strong> en cas d'accord, il sera
                mentionné dans le document d'autorisation que l'employé
                <strong>peut être rappelé en fonction à tout moment</strong> en cas de besoin.</li>
        </ul>
    </div>
</div>

<div class="text-center text-muted small mb-4">
    <i class="bi bi-building me-1"></i>
    Université du Sine Saloum El-Hâdj Ibrahima NIASS — Dispositions relatives aux congés et absences du personnel.
</div>
@endsection
