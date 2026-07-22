@extends('layouts.employe')

@section('title', 'Mon profil')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h2><i class="bi bi-person me-2"></i> Mon profil</h2>
        <nav class="breadcrumb"><span>Mon espace / Profil</span></nav>
    </div>
</div>

@if(!$agent)
    <div class="alert alert-warning">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        Aucune fiche agent n'est encore rattachée à votre compte. Contactez l'administration.
    </div>
@else

<p class="text-muted mb-4">
    <i class="bi bi-info-circle me-1"></i>
    Ces informations sont gérées par l'administration une fois vos demandes traitées.
    Contactez l'administration pour toute correction.
</p>

<div class="row g-4">
    <!-- Carte identité -->
    <div class="col-xl-4">
        <div class="card text-center p-4">
            <div class="rounded-circle d-inline-flex align-items-center justify-content-center mx-auto mb-3 text-white shadow-sm" style="width:85px;height:85px;background:var(--dark-2);">
                <i class="bi bi-person" style="font-size:2.5rem;"></i>
            </div>
            <h4 class="fw-bold mb-1" style="color:var(--dark);">{{ $agent->prenom }} {{ $agent->nom }}</h4>
            <p class="small text-muted mb-3"><i class="bi bi-geo-alt-fill text-danger"></i> {{ $agent->lieu_affectation }}</p>
            <span class="badge px-3 py-2 text-capitalize shadow-sm" style="background:var(--accent);font-size:.85rem;">{{ $agent->type_agent }}</span>
        </div>
    </div>

    <!-- Informations personnelles -->
    <div class="col-xl-8">
        <div class="card p-4 mb-4">
            <h5 class="fw-bold mb-3" style="color:var(--dark);font-size:1rem;">
                <i class="bi bi-card-text text-muted me-2"></i> Informations personnelles
            </h5>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label d-block mb-0 text-muted small">Nom</label>
                    <span class="fw-bold text-dark">{{ $agent->nom }}</span>
                </div>
                <div class="col-md-6">
                    <label class="form-label d-block mb-0 text-muted small">Prénom</label>
                    <span class="fw-bold text-dark">{{ $agent->prenom }}</span>
                </div>
                <div class="col-md-6">
                    <label class="form-label d-block mb-0 text-muted small">Matricule de solde</label>
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
                <div class="col-md-6">
                    <label class="form-label d-block mb-0 text-muted small">Identifiant de connexion</label>
                    <span class="fw-bold text-dark">{{ Auth::user()->email }}</span>
                </div>
            </div>
        </div>

        <!-- Situation des congés -->
        <div class="card p-4">
            <h5 class="fw-bold mb-3" style="color:var(--dark);font-size:1rem;">
                <i class="bi bi-calculator text-muted me-2"></i> Situation des congés
            </h5>
            @if(!$agent->aUnAnDeService())
                {{-- Droits non encore ouverts : compteurs verrouillés --}}
                <div class="row text-center my-1">
                    <div class="col-4 border-end">
                        <div class="fw-bold" style="font-size:2rem;line-height:1.1;color:#c9ccd4;">
                            <i class="bi bi-lock-fill"></i>
                        </div>
                        <div class="small fw-semibold mt-1" style="font-size:.75rem;color:var(--text-muted);">Jours acquis</div>
                    </div>
                    <div class="col-4 border-end">
                        <div class="fw-bold" style="font-size:2rem;line-height:1.1;color:#c9ccd4;">
                            <i class="bi bi-lock-fill"></i>
                        </div>
                        <div class="small fw-semibold mt-1" style="font-size:.75rem;color:var(--text-muted);">Jours consommés</div>
                    </div>
                    <div class="col-4">
                        <div class="fw-bold" style="font-size:2rem;line-height:1.1;color:#c9ccd4;">
                            <i class="bi bi-lock-fill"></i>
                        </div>
                        <div class="small fw-semibold mt-1" style="font-size:.75rem;color:var(--text-muted);">Solde disponible</div>
                    </div>
                </div>

                <div class="alert alert-danger mt-3 mb-0 py-2 small">
                    <i class="bi bi-lock-fill me-1"></i>
                    <strong>Droits non encore ouverts.</strong>
                    Le congé annuel n'est accessible qu'après <strong>1 année de service accomplie</strong>.
                    @if($agent->dateOuvertureDroit())
                        Vos droits seront ouverts le <strong>{{ $agent->dateOuvertureDroit()->format('d/m/Y') }}</strong>.
                    @endif
                    @if($agent->bonusEnfants() > 0)
                        <br>
                        <span class="text-muted">
                            Le bonus de {{ $agent->bonusEnfants() }} jour(s) lié à vos enfants de moins de 14 ans
                            sera ajouté à cette date.
                        </span>
                    @endif
                </div>
            @else
                <div class="row text-center my-1">
                    <div class="col-4 border-end">
                        <div class="fw-bold text-success" style="font-size:2rem;line-height:1.1;">{{ $agent->joursAcquis() }}</div>
                        <div class="small fw-semibold mt-1" style="font-size:.75rem;color:var(--text-muted);">Jours acquis</div>
                    </div>
                    <div class="col-4 border-end">
                        <div class="fw-bold text-secondary" style="font-size:2rem;line-height:1.1;">{{ $agent->joursConsommes() }}</div>
                        <div class="small fw-semibold mt-1" style="font-size:.75rem;color:var(--text-muted);">Jours consommés</div>
                    </div>
                    <div class="col-4">
                        <div class="fw-bold" style="font-size:2rem;line-height:1.1;color:var(--accent);">{{ $agent->soldeDisponible() }}</div>
                        <div class="small fw-semibold mt-1" style="font-size:.75rem;color:var(--text-muted);">Solde disponible</div>
                    </div>
                </div>
                @if($agent->bonusEnfants() > 0)
                    <div class="text-center small text-muted mt-2">
                        Dont {{ $agent->bonusEnfants() }} jour(s) au titre des enfants de moins de 14 ans.
                    </div>
                @endif
            @endif

            <div class="text-center small text-muted mt-3">
                {{ $agent->anneesService() }} année(s) de service —
                <a href="{{ route('employe.reglement') }}">voir le code du travail</a>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
