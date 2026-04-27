@extends('layouts.app')

@section('title', 'Créer un utilisateur')

@section('content')
<div class="create-user-container">
    
    {{-- Header --}}
    <div class="page-header">
        <div class="header-content">
            <div class="title-section">
                <a href="{{ route('admin.users.index') }}" class="back-link">
                    <i class="fas fa-arrow-left"></i>
                    <span>Retour à la liste</span>
                </a>
                <h1 class="page-title">
                    <span class="icon-badge">
                        <i class="fas fa-user-plus"></i>
                    </span>
                    Nouvel utilisateur
                </h1>
                <p class="page-subtitle">Créez un compte utilisateur manuellement</p>
            </div>
        </div>
    </div>

    {{-- Alert Messages --}}
    @if($errors->any())
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            <div class="alert-content">
                <strong>Erreurs de validation</strong>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <button type="button" class="alert-close" onclick="this.parentElement.remove()">×</button>
        </div>
    @endif

    {{-- Create Form --}}
    <form action="{{ route('admin.users.store') }}" method="POST" class="create-form">
        @csrf

        <div class="form-grid">
            
            {{-- Colonne gauche --}}
            <div class="form-column">
                
                {{-- Informations principales --}}
                <div class="form-card">
                    <div class="card-header">
                        <h3>
                            <i class="fas fa-user-circle"></i>
                            Informations personnelles
                        </h3>
                    </div>
                    <div class="card-body">
                        
                        {{-- Nom complet --}}
                        <div class="form-group">
                            <label for="name" class="form-label">
                                <i class="fas fa-user"></i>
                                Nom complet
                                <span class="required">*</span>
                            </label>
                            <input type="text" 
                                   class="form-input @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}" 
                                   placeholder="Ex: Jean Dupont"
                                   required>
                            @error('name')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="form-group">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope"></i>
                                Adresse email
                                <span class="required">*</span>
                            </label>
                            <input type="email" 
                                   class="form-input @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   placeholder="exemple@esig.tg"
                                   required>
                            @error('email')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                            <span class="hint-text">
                                <i class="fas fa-info-circle"></i>
                                L'utilisateur pourra se connecter avec cet email
                            </span>
                        </div>

                        {{-- Rôle --}}
                        <div class="form-group">
                            <label for="role" class="form-label">
                                <i class="fas fa-shield-alt"></i>
                                Rôle utilisateur
                                <span class="required">*</span>
                            </label>
                            <select class="form-select @error('role') is-invalid @enderror" 
                                    id="role" 
                                    name="role" 
                                    required>
                                <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>
                                    👤 Utilisateur standard
                                </option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>
                                    👑 Administrateur
                                </option>
                            </select>
                            @error('role')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                            <span class="hint-text">
                                <i class="fas fa-info-circle"></i>
                                Les administrateurs ont accès au panneau d'administration complet
                            </span>
                        </div>

                    </div>
                </div>

                {{-- Sécurité --}}
                <div class="form-card">
                    <div class="card-header">
                        <h3>
                            <i class="fas fa-lock"></i>
                            Sécurité
                        </h3>
                    </div>
                    <div class="card-body">
                        
                        {{-- Mot de passe --}}
                        <div class="form-group">
                            <label for="password" class="form-label">
                                <i class="fas fa-key"></i>
                                Mot de passe
                                <span class="required">*</span>
                            </label>
                            <div class="password-wrapper">
                                <input type="password" 
                                       class="form-input @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password" 
                                       placeholder="Minimum 8 caractères"
                                       required>
                                <button type="button" class="toggle-password" onclick="togglePassword('password')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Confirmation --}}
                        <div class="form-group">
                            <label for="password_confirmation" class="form-label">
                                <i class="fas fa-check-double"></i>
                                Confirmer le mot de passe
                                <span class="required">*</span>
                            </label>
                            <div class="password-wrapper">
                                <input type="password" 
                                       class="form-input" 
                                       id="password_confirmation" 
                                       name="password_confirmation" 
                                       placeholder="Répéter le mot de passe"
                                       required>
                                <button type="button" class="toggle-password" onclick="togglePassword('password_confirmation')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        {{-- Force du mot de passe --}}
                        <div class="password-strength" id="password-strength" style="display: none;">
                            <div class="strength-bar">
                                <div class="strength-fill" id="strength-fill"></div>
                            </div>
                            <span class="strength-text" id="strength-text"></span>
                        </div>

                    </div>
                </div>

            </div>

            {{-- Colonne droite --}}
            <div class="form-column">
                
                {{-- Informations académiques --}}
                <div class="form-card">
                    <div class="card-header">
                        <h3>
                            <i class="fas fa-graduation-cap"></i>
                            Informations académiques
                        </h3>
                    </div>
                    <div class="card-body">
                        
                        {{-- Filière --}}
                        <div class="form-group">
                            <label for="filiere" class="form-label">
                                <i class="fas fa-book"></i>
                                Filière
                            </label>
                            <select name="filiere" id="filiere" class="form-select">
                                <option value="">-- Sélectionner une filière --</option>
                                <option value="Génie Logiciel" {{ old('filiere') == 'Génie Logiciel' ? 'selected' : '' }}>
                                    💻 Génie Logiciel
                                </option>
                                <option value="Réseaux & Télécoms" {{ old('filiere') == 'Réseaux & Télécoms' ? 'selected' : '' }}>
                                    🌐 Réseaux & Télécoms
                                </option>
                                <option value="Systèmes Embarqués" {{ old('filiere') == 'Systèmes Embarqués' ? 'selected' : '' }}>
                                    🔧 Systèmes Embarqués
                                </option>
                                <option value="Data Science & IA" {{ old('filiere') == 'Data Science & IA' ? 'selected' : '' }}>
                                    📊 Data Science & IA
                                </option>
                                <option value="Cybersécurité" {{ old('filiere') == 'Cybersécurité' ? 'selected' : '' }}>
                                    🔒 Cybersécurité
                                </option>
                                <option value="Autre" {{ old('filiere') == 'Autre' ? 'selected' : '' }}>
                                    📚 Autre
                                </option>
                            </select>
                        </div>

                        {{-- Numéro étudiant --}}
                        <div class="form-group">
                            <label for="numero_etudiant" class="form-label">
                                <i class="fas fa-id-card"></i>
                                Numéro étudiant
                                <span class="optional">(optionnel)</span>
                            </label>
                            <input type="text" 
                                   class="form-input @error('numero_etudiant') is-invalid @enderror" 
                                   id="numero_etudiant" 
                                   name="numero_etudiant" 
                                   value="{{ old('numero_etudiant') }}" 
                                   placeholder="Ex: ESIG2024001">
                            @error('numero_etudiant')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>
                </div>

                {{-- Biographie --}}
                <div class="form-card">
                    <div class="card-header">
                        <h3>
                            <i class="fas fa-pen"></i>
                            Biographie
                        </h3>
                    </div>
                    <div class="card-body">
                        
                        <div class="form-group">
                            <label for="bio" class="form-label">
                                <i class="fas fa-quote-right"></i>
                                À propos
                                <span class="optional">(optionnel)</span>
                            </label>
                            <textarea class="form-textarea @error('bio') is-invalid @enderror" 
                                      id="bio" 
                                      name="bio" 
                                      rows="5"
                                      placeholder="Une courte description de l'utilisateur...">{{ old('bio') }}</textarea>
                            @error('bio')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                            <span class="char-counter">
                                <span id="char-count">0</span> / 500 caractères
                            </span>
                        </div>

                    </div>
                </div>

                {{-- Options supplémentaires --}}
                <div class="form-card">
                    <div class="card-header">
                        <h3>
                            <i class="fas fa-cog"></i>
                            Options
                        </h3>
                    </div>
                    <div class="card-body">
                        
                        {{-- Envoyer email de bienvenue --}}
                        <div class="form-group">
                            <label class="checkbox-wrapper">
                                <input type="checkbox" name="send_welcome_email" value="1" checked>
                                <span class="checkmark"></span>
                                <span class="checkbox-label">
                                    <i class="fas fa-envelope"></i>
                                    Envoyer un email de bienvenue
                                </span>
                            </label>
                            <span class="hint-text" style="margin-left: 28px;">
                                L'utilisateur recevra ses identifiants par email
                            </span>
                        </div>

                        {{-- Forcer changement de mot de passe --}}
                        <div class="form-group">
                            <label class="checkbox-wrapper">
                                <input type="checkbox" name="force_password_change" value="1">
                                <span class="checkmark"></span>
                                <span class="checkbox-label">
                                    <i class="fas fa-sync-alt"></i>
                                    Forcer le changement de mot de passe à la première connexion
                                </span>
                            </label>
                        </div>

                    </div>
                </div>

            </div>
        </div>

        {{-- Actions --}}
        <div class="form-actions">
            <a href="{{ route('admin.users.index') }}" class="btn-secondary">
                <i class="fas fa-times"></i>
                Annuler
            </a>
            <button type="submit" class="btn-primary">
                <i class="fas fa-user-plus"></i>
                Créer l'utilisateur
            </button>
        </div>

    </form>
</div>

{{-- Scripts --}}
<script>
// Toggle password visibility
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const button = field.nextElementSibling.querySelector('i');
    
    if (field.type === 'password') {
        field.type = 'text';
        button.classList.remove('fa-eye');
        button.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        button.classList.remove('fa-eye-slash');
        button.classList.add('fa-eye');
    }
}

// Password strength checker
document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('password');
    const strengthDiv = document.getElementById('password-strength');
    const strengthFill = document.getElementById('strength-fill');
    const strengthText = document.getElementById('strength-text');
    
    if (passwordInput) {
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            
            if (password.length > 0) {
                strengthDiv.style.display = 'block';
                
                let strength = 0;
                if (password.length >= 8) strength++;
                if (password.match(/[a-z]+/)) strength++;
                if (password.match(/[A-Z]+/)) strength++;
                if (password.match(/[0-9]+/)) strength++;
                if (password.match(/[$@#&!]+/)) strength++;
                
                let width = (strength / 5) * 100;
                let color, text;
                
                if (strength <= 2) {
                    color = '#dc2626';
                    text = 'Faible';
                } else if (strength <= 3) {
                    color = '#f59e0b';
                    text = 'Moyen';
                } else if (strength <= 4) {
                    color = '#10b981';
                    text = 'Bon';
                } else {
                    color = '#059669';
                    text = 'Excellent';
                }
                
                strengthFill.style.width = width + '%';
                strengthFill.style.background = color;
                strengthText.textContent = text;
                strengthText.style.color = color;
            } else {
                strengthDiv.style.display = 'none';
            }
        });
    }
    
    // Character counter for bio
    const textarea = document.getElementById('bio');
    const counter = document.getElementById('char-count');
    
    if (textarea && counter) {
        const updateCounter = () => {
            const length = textarea.value.length;
            counter.textContent = length;
            counter.style.color = length > 500 ? '#dc2626' : '#6b7280';
        };
        
        updateCounter();
        textarea.addEventListener('input', updateCounter);
    }
});
</script>

{{-- Styles --}}
<style>
/* Container */
.create-user-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 24px 32px;
}

/* Header */
.page-header {
    margin-bottom: 28px;
}

.back-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #6b7280;
    text-decoration: none;
    font-size: 14px;
    margin-bottom: 12px;
    transition: color 0.2s;
}

.back-link:hover {
    color: #4f46e5;
}

.page-title {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 26px;
    font-weight: 600;
    color: #111827;
    margin: 0 0 6px 0;
}

.icon-badge {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 10px;
    color: white;
    font-size: 18px;
}

.page-subtitle {
    color: #6b7280;
    font-size: 15px;
    margin: 0 0 0 52px;
}

/* Alert */
.alert {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 14px 18px;
    border-radius: 10px;
    margin-bottom: 24px;
}

.alert-error {
    background: #fef2f2;
    border: 1px solid #fca5a5;
    color: #991b1b;
}

.alert-content {
    flex: 1;
}

.alert-content ul {
    margin: 8px 0 0 20px;
    padding: 0;
}

.alert-close {
    background: none;
    border: none;
    font-size: 22px;
    cursor: pointer;
    opacity: 0.6;
    color: inherit;
    padding: 0 4px;
}

/* Form Grid */
.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
    margin-bottom: 32px;
}

/* Form Cards */
.form-card {
    background: white;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    overflow: hidden;
    margin-bottom: 24px;
}

.form-card:last-child {
    margin-bottom: 0;
}

.card-header {
    padding: 18px 20px;
    border-bottom: 1px solid #e5e7eb;
    background: #fafafa;
}

.card-header h3 {
    display: flex;
    align-items: center;
    gap: 8px;
    margin: 0;
    font-size: 16px;
    font-weight: 600;
    color: #374151;
}

.card-header h3 i {
    color: #4f46e5;
    width: 20px;
}

.card-body {
    padding: 20px;
}

/* Form Elements */
.form-group {
    margin-bottom: 20px;
}

.form-group:last-child {
    margin-bottom: 0;
}

.form-label {
    display: flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 8px;
    font-size: 14px;
    font-weight: 500;
    color: #374151;
}

.form-label i {
    color: #4f46e5;
    width: 18px;
}

.required {
    color: #dc2626;
    margin-left: 4px;
}

.optional {
    color: #9ca3af;
    font-weight: 400;
    font-size: 12px;
    margin-left: 4px;
}

.form-input,
.form-select,
.form-textarea {
    width: 100%;
    padding: 10px 14px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    font-size: 14px;
    transition: all 0.2s;
    background: white;
}

.form-input:focus,
.form-select:focus,
.form-textarea:focus {
    outline: none;
    border-color: #4f46e5;
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
}

.form-input.is-invalid,
.form-select.is-invalid,
.form-textarea.is-invalid {
    border-color: #dc2626;
}

.form-textarea {
    resize: vertical;
    min-height: 100px;
}

/* Password Wrapper */
.password-wrapper {
    position: relative;
}

.toggle-password {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #9ca3af;
    cursor: pointer;
    padding: 4px;
    font-size: 16px;
}

.toggle-password:hover {
    color: #6b7280;
}

/* Password Strength */
.password-strength {
    margin-top: 12px;
    padding: 12px;
    background: #f9fafb;
    border-radius: 8px;
}

.strength-bar {
    height: 6px;
    background: #e5e7eb;
    border-radius: 3px;
    overflow: hidden;
    margin-bottom: 8px;
}

.strength-fill {
    height: 100%;
    width: 0%;
    transition: all 0.3s;
    border-radius: 3px;
}

.strength-text {
    font-size: 13px;
    font-weight: 500;
}

/* Error Message */
.error-message {
    display: block;
    margin-top: 6px;
    font-size: 13px;
    color: #dc2626;
}

.hint-text {
    display: flex;
    align-items: center;
    gap: 6px;
    margin-top: 6px;
    font-size: 12px;
    color: #6b7280;
}

.char-counter {
    display: block;
    margin-top: 6px;
    font-size: 12px;
    color: #6b7280;
    text-align: right;
}

/* Checkbox */
.checkbox-wrapper {
    display: flex;
    align-items: flex-start;
    cursor: pointer;
    position: relative;
    padding-left: 28px;
}

.checkbox-wrapper input {
    position: absolute;
    opacity: 0;
    cursor: pointer;
}

.checkmark {
    position: absolute;
    left: 0;
    top: 2px;
    width: 20px;
    height: 20px;
    background: white;
    border: 2px solid #e5e7eb;
    border-radius: 4px;
    transition: all 0.2s;
}

.checkbox-wrapper:hover .checkmark {
    border-color: #4f46e5;
}

.checkbox-wrapper input:checked ~ .checkmark {
    background: #4f46e5;
    border-color: #4f46e5;
}

.checkmark:after {
    content: "";
    position: absolute;
    display: none;
    left: 6px;
    top: 2px;
    width: 5px;
    height: 10px;
    border: solid white;
    border-width: 0 2px 2px 0;
    transform: rotate(45deg);
}

.checkbox-wrapper input:checked ~ .checkmark:after {
    display: block;
}

.checkbox-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    color: #374151;
}

.checkbox-label i {
    color: #4f46e5;
    width: 18px;
}

/* Form Actions */
.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    padding-top: 8px;
    border-top: 1px solid #e5e7eb;
}

.btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    background: #4f46e5;
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: 500;
    font-size: 15px;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-primary:hover {
    background: #4338ca;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
}

.btn-secondary {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    color: #374151;
    font-weight: 500;
    font-size: 15px;
    text-decoration: none;
    transition: all 0.2s;
}

.btn-secondary:hover {
    background: #f9fafb;
    border-color: #d1d5db;
}

/* Responsive */
@media (max-width: 768px) {
    .create-user-container {
        padding: 16px;
    }
    
    .form-grid {
        grid-template-columns: 1fr;
        gap: 0;
    }
    
    .form-actions {
        flex-direction: column-reverse;
    }
    
    .btn-primary,
    .btn-secondary {
        width: 100%;
        justify-content: center;
    }
}
</style>
@endsection