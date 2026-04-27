@extends('layouts.app')

@section('title', 'Modifier ' . $user->name)

@section('content')
<div class="edit-user-container">
    
    {{-- Header --}}
    <div class="page-header">
        <div class="header-content">
            <div class="title-section">
                <a href="{{ route('admin.users.show', $user) }}" class="back-link">
                    <i class="fas fa-arrow-left"></i>
                    <span>Retour au profil</span>
                </a>
                <h1 class="page-title">
                    <span class="icon-badge">
                        <i class="fas fa-user-edit"></i>
                    </span>
                    Modifier l'utilisateur
                </h1>
                <p class="page-subtitle">{{ $user->name }} • #{{ $user->id }}</p>
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

    {{-- Edit Form --}}
    <form action="{{ route('admin.users.update', $user) }}" method="POST" class="edit-form">
        @csrf
        @method('PUT')

        <div class="form-grid">
            
            {{-- Colonne gauche : Informations principales --}}
            <div class="form-column">
                
                {{-- Informations de base --}}
                <div class="form-card">
                    <div class="card-header">
                        <h3>
                            <i class="fas fa-user"></i>
                            Informations personnelles
                        </h3>
                    </div>
                    <div class="card-body">
                        
                        {{-- Avatar actuel --}}
                        <div class="avatar-section">
                            <div class="current-avatar">
                                @if($user->avatar)
                                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}">
                                @else
                                    <div class="avatar-placeholder">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <div class="avatar-info">
                                <p class="avatar-text">L'avatar peut être modifié depuis le profil public</p>
                            </div>
                        </div>

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
                                   value="{{ old('name', $user->name) }}" 
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
                                   value="{{ old('email', $user->email) }}" 
                                   placeholder="exemple@email.com"
                                   required>
                            @error('email')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                            @if(!$user->email_verified_at)
                                <span class="hint-text">
                                    <i class="fas fa-info-circle"></i>
                                    Email non vérifié
                                </span>
                            @endif
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
                                <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>
                                    👤 Utilisateur standard
                                </option>
                                <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>
                                    👑 Administrateur
                                </option>
                            </select>
                            @error('role')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                            <span class="hint-text">
                                <i class="fas fa-info-circle"></i>
                                Les administrateurs ont accès à toutes les fonctionnalités
                            </span>
                        </div>

                        {{-- Statut du compte --}}
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-toggle-on"></i>
                                Statut du compte
                            </label>
                            <div class="toggle-wrapper">
                                <label class="toggle-switch">
                                    <input type="checkbox" 
                                           name="is_active" 
                                           value="1"
                                           {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                                    <span class="toggle-slider"></span>
                                </label>
                                <span class="toggle-label">
                                    {{ old('is_active', $user->is_active) ? 'Compte actif' : 'Compte inactif' }}
                                </span>
                            </div>
                            <span class="hint-text">
                                <i class="fas fa-info-circle"></i>
                                Les comptes inactifs ne peuvent pas se connecter
                            </span>
                        </div>

                    </div>
                </div>

            </div>

            {{-- Colonne droite : Informations complémentaires et sécurité --}}
            <div class="form-column">
                
                {{-- Changement de mot de passe --}}
                <div class="form-card">
                    <div class="card-header">
                        <h3>
                            <i class="fas fa-lock"></i>
                            Sécurité
                        </h3>
                    </div>
                    <div class="card-body">
                        
                        <div class="form-group">
                            <label for="password" class="form-label">
                                <i class="fas fa-key"></i>
                                Nouveau mot de passe
                            </label>
                            <input type="password" 
                                   class="form-input @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password" 
                                   placeholder="Laisser vide pour ne pas changer">
                            @error('password')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation" class="form-label">
                                <i class="fas fa-check-double"></i>
                                Confirmer le mot de passe
                            </label>
                            <input type="password" 
                                   class="form-input" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   placeholder="Confirmer le nouveau mot de passe">
                        </div>

                        <div class="password-strength" style="display: none;">
                            <div class="strength-bar">
                                <div class="strength-fill"></div>
                            </div>
                            <span class="strength-text"></span>
                        </div>

                    </div>
                </div>

                {{-- Informations académiques --}}
                <div class="form-card">
                    <div class="card-header">
                        <h3>
                            <i class="fas fa-graduation-cap"></i>
                            Informations académiques
                        </h3>
                    </div>
                    <div class="card-body">
                        
                        <div class="form-group">
                            <label for="filiere" class="form-label">
                                <i class="fas fa-book"></i>
                                Filière
                            </label>
                            <input type="text" 
                                   class="form-input @error('filiere') is-invalid @enderror" 
                                   id="filiere" 
                                   name="filiere" 
                                   value="{{ old('filiere', $user->filiere) }}" 
                                   placeholder="Ex: Informatique, Gestion...">
                            @error('filiere')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="numero_etudiant" class="form-label">
                                <i class="fas fa-id-card"></i>
                                Numéro étudiant
                            </label>
                            <input type="text" 
                                   class="form-input @error('numero_etudiant') is-invalid @enderror" 
                                   id="numero_etudiant" 
                                   name="numero_etudiant" 
                                   value="{{ old('numero_etudiant', $user->numero_etudiant) }}" 
                                   placeholder="Ex: ESI2024001">
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
                            </label>
                            <textarea class="form-textarea @error('bio') is-invalid @enderror" 
                                      id="bio" 
                                      name="bio" 
                                      rows="4"
                                      placeholder="Une courte description de l'utilisateur...">{{ old('bio', $user->bio) }}</textarea>
                            @error('bio')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                            <span class="char-counter">
                                <span id="char-count">0</span> / 500 caractères
                            </span>
                        </div>

                    </div>
                </div>

            </div>
        </div>

        {{-- Actions --}}
        <div class="form-actions">
            <a href="{{ route('admin.users.show', $user) }}" class="btn-secondary">
                <i class="fas fa-times"></i>
                Annuler
            </a>
            <button type="submit" class="btn-primary">
                <i class="fas fa-save"></i>
                Enregistrer les modifications
            </button>
        </div>

    </form>

    {{-- Danger Zone --}}
    @if($user->id !== auth()->id())
        <div class="danger-zone">
            <div class="danger-header">
                <i class="fas fa-exclamation-triangle"></i>
                <h4>Zone dangereuse</h4>
            </div>
            <p>La suppression d'un utilisateur est irréversible. Toutes ses données seront définitivement perdues.</p>
            <form action="{{ route('admin.users.destroy', $user) }}" 
                  method="POST" 
                  onsubmit="return confirmDelete()">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger">
                    <i class="fas fa-trash-alt"></i>
                    Supprimer définitivement cet utilisateur
                </button>
            </form>
        </div>
    @endif

</div>

{{-- Scripts --}}
<script>
function confirmDelete() {
    return confirm('⚠️ ATTENTION !\n\nVous êtes sur le point de supprimer définitivement cet utilisateur.\n\nCette action est IRRÉVERSIBLE et supprimera toutes les données associées (articles, images, etc.).\n\nÊtes-vous absolument sûr de vouloir continuer ?');
}

// Compteur de caractères pour la bio
document.addEventListener('DOMContentLoaded', function() {
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
    
    // Toggle label pour le statut
    const toggle = document.querySelector('input[name="is_active"]');
    const toggleLabel = document.querySelector('.toggle-label');
    
    if (toggle && toggleLabel) {
        toggle.addEventListener('change', function() {
            toggleLabel.textContent = this.checked ? 'Compte actif' : 'Compte inactif';
        });
    }
});
</script>

{{-- Styles --}}
<style>
/* Container */
.edit-user-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 24px 32px;
}

/* Header */
.page-header {
    margin-bottom: 28px;
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

.title-section {
    flex: 1;
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

/* Avatar Section */
.avatar-section {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 24px;
    padding-bottom: 20px;
    border-bottom: 1px solid #e5e7eb;
}

.current-avatar {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    overflow: hidden;
    flex-shrink: 0;
}

.current-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.avatar-placeholder {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    font-weight: 600;
}

.avatar-info {
    flex: 1;
}

.avatar-text {
    margin: 0;
    font-size: 14px;
    color: #6b7280;
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

/* Toggle Switch */
.toggle-wrapper {
    display: flex;
    align-items: center;
    gap: 12px;
}

.toggle-switch {
    position: relative;
    display: inline-block;
    width: 48px;
    height: 24px;
}

.toggle-switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.toggle-slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #e5e7eb;
    transition: 0.3s;
    border-radius: 24px;
}

.toggle-slider:before {
    position: absolute;
    content: "";
    height: 18px;
    width: 18px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: 0.3s;
    border-radius: 50%;
}

input:checked + .toggle-slider {
    background-color: #10b981;
}

input:checked + .toggle-slider:before {
    transform: translateX(24px);
}

.toggle-label {
    font-size: 14px;
    color: #374151;
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

/* Danger Zone */
.danger-zone {
    background: #fef2f2;
    border: 1px solid #fca5a5;
    border-radius: 12px;
    padding: 20px;
    margin-top: 32px;
}

.danger-header {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 12px;
}

.danger-header i {
    color: #dc2626;
    font-size: 20px;
}

.danger-header h4 {
    margin: 0;
    color: #dc2626;
    font-size: 16px;
    font-weight: 600;
}

.danger-zone p {
    color: #991b1b;
    font-size: 14px;
    margin: 0 0 16px 0;
}

.btn-danger {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 18px;
    background: #dc2626;
    border: none;
    border-radius: 8px;
    color: white;
    font-weight: 500;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-danger:hover {
    background: #b91c1c;
}

/* Responsive */
@media (max-width: 768px) {
    .edit-user-container {
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
    
    .avatar-section {
        flex-direction: column;
        text-align: center;
    }
}
</style>
@endsection