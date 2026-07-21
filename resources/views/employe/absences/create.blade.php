@extends('layouts.employe')

@section('title', "Nouvelle demande d'absence")

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h2><i class="bi bi-calendar-x text-muted me-2"></i> Nouvelle demande d'absence</h2>
        <nav class="breadcrumb"><span>Mes absences / Nouvelle demande</span></nav>
    </div>
    <a href="{{ route('employe.absences') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Retour</a>
</div>

<div class="card">
    <div class="card-header bg-transparent fw-bold text-dark">Formulaire de demande</div>
    <div class="card-body p-4">
        <form action="{{ route('employe.absences.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Motif <span class="text-danger">*</span></label>
                    <select name="motif" id="motif" onchange="majAbsence()" class="form-select @error('motif') is-invalid @enderror" required>
                        <option value="" disabled selected>Choisir un motif...</option>
                        <option value="maladie" {{ old('motif')=='maladie'?'selected':'' }}>Maladie</option>
                        <option value="mariage" {{ old('motif')=='mariage'?'selected':'' }}>Mariage (3 j)</option>
                        <option value="naissance" {{ old('motif')=='naissance'?'selected':'' }}>Naissance (1 j)</option>
                        <option value="bapteme" {{ old('motif')=='bapteme'?'selected':'' }}>Baptême (1 j)</option>
                        <option value="deces_ascendant" {{ old('motif')=='deces_ascendant'?'selected':'' }}>Décès ascendant (2 j)</option>
                        <option value="deces_descendant" {{ old('motif')=='deces_descendant'?'selected':'' }}>Décès descendant (4 j)</option>
                        <option value="grossesse" {{ old('motif')=='grossesse'?'selected':'' }}>Grossesse / Maternité</option>
                        <option value="accident_travail" {{ old('motif')=='accident_travail'?'selected':'' }}>Accident de travail</option>
                        <option value="autorisation_personnelle" {{ old('motif')=='autorisation_personnelle'?'selected':'' }}>Autorisation personnelle</option>
                        <option value="autre" {{ old('motif')=='autre'?'selected':'' }}>Autre</option>
                    </select>
                    <small class="text-muted">Pour certains motifs, la durée est fixée automatiquement.</small>
                    @error('motif') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6" id="bloc_jours">
                    <label class="form-label">Nombre de jours <span class="text-danger">*</span></label>
                    <input type="number" name="nombre_jours" id="nombre_jours" min="1" class="form-control @error('nombre_jours') is-invalid @enderror" value="{{ old('nombre_jours') }}" placeholder="Ex: 3">
                    @error('nombre_jours') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Date de début <span class="text-danger">*</span></label>
                    <input type="date" name="date_debut" id="date_debut" class="form-control @error('date_debut') is-invalid @enderror" value="{{ old('date_debut') }}" onchange="majAbsence()" required>
                    @error('date_debut') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-12">
                    <div class="alert alert-info py-2 mb-0 small" id="apercu_absence" style="display:none;"></div>
                </div>
                <div class="col-12">
                    <label class="form-label">Commentaire</label>
                    <textarea name="commentaire" rows="3" class="form-control" placeholder="Précisez si nécessaire...">{{ old('commentaire') }}</textarea>
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
    var DUREES = {
        'mariage': 3,
        'naissance': 1,
        'bapteme': 1,
        'deces_ascendant': 2,
        'deces_descendant': 4
    };

    function formatFr(d) {
        return ('0' + d.getDate()).slice(-2) + '/' + ('0' + (d.getMonth() + 1)).slice(-2) + '/' + d.getFullYear();
    }

    function majAbsence() {
        var motif = document.getElementById('motif').value;
        var fixe  = DUREES.hasOwnProperty(motif);
        var bloc  = document.getElementById('bloc_jours');
        var champ = document.getElementById('nombre_jours');
        var zone  = document.getElementById('apercu_absence');

        // Duree fixee par le code du travail : on masque la saisie
        bloc.style.display = fixe ? 'none' : '';
        champ.required = !fixe;

        var debut = document.getElementById('date_debut').value;

        if (fixe) {
            var n = DUREES[motif];
            champ.value = n;
            if (debut) {
                var d = new Date(debut);
                var f = new Date(debut); f.setDate(f.getDate() + n - 1);
                zone.style.display = '';
                zone.innerHTML = '<i class="bi bi-calendar-range me-1"></i> Duree fixee par le code du travail : <strong>'
                    + n + ' jour(s)</strong> &mdash; du <strong>' + formatFr(d) + '</strong> au <strong>' + formatFr(f) + '</strong>.';
            } else {
                zone.style.display = '';
                zone.innerHTML = '<i class="bi bi-calendar-range me-1"></i> Duree fixee par le code du travail : <strong>' + n + ' jour(s)</strong>. Choisissez la date de debut.';
            }
        } else {
            zone.style.display = 'none';
        }
    }

    document.addEventListener('DOMContentLoaded', majAbsence);
</script>
@endpush
@endsection
