@extends('layouts.app')

@section('title', $article->title)

@section('content')
<div class="admin-show-container">
    <div class="page-header">
        <a href="{{ route('admin.articles.index') }}" class="back-link">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
        <h1>{{ $article->title }}</h1>
    </div>
    
    <div class="article-detail">
        <p><strong>Prix :</strong> {{ number_format($article->price, 0, ',', ' ') }} FCFA</p>
        <p><strong>Statut :</strong> {{ $article->status }}</p>
        <p><strong>Vues :</strong> {{ $article->views }}</p>
        <p><strong>Description :</strong> {{ $article->description }}</p>
        
        <div class="actions">
            <a href="{{ route('admin.articles.edit', $article) }}" class="btn btn-primary">Modifier</a>
            <form action="{{ route('admin.articles.destroy', $article) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Supprimer ?')">Supprimer</button>
            </form>
        </div>
    </div>
</div>
@endsection@extends('layouts.app')

@section('title', $article->title)

@section('content')
<div class="admin-articles-show-container">
    
    {{-- Header --}}
    <div class="page-header">
        <div class="header-content">
            <div class="title-section">
                <a href="{{ route('admin.articles.index') }}" class="back-link">
                    <i class="fas fa-arrow-left"></i>
                    <span>Retour à la liste</span>
                </a>
                <h1 class="page-title">
                    <span class="icon-badge">
                        <i class="fas fa-newspaper"></i>
                    </span>
                    Détails de l'article
                </h1>
            </div>
            <div class="header-actions">
                <a href="{{ route('articles.show', $article) }}" target="_blank" class="btn-secondary">
                    <i class="fas fa-eye"></i>
                    <span>Voir public</span>
                </a>
                <a href="{{ route('admin.articles.edit', $article) }}" class="btn-primary">
                    <i class="fas fa-pen"></i>
                    <span>Modifier</span>
                </a>
                <form action="{{ route('admin.articles.toggle-status', $article) }}" method="POST" class="inline-form">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn-status">
                        <i class="fas fa-sync-alt"></i>
                        <span>Changer statut</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
            <button type="button" class="alert-close" onclick="this.parentElement.remove()">×</button>
        </div>
    @endif

    {{-- Main Content Grid --}}
    <div class="show-grid">
        
        {{-- Colonne gauche : Informations principales --}}
        <div class="show-main">
            
            {{-- Galerie photos --}}
            <div class="card gallery-card">
                <div class="card-header">
                    <h3>
                        <i class="fas fa-images"></i>
                        Photos ({{ $article->images->count() }})
                    </h3>
                </div>
                <div class="card-body">
                    @if($article->images && $article->images->count() > 0)
                        <div class="gallery-grid">
                            @foreach($article->images as $image)
                                <div class="gallery-item {{ $image->is_primary ? 'primary' : '' }}">
                                    <img src="{{ asset('storage/' . $image->path) }}" alt="Photo de l'article">
                                    @if($image->is_primary)
                                        <span class="primary-badge">
                                            <i class="fas fa-star"></i> Principale
                                        </span>
                                    @endif
                                    <a href="{{ asset('storage/' . $image->path) }}" target="_blank" class="zoom-btn">
                                        <i class="fas fa-search-plus"></i>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="no-images">
                            <i class="fas fa-image"></i>
                            <p>Aucune photo pour cet article</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Description --}}
            <div class="card description-card">
                <div class="card-header">
                    <h3>
                        <i class="fas fa-align-left"></i>
                        Description
                    </h3>
                </div>
                <div class="card-body">
                    <div class="description-content">
                        {{ $article->description ?: 'Aucune description fournie.' }}
                    </div>
                </div>
            </div>

        </div>

        {{-- Colonne droite : Détails et stats --}}
        <div class="show-sidebar">
            
            {{-- Informations principales --}}
            <div class="card info-card">
                <div class="card-header">
                    <h3>
                        <i class="fas fa-info-circle"></i>
                        Informations
                    </h3>
                </div>
                <div class="card-body">
                    
                    {{-- Titre --}}
                    <div class="info-item">
                        <span class="info-label">
                            <i class="fas fa-heading"></i>
                            Titre
                        </span>
                        <span class="info-value">{{ $article->title }}</span>
                    </div>

                    {{-- Prix --}}
                    <div class="info-item highlight">
                        <span class="info-label">
                            <i class="fas fa-tag"></i>
                            Prix
                        </span>
                        <span class="info-value price-value">
                            {{ number_format($article->price, 0, ',', ' ') }} FCFA
                        </span>
                    </div>

                    {{-- Catégorie --}}
                    <div class="info-item">
                        <span class="info-label">
                            <i class="fas fa-folder"></i>
                            Catégorie
                        </span>
                        <span class="info-value">
                            <span class="category-badge">
                                {{ $article->category->icon ?? '📦' }} {{ $article->category->name ?? 'Non catégorisé' }}
                            </span>
                        </span>
                    </div>

                    {{-- État --}}
                    <div class="info-item">
                        <span class="info-label">
                            <i class="fas fa-star"></i>
                            État
                        </span>
                        <span class="info-value">
                            @php
                                $conditions = [
                                    'neuf' => ['✨', 'Neuf', 'success'],
                                    'tres_bon' => ['👍', 'Très bon état', 'success'],
                                    'bon' => ['👌', 'Bon état', 'info'],
                                    'acceptable' => ['⚠️', 'Acceptable', 'warning'],
                                ];
                                $c = $conditions[$article->condition] ?? ['📦', $article->condition, 'secondary'];
                            @endphp
                            <span class="badge badge-{{ $c[2] }}">{{ $c[0] }} {{ $c[1] }}</span>
                        </span>
                    </div>

                    {{-- Statut --}}
                    <div class="info-item">
                        <span class="info-label">
                            <i class="fas fa-circle"></i>
                            Statut
                        </span>
                        <span class="info-value">
                            @if($article->status === 'disponible')
                                <span class="badge badge-success">✅ Disponible</span>
                            @elseif($article->status === 'vendu')
                                <span class="badge badge-danger">🏷️ Vendu</span>
                            @else
                                <span class="badge badge-warning">⏳ Réservé</span>
                            @endif
                        </span>
                    </div>

                    {{-- Localisation --}}
                    @if($article->location)
                        <div class="info-item">
                            <span class="info-label">
                                <i class="fas fa-location-dot"></i>
                                Localisation
                            </span>
                            <span class="info-value">{{ $article->location }}</span>
                        </div>
                    @endif

                    {{-- Slug --}}
                    <div class="info-item">
                        <span class="info-label">
                            <i class="fas fa-link"></i>
                            Slug
                        </span>
                        <span class="info-value slug-value">{{ $article->slug }}</span>
                    </div>

                </div>
            </div>

            {{-- Vendeur --}}
            <div class="card seller-card">
                <div class="card-header">
                    <h3>
                        <i class="fas fa-user"></i>
                        Vendeur
                    </h3>
                    <a href="{{ route('admin.users.show', $article->user) }}" class="view-link">
                        Voir profil <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                <div class="card-body">
                    <div class="seller-info">
                        <div class="seller-avatar">
                            @if($article->user->avatar)
                                <img src="{{ asset('storage/' . $article->user->avatar) }}" alt="{{ $article->user->name }}">
                            @else
                                <span class="avatar-placeholder">
                                    {{ strtoupper(substr($article->user->name, 0, 1)) }}
                                </span>
                            @endif
                        </div>
                        <div class="seller-details">
                            <span class="seller-name">{{ $article->user->name }}</span>
                            <span class="seller-email">{{ $article->user->email }}</span>
                            <span class="seller-role">
                                @if($article->user->role === 'admin')
                                    <span class="badge badge-admin">Admin</span>
                                @else
                                    <span class="badge badge-user">Utilisateur</span>
                                @endif
                            </span>
                        </div>
                    </div>
                    
                    <div class="seller-stats">
                        <div class="seller-stat">
                            <span class="stat-label">Articles publiés</span>
                            <span class="stat-value">{{ $article->user->articles()->count() }}</span>
                        </div>
                        <div class="seller-stat">
                            <span class="stat-label">Membre depuis</span>
                            <span class="stat-value">{{ $article->user->created_at->format('d/m/Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Statistiques --}}
            <div class="card stats-card">
                <div class="card-header">
                    <h3>
                        <i class="fas fa-chart-bar"></i>
                        Statistiques
                    </h3>
                </div>
                <div class="card-body">
                    <div class="stats-list">
                        <div class="stat-row">
                            <span class="stat-icon">
                                <i class="fas fa-eye"></i>
                            </span>
                            <div class="stat-info">
                                <span class="stat-value-large">{{ number_format($article->views) }}</span>
                                <span class="stat-label">Vues totales</span>
                            </div>
                        </div>
                        
                        <div class="stat-row">
                            <span class="stat-icon">
                                <i class="fas fa-calendar-plus"></i>
                            </span>
                            <div class="stat-info">
                                <span class="stat-value-large">{{ $article->created_at->format('d/m/Y') }}</span>
                                <span class="stat-label">Date de création</span>
                            </div>
                        </div>
                        
                        <div class="stat-row">
                            <span class="stat-icon">
                                <i class="fas fa-calendar-check"></i>
                            </span>
                            <div class="stat-info">
                                <span class="stat-value-large">{{ $article->updated_at->format('d/m/Y') }}</span>
                                <span class="stat-label">Dernière modification</span>
                            </div>
                        </div>
                        
                        <div class="stat-row">
                            <span class="stat-icon">
                                <i class="fas fa-clock"></i>
                            </span>
                            <div class="stat-info">
                                <span class="stat-value-large">{{ $article->created_at->diffForHumans() }}</span>
                                <span class="stat-label">Publié</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Identifiants --}}
            <div class="card ids-card">
                <div class="card-header">
                    <h3>
                        <i class="fas fa-fingerprint"></i>
                        Identifiants
                    </h3>
                </div>
                <div class="card-body">
                    <div class="id-item">
                        <span class="id-label">ID Article</span>
                        <span class="id-value">#{{ $article->id }}</span>
                    </div>
                    <div class="id-item">
                        <span class="id-label">ID Vendeur</span>
                        <span class="id-value">#{{ $article->user_id }}</span>
                    </div>
                    <div class="id-item">
                        <span class="id-label">ID Catégorie</span>
                        <span class="id-value">#{{ $article->category_id }}</span>
                    </div>
                </div>
            </div>

        </div>
    </div>

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

{{-- Styles --}}
<style>
.admin-articles-show-container {
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
    margin: 0;
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

.header-actions {
    display: flex;
    gap: 12px;
}

.btn-primary, .btn-secondary, .btn-status {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    border-radius: 8px;
    font-weight: 500;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.2s;
    text-decoration: none;
}

.btn-primary {
    background: #4f46e5;
    border: none;
    color: white;
}

.btn-primary:hover {
    background: #4338ca;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
}

.btn-secondary {
    background: white;
    border: 1px solid #e5e7eb;
    color: #374151;
}

.btn-secondary:hover {
    background: #f9fafb;
    border-color: #d1d5db;
}

.btn-status {
    background: #f3f4f6;
    border: 1px solid #e5e7eb;
    color: #374151;
}

.btn-status:hover {
    background: #e5e7eb;
}

.inline-form {
    display: inline;
}

/* Alert */
.alert {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 18px;
    border-radius: 10px;
    margin-bottom: 24px;
}

.alert-success {
    background: #f0fdf4;
    border: 1px solid #86efac;
    color: #166534;
}

.alert-close {
    margin-left: auto;
    background: none;
    border: none;
    font-size: 22px;
    cursor: pointer;
    opacity: 0.6;
}

/* Show Grid */
.show-grid {
    display: grid;
    grid-template-columns: 1fr 400px;
    gap: 24px;
    margin-bottom: 32px;
}

/* Cards */
.card {
    background: white;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    overflow: hidden;
    margin-bottom: 24px;
}

.card:last-child {
    margin-bottom: 0;
}

.card-header {
    padding: 18px 20px;
    border-bottom: 1px solid #e5e7eb;
    background: #fafafa;
    display: flex;
    justify-content: space-between;
    align-items: center;
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
}

.card-body {
    padding: 20px;
}

.view-link {
    color: #4f46e5;
    text-decoration: none;
    font-size: 13px;
    display: flex;
    align-items: center;
    gap: 4px;
}

.view-link:hover {
    text-decoration: underline;
}

/* Gallery */
.gallery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 16px;
}

.gallery-item {
    position: relative;
    aspect-ratio: 1;
    border-radius: 8px;
    overflow: hidden;
    border: 2px solid #e5e7eb;
}

.gallery-item.primary {
    border-color: #4f46e5;
    box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.2);
}

.gallery-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.primary-badge {
    position: absolute;
    bottom: 8px;
    left: 8px;
    background: #4f46e5;
    color: white;
    font-size: 11px;
    font-weight: 600;
    padding: 4px 10px;
    border-radius: 20px;
    display: flex;
    align-items: center;
    gap: 4px;
}

.zoom-btn {
    position: absolute;
    top: 8px;
    right: 8px;
    width: 32px;
    height: 32px;
    background: rgba(0, 0, 0, 0.6);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    opacity: 0;
    transition: opacity 0.2s;
}

.gallery-item:hover .zoom-btn {
    opacity: 1;
}

.no-images {
    text-align: center;
    padding: 40px;
    color: #9ca3af;
}

.no-images i {
    font-size: 48px;
    margin-bottom: 12px;
}

/* Description */
.description-content {
    line-height: 1.7;
    color: #374151;
    font-size: 15px;
    white-space: pre-wrap;
}

/* Info Items */
.info-item {
    display: flex;
    padding: 12px 0;
    border-bottom: 1px solid #f3f4f6;
}

.info-item:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.info-item.highlight {
    background: #f0fdf4;
    margin: 0 -20px;
    padding: 16px 20px;
    border-bottom: 1px solid #86efac;
    border-top: 1px solid #86efac;
}

.info-label {
    width: 120px;
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    font-weight: 500;
    color: #6b7280;
}

.info-value {
    flex: 1;
    font-size: 14px;
    color: #111827;
}

.price-value {
    font-size: 24px;
    font-weight: 700;
    color: #059669;
}

.category-badge {
    display: inline-block;
    padding: 4px 12px;
    background: #f3f4f6;
    border-radius: 20px;
    font-size: 13px;
}

.slug-value {
    font-family: monospace;
    color: #6b7280;
}

/* Badges */
.badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
}

.badge-success { background: #d1fae5; color: #065f46; }
.badge-danger { background: #fee2e2; color: #991b1b; }
.badge-warning { background: #fef3c7; color: #92400e; }
.badge-info { background: #dbeafe; color: #1e40af; }
.badge-admin { background: #fef2f2; color: #dc2626; font-size: 11px; padding: 2px 8px; }
.badge-user { background: #e5e7eb; color: #374151; font-size: 11px; padding: 2px 8px; }

/* Seller */
.seller-info {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 20px;
}

.seller-avatar {
    width: 56px;
    height: 56px;
    border-radius: 12px;
    overflow: hidden;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    flex-shrink: 0;
}

.seller-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.avatar-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 22px;
    font-weight: 600;
}

.seller-details {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.seller-name {
    font-weight: 600;
    color: #111827;
}

.seller-email {
    font-size: 13px;
    color: #6b7280;
}

.seller-stats {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    padding-top: 16px;
    border-top: 1px solid #e5e7eb;
}

.seller-stat {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.seller-stat .stat-label {
    font-size: 11px;
    color: #9ca3af;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.seller-stat .stat-value {
    font-size: 18px;
    font-weight: 700;
    color: #111827;
}

/* Stats List */
.stats-list {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.stat-row {
    display: flex;
    align-items: center;
    gap: 14px;
}

.stat-row .stat-icon {
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

.stat-row .stat-info {
    display: flex;
    flex-direction: column;
}

.stat-value-large {
    font-size: 20px;
    font-weight: 700;
    color: #111827;
}

.stat-row .stat-label {
    font-size: 12px;
    color: #6b7280;
}

/* IDs */
.id-item {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid #f3f4f6;
}

.id-item:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.id-label {
    font-size: 13px;
    color: #6b7280;
}

.id-value {
    font-family: monospace;
    font-size: 14px;
    font-weight: 500;
    color: #111827;
}

/* Danger Zone */
.danger-zone {
    background: #fef2f2;
    border: 1px solid #fca5a5;
    border-radius: 12px;
    padding: 20px;
    margin-top: 8px;
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
    .show-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .admin-articles-show-container {
        padding: 16px;
    }
    
    .header-content {
        flex-direction: column;
        gap: 16px;
    }
    
    .header-actions {
        width: 100%;
        flex-wrap: wrap;
    }
    
    .header-actions .btn-primary,
    .header-actions .btn-secondary,
    .header-actions .btn-status {
        flex: 1;
        justify-content: center;
    }
    
    .gallery-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .info-item {
        flex-direction: column;
        gap: 6px;
    }
    
    .info-label {
        width: 100%;
    }
}
</style>
@endsection