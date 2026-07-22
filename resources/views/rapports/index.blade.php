@extends('layouts.app')

@section('title', 'Rapports PDF')

@section('content')
<div class="page-header">
    <h2><i class="bi bi-file-earmark-pdf text-muted me-2"></i> Rapports PDF</h2>
    <nav class="breadcrumb"><span>Rapports / Génération</span></nav>
</div>

<p class="text-muted mb-4">
    <i class="bi bi-info-circle me-1"></i>
    Choisissez un type de rapport, précisez la période, puis générez le document.
</p>

<style>
    .carte-rapport { cursor:pointer; display:block; margin:0; }
    .carte-rapport .card { transition:all .18s; border:2px solid transparent; position:relative; }
    .carte-rapport:hover .card { border-color:#d5d9e2; }
    .carte-rapport input:checked + .card { border-color:var(--accent); box-shadow:0 6px 18px rgba(233,69,96,.16); }
    .carte-rapport .coche { position:absolute; top:10px; right:12px; color:var(--accent); opacity:0; transition:opacity .18s; }
    .carte-rapport input:checked + .card .coche { opacity:1; }
    .icone-rapport { width:34px; height:34px; border-radius:9px; background:var(--dark);
                     color:#fff; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
    .carte-rapport input:checked + .card .icone-rapport { background:var(--accent); }
</style>

<form action="{{ route('rapports.generer') }}" method="GET" id="formRapport">

    <!-- ===== 1. CHOIX DU RAPPORT ===== -->
    <div class="row g-3 mb-4">

        @php
            $choix = [
                ['agents',   'bi-people',          'Agents inscrits',      'Liste du personnel avec toutes les informations administratives : matricule, affectation, ancienneté et situation de congés.'],
                ['conges',   'bi-calendar-check',  'Demandes de congé',    'Toutes les demandes de congé de la période, réparties entre reçues, approuvées, refusées et en attente.'],
                ['absences', 'bi-calendar-x',      "Demandes d'absence",   "Toutes les demandes d'absence de la période, réparties entre reçues, approuvées, refusées et en attente."],
                ['complet',  'bi-journals',        'Rapport complet',      'Document unique réunissant les agents inscrits, les demandes de congé et les demandes d\'absence.'],
            ];
        @endphp

        @foreach($choix as $c)
            <div class="col-md-6 col-xl-3">
                <label class="carte-rapport w-100 h-100" for="type_{{ $c[0] }}">
                    <input type="radio" name="type" id="type_{{ $c[0] }}" value="{{ $c[0] }}"
                           class="d-none" {{ $loop->first ? 'checked' : '' }} required>
                    <div class="card h-100 p-3">
                        <div class="d-flex align-items-center mb-2">
                            <div class="icone-rapport me-2"><i class="bi {{ $c[1] }}"></i></div>
                            <span class="fw-bold" style="color:var(--dark);">{{ $c[2] }}</span>
                        </div>
                        <p class="small text-muted mb-0">{{ $c[3] }}</p>
                        <span class="coche"><i class="bi bi-check-circle-fill"></i></span>
                    </div>
                </label>
            </div>
        @endforeach
    </div>

    <!-- ===== 2. PÉRIODE ET FILTRE ===== -->
    <div class="card mb-4">
        <div class="card-header bg-transparent fw-bold text-dark py-3">
            <i class="bi bi-calendar-range text-muted me-1"></i> Période et filtre
        </div>
        <div class="card-body p-4">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Date de début</label>
                    <input type="date" name="date_debut" class="form-control" value="{{ request('date_debut') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Date de fin</label>
                    <input type="date" name="date_fin" class="form-control" value="{{ request('date_fin') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Affectation <small class="text-muted">(optionnel)</small></label>
                    <input type="text" name="affectation" class="form-control"
                           value="{{ request('affectation') }}" placeholder="Ex : Rectorat, UFR, Direction">
                </div>
            </div>
            <small class="text-muted d-block mt-3">
                <i class="bi bi-lightbulb"></i>
                Sans période, le rapport porte sur l'ensemble des enregistrements.
                Pour les agents, la période s'applique à la date de prise de service ;
                pour les congés et absences, à la date de la demande.
            </small>
        </div>
    </div>

    <div class="text-end">
        <button type="submit" class="btn btn-primary px-4">
            <i class="bi bi-eye me-1"></i> Aperçu du rapport
        </button>
        <button type="submit" formaction="{{ route('rapports.export-pdf') }}" class="btn btn-danger px-4">
            <i class="bi bi-file-earmark-pdf me-1"></i> Télécharger le PDF
        </button>
    </div>
</form>


@endsection
