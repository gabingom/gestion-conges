@extends('layouts.app')

@section('title', 'Enregistrer une Absence')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h2><i class="bi bi-patch-plus text-muted me-2"></i> Nouvelle Absence</h2>
        <nav class="breadcrumb"><span>Absences / Enregistrer un événement</span></nav>
    </div>
    <a href="{{ route('absences.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Retour
    </a>
</div>

<div class="card max-w-3xl mx-auto">
    <div class="card-header bg-transparent fw-bold text-dark">
        Fiche de déclaration d'absence
    </div>
    <div class="card-body p-4">
        <form action="{{ route('absences.store') }}" method="POST">
            @csrf

            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Agent concerné <span class="text-danger">*</span></label>
                    <select name="agent_id" class="form-select" required>
                        <option value="" disabled selected>Choisir un agent...</option>
                        @foreach($agents as $agent)
                            <option value="{{ $agent->id }}" {{ old('agent_id') == $agent->id ? 'selected' : '' }}>
                                {{ $agent->nom }} {{ $agent->prenom }} ({{ $agent->matricule_solde }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Motif de l'absence <span class="text-danger">*</span></label>
                    <select name="motif" class="form-select" required>
                        <option value="" disabled selected>Choisir un motif...</option>
                        <option value="maladie" {{ old('motif') == 'maladie' ? 'selected' : '' }}>Maladie / Accident</option>
                        <option value="maternite" {{ old('motif') == 'maternite' ? 'selected' : '' }}>Maternité</option>
                        <option value="paternite" {{ old('motif') == 'paternite' ? 'selected' : '' }}>Paternité</option>
                        <option value="evenement_familial" {{ old('motif') == 'evenement_familial' ? 'selected' : '' }}>Événement Familial</option>
                        <option value="injustifie" {{ old('motif') == 'injustifie' ? 'selected' : '' }}>Injustifié / Autre</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Nombre de jours <small class="text-muted">(si non fixe)</small></label>
                    <input type="number" name="nombre_jours" class="form-control" min="1" value="{{ old('nombre_jours') }}" placeholder="Ex: 3">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Date de début <span class="text-danger">*</span></label>
                    <input type="date" name="date_debut" class="form-control" value="{{ old('date_debut') }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Référence du justificatif</label>
                    <input type="text" name="document_justificatif" class="form-control" value="{{ old('document_justificatif') }}" placeholder="Ex: Certificat médical N°...">
                </div>

                <div class="col-md-6 pt-2">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="valide_par_medecin" id="valide_par_medecin" value="1">
                        <label class="form-check-label fw-semibold" for="valide_par_medecin">Validé par un médecin agréé</label>
                    </div>
                </div>

                <div class="col-md-6 pt-2">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="rappelable" id="rappelable" value="1">
                        <label class="form-check-label fw-semibold" for="rappelable">Agent rappelable en cas d'urgence</label>
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label">Commentaire / Notes</label>
                    <textarea name="commentaire" class="form-control" rows="3" placeholder="Précisions sur l'absence...">{{ old('commentaire') }}</textarea>
                </div>
            </div>

            <div class="mt-4 pt-3 border-top d-flex justify-content-end">
                <button type="submit" class="btn btn-danger px-4">
                    <i class="bi bi-save me-1"></i> Valider l'absence
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
