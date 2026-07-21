@extends('layouts.app')

@section('title', 'Modifier l\'Absence')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h2><i class="bi bi-pencil-square text-muted me-2"></i> Modifier l'Absence</h2>
        <nav class="breadcrumb"><span>Absences / Édition</span></nav>
    </div>
    <a href="{{ route('absences.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Annuler
    </a>
</div>

<div class="card max-w-3xl mx-auto">
    <div class="card-header bg-transparent fw-bold text-dark">
        Mise à jour de l'absence de {{ $absence->agent->prenom }} {{ $absence->agent->nom }}
    </div>
    <div class="card-body p-4">
        <form action="{{ route('absences.update', $absence) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Agent concerné</label>
                    <select name="agent_id" class="form-select" required>
                        @foreach($agents as $agent)
                            <option value="{{ $agent->id }}" {{ $absence->agent_id == $agent->id ? 'selected' : '' }}>
                                {{ $agent->nom }} {{ $agent->prenom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Motif de l'absence</label>
                    <select name="motif" class="form-select" required>
                        <option value="maladie" {{ $absence->motif == 'maladie' ? 'selected' : '' }}>Maladie / Accident</option>
                        <option value="maternite" {{ $absence->motif == 'maternite' ? 'selected' : '' }}>Maternité</option>
                        <option value="paternite" {{ $absence->motif == 'paternite' ? 'selected' : '' }}>Paternité</option>
                        <option value="evenement_familial" {{ $absence->motif == 'evenement_familial' ? 'selected' : '' }}>Événement Familial</option>
                        <option value="injustifie" {{ $absence->motif == 'injustifie' ? 'selected' : '' }}>Injustifié / Autre</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Nombre de jours</label>
                    <input type="number" name="nombre_jours" class="form-control" min="1" value="{{ old('nombre_jours', $absence->nombre_jours) }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Date de début</label>
                    <input type="date" name="date_debut" class="form-control" value="{{ old('date_debut', $absence->date_debut ? \Carbon\Carbon::parse($absence->date_debut)->format('Y-m-d') : '') }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Référence du justificatif</label>
                    <input type="text" name="document_justificatif" class="form-control" value="{{ old('document_justificatif', $absence->document_justificatif) }}">
                </div>

                <div class="col-md-6 pt-2">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="valide_par_medecin" id="valide_par_medecin" value="1" {{ $absence->valide_par_medecin ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="valide_par_medecin">Validé par un médecin agréé</label>
                    </div>
                </div>

                <div class="col-md-6 pt-2">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="rappelable" id="rappelable" value="1" {{ $absence->rappelable ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="rappelable">Agent rappelable en cas d'urgence</label>
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label">Commentaire / Notes</label>
                    <textarea name="commentaire" class="form-control" rows="3">{{ old('commentaire', $absence->commentaire) }}</textarea>
                </div>
            </div>

            <div class="mt-4 pt-3 border-top d-flex justify-content-end">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-save me-1"></i> Sauvegarder
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
