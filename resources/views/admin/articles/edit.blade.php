@extends('layouts.app')

@section('title', 'Modifier ' . $article->title)

@section('content')
<div class="admin-articles-edit-container">
    
    {{-- Header --}}
    <div class="page-header">
        <div class="header-content">
            <div class="title-section">
                <a href="{{ route('admin.articles.show', $article) }}" class="back-link">
                    <i class="fas fa-arrow-left"></i>
                    <span>Retour à l'article</span>
                </a>
                <h1 class="page-title">
                    <span class="icon-badge">
                        <i class="fas fa-pen-to-square"></i>
                    </span>
                    Modifier l'article
                </h1>
                <p class="page-subtitle">#{{ $article->id }} • {{ $article->title }}</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('articles.show', $article) }}" target="_blank" class="btn-secondary">
                    <i class="fas fa-eye"></i>
                    <span>Voir public</span>
                </a>
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
    <form action="{{ route('admin.articles.update', $article) }}" method="POST" class="edit-form">
        @csrf
        @method('PUT')

        <div class="form-grid">
            
            {{-- Colonne gauche : Informations principales --}}
            <div class="form-column">
                
                <div class="form-card">
                    <div class="card-header">
                        <h3>
                            <i class="fas fa-info-circle"></i>
                            Informations principales
                        </h3>
                    </div>
                    <div class="card-body">
                        
                        {{-- Titre --}}
                        <div class="form-group">
                            <label for="title" class="form-label">
                                <i class="fas fa-heading"></i>
                                Titre de l'annonce
                                <span class="required">*</span>
                            </label>
                            <input type="text" 
                                   class="form-input @error('title') is-invalid @enderror" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title', $article->title) }}" 
                                   placeholder="Ex: iPhone 12 en excellent état"
                                   required>
                            @error('title')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Slug (lecture seule) --}}
                        <div class="form-group">
                            <label for="slug" class="form-label">
                                <i class="fas fa-link"></i>
                                Slug
                            </label>
                            <input type="text" 
                                   class="form-input" 
                                   id="slug" 
                                   value="{{ $article->slug }}" 
                                   readonly
                                   style="background: #f9fafb; cursor: not-allowed;">
                            <span class="hint-text">
                                <i class="fas fa-info-circle"></i>
                                Le slug est généré automatiquement et ne peut pas être modifié
                            </span>
                        </div>

                        {{-- Description --}}
                        <div class="form-group">
                            <label for="description" class="form-label">
                                <i class="fas fa-align-left"></i>
                                Description
                                <span class="required">*</span>
                            </label>
                            <textarea class="form-textarea @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="8"
                                      placeholder="Décrivez l'article en détail..."
                                      required>{{ old('description', $article->description) }}</textarea>
                            @error('description')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                            <span class="char-counter">
                                <span id="char-count">0</span> / 5000 caractères
                            </span>
                        </div>

                    </div>
                </div>

                {{-- Prix et localisation --}}
                <div class="form-card">
                    <div class="card-header">
                        <h3>
                            <i class="fas fa-money-bill"></i>
                            Prix et localisation
                        </h3>
                    </div>
                    <div class="card-body">
                        
                        {{-- Prix --}}
                        <div class="form-group">
                            <label for="price" class="form-label">
                                <i class="fas fa-tag"></i>
                                Prix (FCFA)
                                <span class="required">*</span>
                            </label>
                            <div class="price-input-wrapper">
                                <input type="number" 
                                       class="form-input @error('price') is-invalid @enderror" 
                                       id="price" 
                                       name="price" 
                                       value="{{ old('price', $article->price) }}" 
                                       placeholder="Ex: 15000"
                                       min="0"
                                       step="1"
                                       required>
                                <span class="price-suffix">FCFA</span>
                            </div>
                            @error('price')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                            <span class="price-format" id="price-format"></span>
                        </div>

                        {{-- Localisation --}}
                        <div class="form-group">
                            <label for="location" class="form-label">
                                <i class="fas fa-location-dot"></i>
                                Lieu de remise
                                <span class="optional">(optionnel)</span>
                            </label>
                            <input type="text" 
                                   class="form-input @error('location') is-invalid @enderror" 
                                   id="location" 
                                   name="location" 
                                   value="{{ old('location', $article->location) }}" 
                                   placeholder="Ex: Campus ESIG, Bâtiment A">
                            @error('location')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>
                </div>

            </div>

            {{-- Colonne droite : Classification et stats --}}
            <div class="form-column">
                
                {{-- Classification --}}
                <div class="form-card">
                    <div class="card-header">
                        <h3>
                            <i class="fas fa-tags"></i>
                            Classification
                        </h3>
                    </div>
                    <div class="card-body">
                        
                        {{-- Catégorie --}}
                        <div class="form-group">
                            <label for="category_id" class="form-label">
                                <i class="fas fa-folder"></i>
                                Catégorie
                                <span class="required">*</span>
                            </label>
                            <select class="form-select @error('category_id') is-invalid @enderror" 
                                    id="category_id" 
                                    name="category_id" 
                                    required>
                                <option value="">-- Sélectionner une catégorie --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" 
                                        {{ old('category_id', $article->category_id) == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->icon }} {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- État --}}
                        <div class="form-group">
                            <label for="condition" class="form-label">
                                <i class="fas fa-star"></i>
                                État de l'article
                                <span class="required">*</span>
                            </label>
                            <div class="condition-options">
                                @php
                                    $conditions = [
                                        'neuf' => ['✨', 'Neuf', 'Jamais utilisé, dans son emballage d\'origine'],
                                        'tres_bon' => ['👍', 'Très bon état', 'Comme neuf, aucune trace d\'usure'],
                                        'bon' => ['👌', 'Bon état', 'Légères traces d\'usage normales'],
                                        'acceptable' => ['⚠️', 'Acceptable', 'Usure visible mais fonctionnel']
                                    ];
                                @endphp
                                
                                @foreach($conditions as $value => $data)
                                    <label class="condition-option {{ old('condition', $article->condition) == $value ? 'selected' : '' }}">
                                        <input type="radio" 
                                               name="condition" 
                                               value="{{ $value }}"
                                               {{ old('condition', $article->condition) == $value ? 'checked' : '' }}>
                                        <span class="condition-emoji">{{ $data[0] }}</span>
                                        <span class="condition-text">
                                            <strong>{{ $data[1] }}</strong>
                                            <small>{{ $data[2] }}</small>
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                            @error('condition')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Statut --}}
                        <div class="form-group">
                            <label for="status" class="form-label">
                                <i class="fas fa-circle"></i>
                                Statut de l'annonce
                                <span class="required">*</span>
                            </label>
                            <div class="status-options">
                                @php
                                    $statuses = [
                                        'disponible' => ['✅', 'Disponible', 'L\'article est à vendre', 'success'],
                                        'reserve' => ['⏳', 'Réservé', 'En attente de paiement', 'warning'],
                                        'vendu' => ['🏷️', 'Vendu', 'Transaction terminée', 'danger']
                                    ];
                                @endphp
                                
                                @foreach($statuses as $value => $data)
                                    <label class="status-option {{ old('status', $article->status) == $value ? 'selected' : '' }} {{ $data[3] }}">
                                        <input type="radio" 
                                               name="status" 
                                               value="{{ $value }}"
                                               {{ old('status', $article->status) == $value ? 'checked' : '' }}>
                                        <span class="status-emoji">{{ $data[0] }}</span>
                                        <span class="status-text">
                                            <strong>{{ $data[1] }}</strong>
                                            <small>{{ $data[2] }}</small>
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                            @error('status')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>
                </div>

                {{-- Statistiques --}}
                <div class="form-card">
                    <div class="card-header">
                        <h3>
                            <i class="fas fa-chart-bar"></i>
                            Statistiques
                        </h3>
                    </div>
                    <div class="card-body">
                        
                        <div class="stats-grid-inline">
                            <div class="stat-item-inline">
                                <div class="stat-icon-small">
                                    <i class="fas fa-eye"></i>
                                </div>
                                <div class="stat-content">
                                    <span class="stat-value">{{ number_format($article->views) }}</span>
                                    <span class="stat-label">Vues totales</span>
                                </div>
                            </div>
                            
                            <div class="stat-item-inline">
                                <div class="stat-icon-small">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="stat-content">
                                    <span class="stat-value">{{ $article->user->name ?? 'N/A' }}</span>
                                    <span class="stat-label">Vendeur</span>
                                </div>
                            </div>
                            
                            <div class="stat-item-inline">
                                <div class="stat-icon-small">
                                    <i class="fas fa-calendar"></i>
                                </div>
                                <div class="stat-content">
                                    <span class="stat-value">{{ $article->created_at->format('d/m/Y') }}</span>
                                    <span class="stat-label">Créé le</span>
                                </div>
                            </div>
                            
                            <div class="stat-item-inline">
                                <div class="stat-icon-small">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="stat-content">
                                    <span class="stat-value">{{ $article->updated_at->diffForHumans() }}</span>
                                    <span class="stat-label">Dernière modification</span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Photos --}}
                @if($article->images && $article->images->count() > 0)
                    <div class="form-card">
                        <div class="card-header">
                            <h3>
                                <i class="fas fa-images"></i>
                                Photos ({{ $article->images->count() }})
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="images-grid-small">
                                @foreach($article->images as $image)
                                    <div class="image-thumbnail">
                                        <img src="{{ asset('storage/' . $image->path) }}" alt="Photo article">
                                        @if($image->is_primary)
                                            <span class="primary-indicator">Principale</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                            <p class="hint-text" style="margin-top: 12px;">
                                <i class="fas fa-info-circle"></i>
                                La gestion des photos se fait depuis la page de détail de l'article
                            </p>
                        </div>
                    </div>
                @endif

            </div>
        </div>

        {{-- Actions --}}
        <div class="form-actions">
            <a href="{{ route('admin.articles.show', $article) }}" class="btn-secondary">
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
    <div class="danger-zone">
        <div class="danger-header">
            <i class="fas fa-exclamation-triangle"></i>
            <h4>Zone dangereuse</h4>
        </div>
        <p>La suppression d'un article est irréversible. Toutes les données et photos associées seront définitivement perdues.</p>
        <form action="{{ route('admin.articles.destroy', $article) }}" 
              method="POST" 
              onsubmit="return confirm('⚠️ ATTENTION !\n\nVous allez supprimer définitivement l\'article :\n{{ $article->title }}\n\nCette action est IRRÉVERSIBLE.\n\nContinuer ?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-danger">
                <i class="fas fa-trash-alt"></i>
                Supprimer définitivement cet article
            </button>
        </form>
    </div>

</div>

{{-- Scripts --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Compteur de caractères pour la description
    const textarea = document.getElementById('description');
    const counter = document.getElementById('char-count');
    
    if (textarea && counter) {
        const updateCounter = () => {
            const length = textarea.value.length;
            counter.textContent = length;
            counter.style.color = length > 5000 ? '#dc2626' : '#6b7280';
        };
        
        updateCounter();
        textarea.addEventListener('input', updateCounter);
    }
    
    // Formatage du prix
    const priceInput = document.getElementById('price');
    const priceFormat = document.getElementById('price-format');
    
    if (priceInput && priceFormat) {
        const formatPrice = () => {
            const value = parseInt(priceInput.value);
            if (!isNaN(value) && value > 0) {
                priceFormat.textContent = new Intl.NumberFormat('fr-FR').format(value) + ' FCFA';
            } else {
                priceFormat.textContent = '';
            }
        };
        
        formatPrice();
        priceInput.addEventListener('input', formatPrice);
    }
    
    // Style pour les options de condition sélectionnées
    const conditionRadios = document.querySelectorAll('input[name="condition"]');
    conditionRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            document.querySelectorAll('.condition-option').forEach(opt => {
                opt.classList.remove('selected');
            });
            this.closest('.condition-option').classList.add('selected');
        });
    });
    
    // Style pour les options de statut sélectionnées
    const statusRadios = document.querySelectorAll('input[name="status"]');
    statusRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            document.querySelectorAll('.status-option').forEach(opt => {
                opt.classList.remove('selected');
            });
            this.closest('.status-option').classList.add('selected');
        });
    });
});
</script>

{{-- Styles --}}
<style>
.admin-articles-edit-container {
    max-width: 1400px;
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

.header-actions {
    display: flex;
    gap: 12px;
}

.btn-secondary {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    color: #374151;
    font-weight: 500;
    font-size: 14px;
    text-decoration: none;
    transition: all 0.2s;
}

.btn-secondary:hover {
    background: #f9fafb;
    border-color: #d1d5db;
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

/* Price Input */
.price-input-wrapper {
    position: relative;
    display: flex;
    align-items: center;
}

.price-input-wrapper .form-input {
    padding-right: 70px;
}

.price-suffix {
    position: absolute;
    right: 14px;
    color: #6b7280;
    font-size: 14px;
    pointer-events: none;
}

.price-format {
    display: block;
    margin-top: 6px;
    font-size: 13px;
    color: #059669;
    font-weight: 500;
}

/* Condition Options */
.condition-options {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.condition-option {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 14px;
    border: 2px solid #e5e7eb;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.2s;
}

.condition-option:hover {
    border-color: #d1d5db;
    background: #f9fafb;
}

.condition-option.selected {
    border-color: #4f46e5;
    background: #f5f3ff;
}

.condition-option input {
    display: none;
}

.condition-emoji {
    font-size: 24px;
}

.condition-text {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.condition-text strong {
    font-size: 14px;
    color: #111827;
}

.condition-text small {
    font-size: 12px;
    color: #6b7280;
}

/* Status Options */
.status-options {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.status-option {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 14px;
    border: 2px solid #e5e7eb;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.2s;
}

.status-option:hover {
    border-color: #d1d5db;
    background: #f9fafb;
}

.status-option.selected.success {
    border-color: #10b981;
    background: #f0fdf4;
}

.status-option.selected.warning {
    border-color: #f59e0b;
    background: #fffbeb;
}

.status-option.selected.danger {
    border-color: #ef4444;
    background: #fef2f2;
}

.status-option input {
    display: none;
}

.status-emoji {
    font-size: 24px;
}

.status-text {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.status-text strong {
    font-size: 14px;
    color: #111827;
}

.status-text small {
    font-size: 12px;
    color: #6b7280;
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

/* Stats Grid Inline */
.stats-grid-inline {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
}

.stat-item-inline {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    background: #f9fafb;
    border-radius: 10px;
}

.stat-icon-small {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    background: #e0e7ff;
    color: #4f46e5;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
}

.stat-content {
    display: flex;
    flex-direction: column;
}

.stat-content .stat-value {
    font-size: 18px;
    font-weight: 700;
    color: #111827;
}

.stat-content .stat-label {
    font-size: 11px;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

/* Images Grid */
.images-grid-small {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 10px;
}

.image-thumbnail {
    position: relative;
    aspect-ratio: 1;
    border-radius: 8px;
    overflow: hidden;
    border: 2px solid #e5e7eb;
}

.image-thumbnail img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.primary-indicator {
    position: absolute;
    bottom: 4px;
    left: 4px;
    background: #4f46e5;
    color: white;
    font-size: 9px;
    font-weight: 700;
    padding: 2px 6px;
    border-radius: 10px;
    text-transform: uppercase;
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
@media (max-width: 1024px) {
    .form-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .admin-articles-edit-container {
        padding: 16px;
    }
    
    .header-content {
        flex-direction: column;
        gap: 16px;
    }
    
    .header-actions {
        width: 100%;
    }
    
    .header-actions .btn-secondary {
        flex: 1;
        justify-content: center;
    }
    
    .stats-grid-inline {
        grid-template-columns: 1fr;
    }
    
    .images-grid-small {
        grid-template-columns: repeat(2, 1fr);
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