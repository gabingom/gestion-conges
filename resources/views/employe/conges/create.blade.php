@extends('layouts.employe')

@section('title', 'Nouvelle demande de congé')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h2><i class="bi bi-calendar-plus text-muted me-2"></i> Nouvelle demande de congé</h2>
        <nav class="breadcrumb"><span>Mes congés / Nouvelle demande</span></nav>
    </div>
    <a href="{{ route('employe.conges') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Retour</a>
</div>

@if($agent)
<div class="card mb-4" style="border-left:4px solid var(--accent);">
    <div class="card-body py-3">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div class="small">
                <i class="bi bi-info-circle-fill text-danger me-1"></i>
                <strong>Rappel :</strong> le congé annuel exige 1 an de service, à raison de 24 jours par an
                (cumul plafonné à {{ \App\Models\Agent::CUMUL_MAX }} jours).
                <a href="{{ route('employe.reglement') }}">Consulter le code du travail</a>
            </div>
            <div class="text-center">
                <div class="fw-bold" style="font-size:1.5rem;line-height:1;color:var(--accent);">{{ $agent->soldeDisponible() }}</div>
                <div class="small text-muted">Solde disponible</div>
            </div>
        </div>

        @unless($agent->aUnAnDeService())
            <div class="alert alert-warning mt-3 mb-0 py-2 small">
                <i class="bi bi-exclamation-triangle-fill me-1"></i>
                Vous n'avez pas encore 1 an de service : toute demande de <strong>congé annuel</strong> sera refusée par la plateforme.
                @if($agent->dateOuvertureDroit())
                    Droits ouverts le <strong>{{ $agent->dateOuvertureDroit()->format('d/m/Y') }}</strong>.
                @endif
            </div>
        @endunless
    </div>
</div>
@endif

<div class="card">
    <div class="card-header bg-transparent fw-bold text-dark">Formulaire de demande</div>
    <div class="card-body p-4">
        <form action="{{ route('employe.conges.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label">Type de congé <span class="text-danger">*</span></label>
                    <select name="type_conge" id="type_conge" class="form-select @error('type_conge') is-invalid @enderror" required onchange="majFormulaire()">
                        <option value="Annuel" {{ old('type_conge')=='Annuel'?'selected':'' }}>Congé annuel (24 j / an)</option>
                        @if($agent && $agent->sexe === 'femme')
                            <option value="Maternite" {{ old('type_conge')=='Maternite'?'selected':'' }}>Congé de maternité (6 sem. avant + 8 sem. après)</option>
                        @endif
                        <option value="Exceptionnel" {{ old('type_conge')=='Exceptionnel'?'selected':'' }}>Congé exceptionnel</option>
                        <option value="Maladie" {{ old('type_conge')=='Maladie'?'selected':'' }}>Congé maladie</option>
                    </select>
                    @error('type_conge') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- ===== MATERNITÉ : durée calculée automatiquement ===== -->
                <div class="col-md-6 bloc-maternite" style="display:none;">
                    <label class="form-label">Date prévue d'accouchement <span class="text-danger">*</span></label>
                    <input type="date" name="date_accouchement" id="date_accouchement"
                           class="form-control @error('date_accouchement') is-invalid @enderror"
                           value="{{ old('date_accouchement') }}" onchange="calculerMaternite()">
                    <small class="text-muted">Le début et la fin du congé sont calculés automatiquement.</small>
                    @error('date_accouchement') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-12 bloc-maternite" style="display:none;">
                    <div class="alert alert-info py-2 mb-0 small" id="apercu_maternite">
                        <i class="bi bi-calendar-range me-1"></i>
                        Sélectionnez la date prévue d'accouchement pour voir la période de congé.
                    </div>
                </div>

                <div class="col-md-6 bloc-maternite" style="display:none;">
                    <label class="form-label">Jours de prolongation <small class="text-muted">(sur avis médical)</small></label>
                    <input type="number" name="jours_prolongation" id="jours_prolongation" min="1" class="form-control"
                           value="{{ old('jours_prolongation') }}" placeholder="Laisser vide si aucune"
                           onchange="calculerMaternite()">
                    <small class="text-muted">Toute prolongation exige un certificat médical.</small>
                </div>

                <!-- ===== AUTRES CONGÉS ===== -->
                <div class="col-md-6 bloc-standard">
                    <label class="form-label">Nombre de jours <span class="text-danger">*</span></label>
                    <input type="number" name="jours_a_prendre" id="jours_a_prendre" min="1"
                           class="form-control @error('jours_a_prendre') is-invalid @enderror"
                           value="{{ old('jours_a_prendre') }}">
                    @error('jours_a_prendre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6 bloc-standard">
                    <label class="form-label">Date de début souhaitée <span class="text-danger">*</span></label>
                    <input type="date" name="date_cessation" id="date_cessation"
                           class="form-control @error('date_cessation') is-invalid @enderror"
                           value="{{ old('date_cessation') }}">
                    @error('date_cessation') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Justificatif <small class="text-muted">(obligatoire si prolongation)</small></label>
                    <input type="file" name="justificatif" class="form-control @error('justificatif') is-invalid @enderror" accept=".pdf,.jpg,.jpeg,.png">
                    @error('justificatif') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Motif / commentaire</label>
                    <textarea name="commentaire" rows="3" class="form-control" placeholder="Précisez la raison de votre demande...">{{ old('commentaire') }}</textarea>
                </div>
            </div>

            <div class="text-end mt-4">
                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-send"></i> Envoyer la demande</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    var AVANT = {{ \App\Models\Agent::MATERNITE_AVANT }};
    var APRES = {{ \App\Models\Agent::MATERNITE_APRES }};

    function majFormulaire() {
        var type = document.getElementById('type_conge').value;
        var maternite = (type === 'Maternite');

        document.querySelectorAll('.bloc-maternite').forEach(function (el) {
            el.style.display = maternite ? '' : 'none';
        });
        document.querySelectorAll('.bloc-standard').forEach(function (el) {
            el.style.display = maternite ? 'none' : '';
        });

        document.getElementById('date_accouchement').required = maternite;
        document.getElementById('jours_a_prendre').required   = !maternite;
        document.getElementById('date_cessation').required    = !maternite;

        if (maternite) calculerMaternite();
    }

    function formatFr(d) {
        return ('0' + d.getDate()).slice(-2) + '/' + ('0' + (d.getMonth() + 1)).slice(-2) + '/' + d.getFullYear();
    }

    function calculerMaternite() {
        var val  = document.getElementById('date_accouchement').value;
        var zone = document.getElementById('apercu_maternite');

        if (!val) {
            zone.innerHTML = '<i class="bi bi-calendar-range me-1"></i> Selectionnez la date prevue d\'accouchement pour voir la periode de conge.';
            return;
        }

        var prolong = parseInt(document.getElementById('jours_prolongation').value || 0, 10);
        var acc = new Date(val);

        var debut = new Date(acc); debut.setDate(debut.getDate() - AVANT);
        var fin   = new Date(acc); fin.setDate(fin.getDate() + APRES + prolong);
        var total = AVANT + APRES + prolong;

        zone.innerHTML = '<i class="bi bi-calendar-range me-1"></i> Conge du <strong>'
            + formatFr(debut) + '</strong> au <strong>' + formatFr(fin)
            + '</strong> &mdash; soit <strong>' + total + ' jours</strong>.';
    }

    document.addEventListener('DOMContentLoaded', majFormulaire);
</script>
@endpush
@endsection
