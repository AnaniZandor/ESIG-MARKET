@extends('layouts.app')

@section('title', 'Inscription')

@section('content')
<div class="auth-form-side" style="min-height:calc(100vh - 68px);">
    <div class="auth-form-inner">

        <h2>Créer un compte</h2>
        <p class="auth-subtitle">Rejoins la marketplace de l'ESIG</p>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Nom -->
            <div class="form-group">
                <label class="form-label">Nom complet</label>
                <input type="text" name="name"
                       class="form-control @error('name') is-error @enderror"
                       placeholder="Ex: Kofi Mensah"
                       value="{{ old('name') }}" required>
                @error('name')
                    <span class="form-error"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>
                @enderror
            </div>

            <!-- Email -->
            <div class="form-group">
                <label class="form-label">Adresse email</label>
                <input type="email" name="email"
                       class="form-control @error('email') is-error @enderror"
                       placeholder="exemple@esig.tg"
                       value="{{ old('email') }}" required>
                @error('email')
                    <span class="form-error"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>
                @enderror
            </div>

            <!-- Filière -->
            <div class="form-group">
                <label class="form-label">Filière</label>
                <select name="filiere" class="form-control">
                    <option value="">-- Sélectionner --</option>
                    <option value="Génie Logiciel" {{ old('filiere') == 'Génie Logiciel' ? 'selected' : '' }}>Génie Logiciel</option>
                    <option value="Réseaux & Télécoms" {{ old('filiere') == 'Réseaux & Télécoms' ? 'selected' : '' }}>Réseaux & Télécoms</option>
                    <option value="Systèmes Embarqués" {{ old('filiere') == 'Systèmes Embarqués' ? 'selected' : '' }}>Systèmes Embarqués</option>
                    <option value="Autre" {{ old('filiere') == 'Autre' ? 'selected' : '' }}>Autre</option>
                </select>
            </div>

            <!-- Numéro étudiant -->
            <div class="form-group">
                <label class="form-label">Numéro étudiant <span style="color:var(--text-light);font-weight:400">(optionnel)</span></label>
                <input type="text" name="numero_etudiant"
                       class="form-control"
                       placeholder="Ex: ESIG2024001"
                       value="{{ old('numero_etudiant') }}">
            </div>

            <!-- Mot de passe -->
            <div class="form-group">
                <label class="form-label">Mot de passe</label>
                <input type="password" name="password"
                       class="form-control @error('password') is-error @enderror"
                       placeholder="Minimum 8 caractères" required>
                @error('password')
                    <span class="form-error"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>
                @enderror
            </div>

            <!-- Confirmation -->
            <div class="form-group">
                <label class="form-label">Confirmer le mot de passe</label>
                <input type="password" name="password_confirmation"
                       class="form-control"
                       placeholder="Répète ton mot de passe" required>
            </div>

            <button type="submit" class="btn btn-primary btn-full" style="margin-top:8px;">
                <i class="fas fa-user-plus"></i>
                Créer mon compte
            </button>

        </form>

        <p class="auth-footer-text">
            Déjà inscrit ?
            <a href="{{ route('login') }}">Se connecter</a>
        </p>

    </div>
</div>
@endsection