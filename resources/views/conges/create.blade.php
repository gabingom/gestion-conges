@extends('layouts.app')

@section('title', 'Nouvelle Demande de Congé')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h2><i class="bi bi-calendar-plus text-muted me-2"></i> Nouvelle Demande</h2>
        <nav class="breadcrumb"><span>Congés / Enregistrer</span></nav>
    </div>
    <a href="{{ route('conges.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Retour
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-transparent fw-bold text-dark">
        Dépôt et traitement d'un dossier de congé (Conforme Code du Travail)
    </div>
    <div class="card-body">
        <form action="{{ route('conges.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Agent concerné <span class="text-danger">*</span></label>
                    <select name="agent_id" class="form-select" required>
                        <option value="">Sélectionner un agent...</option>
                        @foreach($agents as $agent)
                            <option value="{{ $agent->id }}">{{ $agent->prenom }} {{ $agent->nom }} (Solde : {{ $agent->jours_conges_dus }}j)</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Type de congé <span class="text-danger">*</span></label>
                    <select name="type_conge" id="type_conge" class="form-select" required>
                        <option value="Annuel">Congé Annuel (Droit standard)</option>
                        <option value="Maternité">Congé de Maternité (14 semaines - Justificatif requis)</option>
                        <option value="Décès Descendant">Décès d'un descendant (4 jours - Non déductible)</option>
                        <option value="Décès Ascendant">Décès d'un ascendant (2 jours - Non déductible)</option>
                        <option value="Mariage">Mariage de l'employé (3 jours - Non déductible)</option>
                        <option value="Naissance/Baptême">Naissance / Baptême d'un enfant (1 jour - Non déductible)</option>
                        <option value="Accident de travail">Accident de travail (Sur instruction médicale)</option>
                    </select>
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nombre de jours <span class="text-danger">*</span></label>
                    <input type="number" name="jours_a_prendre" id="jours_a_prendre" class="form-control" min="1" placeholder="Ex: 2" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Date de cessation <span class="text-danger">*</span></label>
                    <input type="date" name="date_cessation" class="form-control" required>
                </div>
            </div>

            <div class="row g-3 mb-3 align-items-center">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Statut <span class="text-danger">*</span></label>
                    <select name="statut" class="form-select border-danger-subtle">
                        <option value="En attente">En attente</option>
                        <option value="Approuvé">Approuvé</option>
                        <option value="Refusé">Refusé</option>
                    </select>
                </div>

                <div class="col-md-4 pt-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="deductible" id="deductible" value="1" checked>
                        <label class="form-check-label fw-bold" for="deductible">Déductible du solde</label>
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold" id="label_justificatif">Document justificatif</label>
                    <input type="file" name="justificatif" id="justificatif" class="form-control">
                    <div class="form-text small">PDF, JPG ou PNG (Obligatoire pour les cas exceptionnels)</div>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Commentaire / Motif</label>
                <textarea name="commentaire" id="commentaire" class="form-control" rows="3" placeholder="Notes optionnelles ou obligatoires sur la demande..."></textarea>
            </div>

            <button type="submit" class="btn btn-danger px-4">
                <i class="bi bi-check-circle me-1"></i> Enregistrer la demande
            </button>
        </form>
    </div>
</div>

<script>
document.getElementById('type_conge').addEventListener('change', function() {
    let type = this.value;
    let inputJours = document.getElementById('jours_a_prendre');
    let checkDeductible = document.getElementById('deductible');
    let labelJustificatif = document.getElementById('label_justificatif');
    let inputJustificatif = document.getElementById('justificatif');

    // Réinitialisation par défaut
    inputJours.readOnly = false;
    checkDeductible.checked = true;
    labelJustificatif.innerHTML = "Document justificatif";
    inputJustificatif.required = false;

    // Règles d'automatisation
    if (type === 'Maternité') {
        inputJours.value = 98; // 14 semaines
        inputJours.readOnly = true;
        checkDeductible.checked = false;
        labelJustificatif.innerHTML = "Document justificatif <span class='text-danger'>* (Certificat médical obligatoire)</span>";
        inputJustificatif.required = true;
    }
    else if (type === 'Décès Descendant') {
        inputJours.value = 4;
        inputJours.readOnly = true;
        checkDeductible.checked = false;
    }
    else if (type === 'Décès Ascendant') {
        inputJours.value = 2;
        inputJours.readOnly = true;
        checkDeductible.checked = false;
    }
    else if (type === 'Mariage') {
        inputJours.value = 3;
        inputJours.readOnly = true;
        checkDeductible.checked = false;
    }
    else if (type === 'Naissance/Baptême') {
        inputJours.value = 1;
        inputJours.readOnly = true;
        checkDeductible.checked = false;
    }
    else if (type === 'Accident de travail') {
        inputJours.value = "";
        inputJours.placeholder = "Selon instruction du médecin";
        checkDeductible.checked = false;
        labelJustificatif.innerHTML = "Document justificatif <span class='text-danger'>* (Avis médical obligatoire)</span>";
        inputJustificatif.required = true;
    }
    else if (type === 'Annuel') {
        inputJours.value = "";
        inputJours.placeholder = "Ex: 2";
    }
});
</script>
@endsection
