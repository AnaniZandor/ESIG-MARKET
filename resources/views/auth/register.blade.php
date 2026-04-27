@extends('layouts.app')

@section('title', 'Inscription')

@section('content')
<div class="auth-form-side" style="min-height:calc(100vh - 68px);">
    <div class="auth-form-inner">

        <h2>Créer un compte</h2>
        <p class="auth-subtitle">Rejoins la marketplace de l'ESIG</p>

        <form method="POST" action="{{ route('register') }}" id="registerForm">
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

            <!-- Mot de passe avec validation en temps réel -->
            <div class="form-group" id="password-group">
                <label class="form-label">Mot de passe</label>
                <input type="password" name="password" id="password"
                       class="form-control @error('password') is-error @enderror"
                       placeholder="Minimum 8 caractères" required>
                @error('password')
                    <span class="form-error"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>
                @enderror
                <div id="password-strength" style="font-size:12px; margin-top:5px;"></div>
            </div>

            <!-- Confirmation avec validation en temps réel -->
            <div class="form-group" id="confirm-group">
                <label class="form-label">Confirmer le mot de passe</label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                       class="form-control"
                       placeholder="Répète ton mot de passe" required>
                <div id="confirm-message" style="font-size:12px; margin-top:5px;"></div>
            </div>

            <button type="submit" class="btn btn-primary btn-full" style="margin-top:8px;" id="submitBtn">
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

<script>
    // Éléments DOM
    const password = document.getElementById('password');
    const confirm = document.getElementById('password_confirmation');
    const confirmMessage = document.getElementById('confirm-message');
    const passwordStrength = document.getElementById('password-strength');
    const submitBtn = document.getElementById('submitBtn');
    const form = document.getElementById('registerForm');

    // Fonction de validation du mot de passe (force)
    function checkPasswordStrength() {
        const val = password.value;
        
        if (val.length === 0) {
            passwordStrength.innerHTML = '';
            passwordStrength.className = '';
            return false;
        }
        
        let strength = 0;
        let message = '';
        let className = '';
        
        // Critères
        if (val.length >= 8) strength++;
        if (val.length >= 12) strength++;
        if (/[A-Z]/.test(val)) strength++;
        if (/[0-9]/.test(val)) strength++;
        if (/[^A-Za-z0-9]/.test(val)) strength++;
        
        // Déterminer le niveau
        if (strength <= 2) {
            message = '⚠️ Mot de passe faible';
            className = 'text-warning';
        } else if (strength <= 4) {
            message = '👍 Mot de passe moyen';
            className = 'text-info';
        } else {
            message = '✅ Mot de passe fort';
            className = 'text-success';
        }
        
        if (val.length < 8) {
            message = '❌ Minimum 8 caractères requis';
            className = 'text-danger';
        }
        
        passwordStrength.innerHTML = message;
        passwordStrength.className = className;
        return val.length >= 8;
    }
    
    // Fonction de correspondance des mots de passe (temps réel)
    function checkPasswordMatch() {
        const passVal = password.value;
        const confirmVal = confirm.value;
        
        // Réinitialiser les styles
        confirm.classList.remove('is-valid', 'is-invalid');
        
        if (confirmVal === '') {
            confirmMessage.innerHTML = '';
            confirmMessage.className = '';
            return false;
        }
        
        if (passVal === confirmVal) {
            confirmMessage.innerHTML = '✓ Mots de passe identiques';
            confirmMessage.className = 'text-success';
            confirm.classList.add('is-valid');
            return true;
        } else {
            confirmMessage.innerHTML = '✗ Les mots de passe ne correspondent pas';
            confirmMessage.className = 'text-danger';
            confirm.classList.add('is-invalid');
            return false;
        }
    }
    
    // Fonction de validation complète avant soumission
    function validateForm() {
        const passwordOk = password.value.length >= 8;
        const matchOk = password.value === confirm.value && confirm.value !== '';
        
        if (!passwordOk || !matchOk) {
            return false;
        }
        return true;
    }
    
    // Mettre à jour le bouton
    function updateButton() {
        if (validateForm()) {
            submitBtn.disabled = false;
            submitBtn.style.opacity = '1';
        } else {
            submitBtn.disabled = true;
            submitBtn.style.opacity = '0.6';
        }
    }
    
    // Événements en temps réel
    password.addEventListener('keyup', function() {
        checkPasswordStrength();
        checkPasswordMatch();
        updateButton();
    });
    
    confirm.addEventListener('keyup', function() {
        checkPasswordMatch();
        updateButton();
    });
    
    confirm.addEventListener('blur', function() {
        checkPasswordMatch();
        updateButton();
    });
    
    // Blocage de la soumission si les mots de passe ne correspondent pas
    form.addEventListener('submit', function(e) {
        if (!validateForm()) {
            e.preventDefault();
            
            // Afficher un message d'erreur clair
            let errorMsg = '';
            if (password.value.length < 8) {
                errorMsg = 'Le mot de passe doit contenir au moins 8 caractères.';
            } else if (password.value !== confirm.value) {
                errorMsg = 'Les mots de passe ne correspondent pas.';
            }
            
            if (errorMsg) {
                // Afficher une alerte ou un message dans le formulaire
                if (!document.getElementById('form-error-msg')) {
                    const errorDiv = document.createElement('div');
                    errorDiv.id = 'form-error-msg';
                    errorDiv.className = 'alert alert-danger';
                    errorDiv.style.marginBottom = '15px';
                    errorDiv.style.padding = '10px';
                    errorDiv.style.borderRadius = '8px';
                    errorDiv.style.backgroundColor = '#fee2e2';
                    errorDiv.style.color = '#dc2626';
                    errorDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> ' + errorMsg;
                    form.insertBefore(errorDiv, form.firstChild);
                } else {
                    const errorDiv = document.getElementById('form-error-msg');
                    errorDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> ' + errorMsg;
                }
            }
        }
    });
    
    // Initialisation
    checkPasswordStrength();
    checkPasswordMatch();
    updateButton();
</script>

<style>
    /* Styles pour la validation */
    .is-valid {
        border-color: #10b981 !important;
        border-width: 2px !important;
    }
    .is-invalid {
        border-color: #dc2626 !important;
        border-width: 2px !important;
    }
    .text-success {
        color: #10b981 !important;
    }
    .text-danger {
        color: #dc2626 !important;
    }
    .text-warning {
        color: #f59e0b !important;
    }
    .text-info {
        color: #3b82f6 !important;
    }
    .alert {
        animation: shake 0.3s ease-in-out;
    }
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }
</style>
@endsection