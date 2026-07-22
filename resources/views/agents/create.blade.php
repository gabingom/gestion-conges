@extends('layouts.app')

@section('title', 'Nouvel Agent')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h2><i class="bi bi-person-plus"></i> Nouvel Agent</h2>
        <nav class="breadcrumb"><span>Agents</span> / <span>Nouveau</span></nav>
    </div>
    <a href="{{ route('agents.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Retour
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('agents.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nom</label>
                    <input type="text" name="nom" class="form-control @error('nom') is-invalid @enderror"
                        value="{{ old('nom') }}" placeholder="Ex: DIALLO">
                    @error('nom') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Prénom</label>
                    <input type="text" name="prenom" class="form-control @error('prenom') is-invalid @enderror"
                        value="{{ old('prenom') }}" placeholder="Ex: Aminata">
                    @error('prenom') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Matricule de solde</label>
                    <input type="text" name="matricule_solde" class="form-control @error('matricule_solde') is-invalid @enderror"
                        value="{{ old('matricule_solde') }}" placeholder="Ex: 123456T">
                    @error('matricule_solde') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Sexe</label>
                    <select name="sexe" class="form-select @error('sexe') is-invalid @enderror">
                        <option value="">-- Sélectionner --</option>
                        <option value="homme" {{ old('sexe') == 'homme' ? 'selected' : '' }}>Homme</option>
                        <option value="femme" {{ old('sexe') == 'femme' ? 'selected' : '' }}>Femme</option>
                    </select>
                    @error('sexe') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Type d'agent</label>
                    <select name="type_agent" class="form-select @error('type_agent') is-invalid @enderror">
                        <option value="titulaire" {{ old('type_agent') == 'titulaire' ? 'selected' : '' }}>Titulaire</option>
                        <option value="contractuel" {{ old('type_agent') == 'contractuel' ? 'selected' : '' }}>Contractuel</option>
                    </select>
                    @error('type_agent') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">
                        Nombre d'enfants <span class="text-danger">de moins de 14 ans</span>
                    </label>
                    <input type="number" name="nombre_enfants" class="form-control @error('nombre_enfants') is-invalid @enderror"
                        value="{{ old('nombre_enfants', 0) }}" min="0" placeholder="Enfants de moins de 14 ans uniquement">
                    <small class="text-muted d-block">
                        <i class="bi bi-exclamation-circle text-danger"></i>
                        <strong>Ne comptez que les enfants âgés de moins de 14 ans.</strong>
                        Chaque enfant déclaré ouvre droit à 1 jour de congé supplémentaire pour les agents de sexe féminin,
                        applicable après 1 an de service.
                    </small>
                    @error('nombre_enfants') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Lieu d'affectation</label>
                    <select name="lieu_affectation" class="form-select @error('lieu_affectation') is-invalid @enderror">
                        <option value="">-- Sélectionner --</option>
                        <optgroup label="Directions">
                            <option value="Direction des Ressources Humaines" {{ old('lieu_affectation') == 'Direction des Ressources Humaines' ? 'selected' : '' }}>Direction des Ressources Humaines</option>
                            <option value="Direction Financière" {{ old('lieu_affectation') == 'Direction Financière' ? 'selected' : '' }}>Direction Financière</option>
                            <option value="Direction des Affaires Académiques" {{ old('lieu_affectation') == 'Direction des Affaires Académiques' ? 'selected' : '' }}>Direction des Affaires Académiques</option>
                            <option value="Direction des Systèmes d'Information" {{ old('lieu_affectation') == "Direction des Systèmes d'Information" ? 'selected' : '' }}>Direction des Systèmes d'Information</option>
                        </optgroup>
                        <optgroup label="UFR">
                            <option value="UFR Sciences fondamentales et de l'Ingénieur" {{ old('lieu_affectation') == "UFR Sciences fondamentales et de l'Ingénieur" ? 'selected' : '' }}>UFR Sciences fondamentales et de l'Ingénieur</option>
                            <option value="UFR Sciences agronomiques, Élevage, Pêche, Aquaculture et Nutrition" {{ old('lieu_affectation') == "UFR Sciences agronomiques, Élevage, Pêche, Aquaculture et Nutrition" ? 'selected' : '' }}>UFR Sciences agronomiques, Élevage, Pêche, Aquaculture et Nutrition</option>
                            <option value="UFR Sciences économiques, Juridiques et Tourisme" {{ old('lieu_affectation') == "UFR Sciences économiques, Juridiques et Tourisme" ? 'selected' : '' }}>UFR Sciences économiques, Juridiques et Tourisme</option>
                            <option value="UFR Sciences sociales et Environnementales" {{ old('lieu_affectation') == "UFR Sciences sociales et Environnementales" ? 'selected' : '' }}>UFR Sciences sociales et Environnementales</option>
                        </optgroup>
                        <optgroup label="Rectorat">
                            <option value="Rectorat" {{ old('lieu_affectation') == 'Rectorat' ? 'selected' : '' }}>Rectorat</option>
                            <option value="Vice-Recteur chargé de la Pédagogie" {{ old('lieu_affectation') == 'Vice-Recteur chargé de la Pédagogie' ? 'selected' : '' }}>Vice-Recteur chargé de la Pédagogie</option>
                            <option value="Vice-Recteur chargé de la Recherche" {{ old('lieu_affectation') == 'Vice-Recteur chargé de la Recherche' ? 'selected' : '' }}>Vice-Recteur chargé de la Recherche</option>
                        </optgroup>
                    </select>
                    @error('lieu_affectation') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Date de prise de service</label>
                    <input type="date" name="date_prise_service" class="form-control @error('date_prise_service') is-invalid @enderror"
                        value="{{ old('date_prise_service') }}">
                    <small class="text-muted">Les jours de congés seront calculés automatiquement</small>
                    @error('date_prise_service') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="text-end mt-4">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-save"></i> Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
