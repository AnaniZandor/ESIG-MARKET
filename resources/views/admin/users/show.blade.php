@extends('layouts.app')

@section('title', 'Profil de ' . $user->name)

@section('content')
<div class="profile-container">
    
    {{-- Header avec navigation --}}
    <div class="profile-header">
        <div class="header-left">
            <a href="{{ route('admin.users.index') }}" class="back-link">
                <i class="fas fa-arrow-left"></i>
                <span>Retour à la liste</span>
            </a>
            <h1 class="profile-title">
                <i class="fas fa-user-circle"></i>
                Profil utilisateur
            </h1>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.users.edit', $user) }}" class="btn-secondary">
                <i class="fas fa-pen"></i>
                <span>Modifier</span>
            </a>
            @if($user->id !== auth()->id())
                <form action="{{ route('admin.users.toggle-active', $user) }}" method="POST" class="inline-form">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn-{{ $user->is_active ? 'warning' : 'success' }}">
                        <i class="fas fa-{{ $user->is_active ? 'ban' : 'check' }}"></i>
                        <span>{{ $user->is_active ? 'Désactiver' : 'Activer' }}</span>
                    </button>
                </form>
            @endif
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
    <div class="profile-grid">
        
        {{-- Colonne gauche : Infos principales --}}
        <div class="profile-sidebar">
            
            {{-- Carte Avatar et infos de base --}}
            <div class="card profile-card">
                <div class="card-body text-center">
                    <div class="avatar-wrapper">
                        @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" 
                                 alt="{{ $user->name }}" 
                                 class="profile-avatar">
                        @else
                            <div class="avatar-placeholder">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    
                    <h2 class="user-fullname">{{ $user->name }}</h2>
                    
                    <div class="user-badges">
                        @if($user->role === 'admin')
                            <span class="badge badge-admin-lg">
                                <i class="fas fa-crown"></i>
                                Administrateur
                            </span>
                        @else
                            <span class="badge badge-user-lg">
                                <i class="fas fa-user"></i>
                                Utilisateur
                            </span>
                        @endif
                        
                        @if($user->is_active)
                            <span class="badge badge-active-lg">
                                <span class="pulse"></span>
                                Compte actif
                            </span>
                        @else
                            <span class="badge badge-inactive-lg">
                                <i class="fas fa-clock"></i>
                                Compte inactif
                            </span>
                        @endif
                    </div>
                    
                    <div class="user-meta-info">
                        <div class="meta-item">
                            <i class="fas fa-calendar-alt"></i>
                            <span>Inscrit le {{ $user->created_at->format('d/m/Y') }}</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-clock"></i>
                            <span>{{ $user->created_at->diffForHumans() }}</span>
                        </div>
                        @if($user->email_verified_at)
                            <div class="meta-item verified">
                                <i class="fas fa-check-circle"></i>
                                <span>Email vérifié le {{ $user->email_verified_at->format('d/m/Y') }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Carte Contact --}}
            <div class="card info-card">
                <div class="card-header">
                    <h3><i class="fas fa-address-card"></i> Contact</h3>
                </div>
                <div class="card-body">
                    <div class="info-list">
                        <div class="info-item">
                            <span class="info-label">
                                <i class="fas fa-envelope"></i>
                                Email
                            </span>
                            <span class="info-value">
                                <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                                @if($user->email_verified_at)
                                    <i class="fas fa-check-circle verified-icon" title="Email vérifié"></i>
                                @else
                                    <i class="fas fa-exclamation-circle unverified-icon" title="Email non vérifié"></i>
                                @endif
                            </span>
                        </div>
                        
                        @if($user->filiere)
                            <div class="info-item">
                                <span class="info-label">
                                    <i class="fas fa-graduation-cap"></i>
                                    Filière
                                </span>
                                <span class="info-value">{{ $user->filiere }}</span>
                            </div>
                        @endif
                        
                        @if($user->numero_etudiant)
                            <div class="info-item">
                                <span class="info-label">
                                    <i class="fas fa-id-card"></i>
                                    N° Étudiant
                                </span>
                                <span class="info-value">{{ $user->numero_etudiant }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Carte Bio --}}
            @if($user->bio)
                <div class="card info-card">
                    <div class="card-header">
                        <h3><i class="fas fa-quote-right"></i> Biographie</h3>
                    </div>
                    <div class="card-body">
                        <p class="user-bio">{{ $user->bio }}</p>
                    </div>
                </div>
            @endif

        </div>

        {{-- Colonne droite : Statistiques et activité --}}
        <div class="profile-main">
            
            {{-- Statistiques rapides --}}
            <div class="stats-grid">
                <div class="stat-box">
                    <div class="stat-icon blue">
                        <i class="fas fa-newspaper"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-number">{{ $user->articles()->count() }}</span>
                        <span class="stat-label">Articles publiés</span>
                    </div>
                </div>
                
                <div class="stat-box">
                    <div class="stat-icon green">
                        <i class="fas fa-eye"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-number">{{ $user->articles()->sum('views') }}</span>
                        <span class="stat-label">Vues totales</span>
                    </div>
                </div>
                
                <div class="stat-box">
                    <div class="stat-icon purple">
                        <i class="fas fa-calendar-week"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-number">{{ $user->created_at->diffInDays(now()) }}</span>
                        <span class="stat-label">Jours d'ancienneté</span>
                    </div>
                </div>
            </div>

            {{-- Articles récents --}}
            <div class="card articles-card">
                <div class="card-header">
                    <h3>
                        <i class="fas fa-history"></i>
                        Articles récents
                    </h3>
                    @if($articles->count() > 0)
                        <a href="#" class="view-all-link">
                            Voir tout
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    @endif
                </div>
                <div class="card-body">
                    @forelse($articles as $article)
                        <div class="article-item">
                            <div class="article-image">
                                @if($article->images()->first())
                                    <img src="{{ asset('storage/' . $article->images()->first()->path) }}" 
                                         alt="{{ $article->title }}">
                                @else
                                    <div class="no-image">
                                        <i class="fas fa-image"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="article-info">
                                <h4 class="article-title">
                                    <a href="{{ route('articles.show', $article) }}" target="_blank">
                                        {{ $article->title }}
                                    </a>
                                </h4>
                                <div class="article-meta">
                                    <span class="article-price">{{ number_format($article->price, 0, ',', ' ') }} FCFA</span>
                                    <span class="article-badge {{ $article->status === 'disponible' ? 'available' : 'sold' }}">
                                        {{ ucfirst($article->status) }}
                                    </span>
                                </div>
                                <div class="article-stats">
                                    <span><i class="fas fa-eye"></i> {{ $article->views }} vues</span>
                                    <span><i class="fas fa-calendar"></i> {{ $article->created_at->format('d/m/Y') }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-articles">
                            <i class="fas fa-box-open"></i>
                            <p>Aucun article publié pour le moment</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Informations système --}}
            <div class="card system-card">
                <div class="card-header">
                    <h3><i class="fas fa-database"></i> Informations système</h3>
                </div>
                <div class="card-body">
                    <div class="system-grid">
                        <div class="system-item">
                            <span class="system-label">ID Utilisateur</span>
                            <span class="system-value">#{{ $user->id }}</span>
                        </div>
                        <div class="system-item">
                            <span class="system-label">Dernière mise à jour</span>
                            <span class="system-value">{{ $user->updated_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="system-item">
                            <span class="system-label">Rôle</span>
                            <span class="system-value">{{ ucfirst($user->role) }}</span>
                        </div>
                        <div class="system-item">
                            <span class="system-label">Statut</span>
                            <span class="system-value {{ $user->is_active ? 'active' : 'inactive' }}">
                                {{ $user->is_active ? 'Actif' : 'Inactif' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Actions dangereuses --}}
            @if($user->id !== auth()->id())
                <div class="danger-zone">
                    <h4><i class="fas fa-exclamation-triangle"></i> Zone dangereuse</h4>
                    <p>Une fois que vous supprimez un utilisateur, toutes ses données seront définitivement perdues.</p>
                    <form action="{{ route('admin.users.destroy', $user) }}" 
                          method="POST" 
                          onsubmit="return confirmDelete('{{ $user->name }}', '{{ $user->email }}')">
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
    </div>
</div>

{{-- Script de confirmation --}}
<script>
function confirmDelete(name, email) {
    return confirm(`⚠️ ATTENTION !\n\nVous êtes sur le point de supprimer définitivement :\n\n${name}\n${email}\n\nCette action est IRRÉVERSIBLE et supprimera toutes les données associées (articles, images, etc.).\n\nÊtes-vous absolument sûr de vouloir continuer ?`);
}
</script>

{{-- Styles --}}
<style>
/* Container */
.profile-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 24px 32px;
}

/* Header */
.profile-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 28px;
}

.header-left {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.back-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #6b7280;
    text-decoration: none;
    font-size: 14px;
    transition: color 0.2s;
}

.back-link:hover {
    color: #4f46e5;
}

.profile-title {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 28px;
    font-weight: 600;
    color: #111827;
    margin: 0;
}

.profile-title i {
    color: #4f46e5;
    font-size: 32px;
}

.header-actions {
    display: flex;
    gap: 12px;
}

/* Buttons */
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

.btn-warning {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    background: #f59e0b;
    border: none;
    border-radius: 8px;
    color: white;
    font-weight: 500;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-warning:hover {
    background: #d97706;
}

.btn-success {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    background: #10b981;
    border: none;
    border-radius: 8px;
    color: white;
    font-weight: 500;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-success:hover {
    background: #059669;
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

/* Profile Grid */
.profile-grid {
    display: grid;
    grid-template-columns: 380px 1fr;
    gap: 24px;
}

/* Cards */
.card {
    background: white;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    overflow: hidden;
    margin-bottom: 24px;
}

.card-header {
    padding: 18px 20px;
    border-bottom: 1px solid #e5e7eb;
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

/* Profile Card */
.profile-card {
    text-align: center;
}

.avatar-wrapper {
    margin-bottom: 16px;
}

.profile-avatar {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid white;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.avatar-placeholder {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 48px;
    font-weight: 600;
    margin: 0 auto;
    border: 4px solid white;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.user-fullname {
    font-size: 22px;
    font-weight: 700;
    color: #111827;
    margin: 12px 0 16px;
}

.user-badges {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    justify-content: center;
    margin-bottom: 20px;
}

.badge-admin-lg {
    padding: 6px 14px;
    background: #fef2f2;
    color: #dc2626;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.badge-user-lg {
    padding: 6px 14px;
    background: #f3f4f6;
    color: #4b5563;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.badge-active-lg {
    padding: 6px 14px;
    background: #f0fdf4;
    color: #166534;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.badge-inactive-lg {
    padding: 6px 14px;
    background: #fef3c7;
    color: #92400e;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.pulse {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #10b981;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
    70% { box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
    100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
}

.user-meta-info {
    border-top: 1px solid #e5e7eb;
    padding-top: 16px;
    margin-top: 8px;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 0;
    color: #6b7280;
    font-size: 13px;
}

.meta-item i {
    width: 18px;
    color: #9ca3af;
}

.meta-item.verified {
    color: #059669;
}

.meta-item.verified i {
    color: #059669;
}

/* Info Card */
.info-list {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.info-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #6b7280;
}

.info-value {
    font-size: 15px;
    color: #111827;
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

.info-value a {
    color: #4f46e5;
    text-decoration: none;
}

.info-value a:hover {
    text-decoration: underline;
}

.verified-icon {
    color: #10b981;
    font-size: 14px;
}

.unverified-icon {
    color: #f59e0b;
    font-size: 14px;
}

.user-bio {
    color: #4b5563;
    line-height: 1.6;
    font-size: 14px;
    margin: 0;
}

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
    margin-bottom: 24px;
}

.stat-box {
    background: white;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 16px;
}

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
}

.stat-icon.blue {
    background: #e0e7ff;
    color: #4f46e5;
}

.stat-icon.green {
    background: #d1fae5;
    color: #059669;
}

.stat-icon.purple {
    background: #f3e8ff;
    color: #9333ea;
}

.stat-content {
    display: flex;
    flex-direction: column;
}

.stat-number {
    font-size: 28px;
    font-weight: 700;
    color: #111827;
    line-height: 1.2;
}

.stat-box .stat-label {
    font-size: 13px;
    color: #6b7280;
}

/* Articles Card */
.view-all-link {
    color: #4f46e5;
    text-decoration: none;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.view-all-link:hover {
    text-decoration: underline;
}

.article-item {
    display: flex;
    gap: 16px;
    padding: 16px 0;
    border-bottom: 1px solid #f3f4f6;
}

.article-item:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.article-item:first-child {
    padding-top: 0;
}

.article-image {
    width: 80px;
    height: 80px;
    border-radius: 8px;
    overflow: hidden;
    flex-shrink: 0;
    background: #f3f4f6;
}

.article-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.no-image {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #9ca3af;
    font-size: 24px;
}

.article-info {
    flex: 1;
}

.article-title {
    margin: 0 0 8px 0;
    font-size: 15px;
    font-weight: 600;
}

.article-title a {
    color: #111827;
    text-decoration: none;
}

.article-title a:hover {
    color: #4f46e5;
}

.article-meta {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 8px;
}

.article-price {
    font-weight: 700;
    color: #059669;
    font-size: 16px;
}

.article-badge {
    padding: 3px 10px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
}

.article-badge.available {
    background: #d1fae5;
    color: #065f46;
}

.article-badge.sold {
    background: #fee2e2;
    color: #991b1b;
}

.article-stats {
    display: flex;
    gap: 16px;
    font-size: 12px;
    color: #6b7280;
}

.article-stats i {
    margin-right: 4px;
}

.empty-articles {
    text-align: center;
    padding: 40px 20px;
    color: #9ca3af;
}

.empty-articles i {
    font-size: 48px;
    margin-bottom: 12px;
}

.empty-articles p {
    margin: 0;
    font-size: 14px;
}

/* System Card */
.system-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
}

.system-item {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.system-label {
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #9ca3af;
}

.system-value {
    font-size: 14px;
    font-weight: 500;
    color: #374151;
}

.system-value.active {
    color: #059669;
}

.system-value.inactive {
    color: #dc2626;
}

/* Danger Zone */
.danger-zone {
    background: #fef2f2;
    border: 1px solid #fca5a5;
    border-radius: 12px;
    padding: 20px;
    margin-top: 24px;
}

.danger-zone h4 {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #dc2626;
    font-size: 16px;
    font-weight: 600;
    margin: 0 0 8px 0;
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
    .profile-grid {
        grid-template-columns: 1fr;
    }
    
    .profile-sidebar {
        order: 1;
    }
    
    .profile-main {
        order: 2;
    }
}

@media (max-width: 768px) {
    .profile-container {
        padding: 16px;
    }
    
    .profile-header {
        flex-direction: column;
        gap: 16px;
    }
    
    .header-actions {
        width: 100%;
    }
    
    .header-actions .btn-secondary,
    .header-actions form {
        flex: 1;
    }
    
    .header-actions button {
        width: 100%;
        justify-content: center;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .system-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection