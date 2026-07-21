@extends('layouts.app')

@section('title', 'Modifier le Congé')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h2><i class="bi bi-pencil-square text-muted me-2"></i> Traiter / Modifier la Demande</h2>
        <nav class="breadcrumb"><span>Congés / Édition</span></nav>
    </div>
    <a href="{{ route('conges.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Annuler
    </a>
</div>

<div class="card max-w-3xl mx-auto">
    <div class="card-header bg-transparent fw-bold text-dark">
        Mise à jour du dossier de congé de {{ $conge->agent->prenom }} {{ $conge->agent->nom }}
    </div>
    <div class="card-body p-4">
        <form action="{{ route('conges.update', $conge) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <!-- Agent (Bloqué ou modifiable, ici modifiable selon le contrôleur de Claude) -->
                <div class="col-md-12">
                    <label class="form-label">Agent concerné</label>
                    <select name="agent_id" class="form-select" required>
                        @foreach($agents as $agent)
                            <option value="{{ $agent->id }}" {{ $conge->agent_id == $agent->id ? 'selected' : '' }}>
                                {{ $agent->nom }} {{ $agent->prenom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Jours à prendre -->
                <div class="col-md-6">
                    <label class="form-label">Nombre de jours</label>
                    <input type="number" name="jours_a_prendre" class="form-control" min="1" value="{{ old('jours_a_prendre', $conge->jours_a_prendre) }}" required>
                </div>

                <!-- Date de cessation -->
                <div class="col-md-6">
                    <label class="form-label">Date de cessation</label>
                    <input type="date" name="date_cessation" class="form-control" value="{{ old('date_cessation', $conge->date_cessation ? $conge->date_cessation->format('Y-m-d') : '') }}" required>
                </div>

                <!-- Statut -->
                <div class="col-md-4">
                    <label class="form-label">Statut de la demande</label>
                    <select name="statut" class="form-select" required>
                        <option value="en_attente" {{ $conge->statut == 'en_attente' ? 'selected' : '' }}>En attente</option>
                        <option value="approuve" {{ $conge->statut == 'approuve' ? 'selected' : '' }}>Approuvé</option>
                        <option value="refuse" {{ $conge->statut == 'refuse' ? 'selected' : '' }}>Refusé</option>
                    </select>
                </div>

                <!-- Options -->
                <div class="col-md-4 d-flex align-items-center pt-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="deductible" id="deductible" value="1" {{ $conge->deductible ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold" for="deductible">Déductible du solde</label>
                    </div>
                </div>

                <div class="col-md-4 d-flex align-items-center pt-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="exceptionnel" id="exceptionnel" value="1" {{ $conge->exceptionnel ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold" for="exceptionnel">Congé exceptionnel</label>
                    </div>
                </div>

                <!-- Commentaire -->
                <div class="col-12">
                    <label class="form-label">Commentaire / Motif du traitement</label>
                    <textarea name="commentaire" class="form-control" rows="3">{{ old('commentaire', $conge->commentaire) }}</textarea>
                </div>
            </div>

            <div class="mt-4 pt-3 border-top d-flex justify-content-end">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-save me-1"></i> Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
