@extends('layouts.app')

@section('title', 'Nouveau Jour Férié')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2><i class="bi bi-calendar-plus text-muted me-2"></i> Ajouter un Jour Férié</h2>
        <nav class="breadcrumb"><span>Configuration / Jours Fériés / Nouveau</span></nav>
    </div>
    <a href="{{ route('jours-feries.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Retour
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-transparent fw-bold text-dark py-3">
        Enregistrer un jour chômé ou fête légale
    </div>
    <div class="card-body">
        <form action="{{ route('jours-feries.store') }}" method="POST">
            @csrf

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nom du jour férié <span class="text-danger">*</span></label>
                    <select name="nom" class="form-select @error('nom') is-invalid @enderror" required>
                        <option value="" selected disabled>Sélectionner un jour férié...</option>

                        <optgroup label="Fêtes Civiles et Nationales">
                            <option value="Nouvel an" {{ old('nom') == 'Nouvel an' ? 'selected' : '' }}>Nouvel an (1er Janvier)</option>
                            <option value="Fête de l'Indépendance" {{ old('nom') == "Fête de l'Indépendance" ? 'selected' : '' }}>Fête de l'Indépendance (4 Avril)</option>
                            <option value="Fête du Travail" {{ old('nom') == 'Fête du Travail' ? 'selected' : '' }}>Fête du Travail (1er Mai)</option>
                        </optgroup>

                        <optgroup label="Fêtes Musulmanes">
                            <option value="Tamkharit" {{ old('nom') == 'Tamkharit' ? 'selected' : '' }}>Tamkharit</option>
                            <option value="Maouloud" {{ old('nom') == 'Maouloud' ? 'selected' : '' }}>Maouloud (Gamou)</option>
                            <option value="Korité" {{ old('nom') == 'Korité' ? 'selected' : '' }}>Korité (Eid al-Fitr)</option>
                            <option value="Tabaski" {{ old('nom') == 'Tabaski' ? 'selected' : '' }}>Tabaski (Eid al-Adha)</option>
                            <option value="Magal de Touba" {{ old('nom') == 'Magal de Touba' ? 'selected' : '' }}>Grand Magal de Touba</option>
                        </optgroup>

                        <optgroup label="Fêtes Chrétiennes">
                            <option value="Lundi de Pâques" {{ old('nom') == 'Lundi de Pâques' ? 'selected' : '' }}>Lundi de Pâques</option>
                            <option value="Ascension" {{ old('nom') == 'Ascension' ? 'selected' : '' }}>Ascension</option>
                            <option value="Lundi de Pentecôte" {{ old('nom') == 'Lundi de Pentecôte' ? 'selected' : '' }}>Lundi de Pentecôte</option>
                            <option value="Assomption" {{ old('nom') == 'Assomption' ? 'selected' : '' }}>Assomption (15 Août)</option>
                            <option value="Toussaint" {{ old('nom') == 'Toussaint' ? 'selected' : '' }}>Toussaint (1er Novembre)</option>
                            <option value="Noël" {{ old('nom') == 'Noël' ? 'selected' : '' }}>Noël (25 Décembre)</option>
                        </optgroup>
                    </select>
                    @error('nom') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Date <span class="text-danger">*</span></label>
                    <input type="date" name="date" class="form-control @error('date') is-invalid @enderror"
                           value="{{ old('date') }}" required>
                    @error('date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <button type="submit" class="btn btn-danger px-4">
                <i class="bi bi-check-circle me-1"></i> Enregistrer le jour férié
            </button>
        </form>
    </div>
</div>
@endsection
