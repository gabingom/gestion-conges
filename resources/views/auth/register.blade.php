@extends('layouts.app')

@section('title', 'Nouvel Utilisateur')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h2><i class="bi bi-person-plus text-muted me-2"></i> Nouvel Utilisateur</h2>
        <nav class="breadcrumb"><span>Administration / Créer un compte</span></nav>
    </div>
    <a href="{{ route('dashboard') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Retour
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-transparent fw-bold text-dark py-3">
                <i class="bi bi-shield-lock me-1 text-muted"></i> Compte back-office (administration)
            </div>
            <div class="card-body p-4">

                <p class="text-muted small mb-4">
                    <i class="bi bi-info-circle me-1"></i>
                    Ce formulaire crée un compte <strong>administrateur</strong> ou <strong>gestionnaire</strong>.
                    Pour créer un compte employé, passez par la section « Nouvel Agent » : le compte est généré automatiquement.
                </p>

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('register') }}" method="POST">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Nom complet <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}" placeholder="Ex : Awa NDIAYE" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label">Adresse e-mail <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email') }}" placeholder="exemple@ussein.local" required>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="password" class="form-label">Mot de passe <span class="text-danger">*</span></label>
                            <input type="password" name="password" id="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   placeholder="6 caractères minimum" required>
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label">Confirmer le mot de passe <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                   class="form-control" placeholder="Retapez le mot de passe" required>
                        </div>

                        <div class="col-md-6">
                            <label for="role" class="form-label">Rôle <span class="text-danger">*</span></label>
                            <select name="role" id="role" class="form-select @error('role') is-invalid @enderror" required>
                                <option value="" disabled {{ old('role') ? '' : 'selected' }}>Choisir un rôle...</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrateur</option>
                                <option value="gestionnaire" {{ old('role') == 'gestionnaire' ? 'selected' : '' }}>Gestionnaire</option>
                            </select>
                            @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="text-end mt-4">
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary me-2">Annuler</a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check-lg me-1"></i> Créer le compte
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection
