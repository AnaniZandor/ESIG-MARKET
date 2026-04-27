@extends('layouts.app')

@section('title', 'Gestion des articles')

@section('content')
<div class="admin-articles-container">
    
    {{-- Header --}}
    <div class="page-header">
        <div class="header-content">
            <div class="title-section">
                <h1 class="page-title">
                    <span class="icon-badge">
                        <i class="fas fa-newspaper"></i>
                    </span>
                    Articles
                </h1>
                <p class="page-subtitle">Gérez toutes les annonces de la plateforme</p>
            </div>
        </div>
    </div>

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
            <button type="button" class="alert-close" onclick="this.parentElement.remove()">&times;</button>
        </div>
    @endif

    {{-- Stats Overview --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon primary">
                <i class="fas fa-newspaper"></i>
            </div>
            <div class="stat-info">
                <span class="stat-label">Total articles</span>
                <span class="stat-value">{{ $stats['total'] }}</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon success">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-info">
                <span class="stat-label">Disponibles</span>
                <span class="stat-value">{{ $stats['disponible'] }}</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon warning">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-info">
                <span class="stat-label">Réservés</span>
                <span class="stat-value">{{ $stats['reserve'] }}</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon danger">
                <i class="fas fa-tag"></i>
            </div>
            <div class="stat-info">
                <span class="stat-label">Vendus</span>
                <span class="stat-value">{{ $stats['vendu'] }}</span>
            </div>
        </div>
    </div>

    {{-- Filters Bar --}}
    <div class="filters-bar">
        <form method="GET" action="{{ route('admin.articles.index') }}" class="filters-form">
            <div class="search-wrapper">
                <i class="fas fa-search search-icon"></i>
                <input type="text" name="search" class="search-input" 
                       placeholder="Rechercher par titre ou description..."
                       value="{{ request('search') }}">
            </div>
            <div class="filter-group">
                <select name="category" class="filter-select">
                    <option value="">Toutes catégories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->icon }} {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
                <select name="status" class="filter-select">
                    <option value="">Tous statuts</option>
                    <option value="disponible" {{ request('status') == 'disponible' ? 'selected' : '' }}>✅ Disponible</option>
                    <option value="reserve" {{ request('status') == 'reserve' ? 'selected' : '' }}>⏳ Réservé</option>
                    <option value="vendu" {{ request('status') == 'vendu' ? 'selected' : '' }}>🏷️ Vendu</option>
                </select>
                <select name="condition" class="filter-select">
                    <option value="">Tous états</option>
                    <option value="neuf" {{ request('condition') == 'neuf' ? 'selected' : '' }}>✨ Neuf</option>
                    <option value="tres_bon" {{ request('condition') == 'tres_bon' ? 'selected' : '' }}>👍 Très bon</option>
                    <option value="bon" {{ request('condition') == 'bon' ? 'selected' : '' }}>👌 Bon</option>
                    <option value="acceptable" {{ request('condition') == 'acceptable' ? 'selected' : '' }}>⚠️ Acceptable</option>
                </select>
                <select name="user" class="filter-select">
                    <option value="">Tous vendeurs</option>
                    @foreach($users as $u)
                        <option value="{{ $u->id }}" {{ request('user') == $u->id ? 'selected' : '' }}>
                            {{ $u->name }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="btn-filter">
                    <i class="fas fa-filter"></i> Filtrer
                </button>
                @if(request()->anyFilled(['search', 'category', 'status', 'condition', 'user']))
                    <a href="{{ route('admin.articles.index') }}" class="btn-reset">
                        <i class="fas fa-times"></i> Réinitialiser
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Articles Table --}}
    <div class="table-container">
        <div class="table-header">
            <div class="results-count">
                <i class="fas fa-list-ul"></i>
                <span>{{ $articles->total() }} article(s) trouvé(s)</span>
            </div>
        </div>

        <table class="articles-table">
            <thead>
                <tr>
                    <th>Article</th>
                    <th>Catégorie</th>
                    <th>Prix</th>
                    <th>État</th>
                    <th>Statut</th>
                    <th>Vendeur</th>
                    <th>Vues</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($articles as $article)
                    <tr>
                        {{-- Article --}}
                        <td>
                            <div class="article-info">
                                <div class="article-image">
                                    @if($article->images->first())
                                        <img src="{{ asset('storage/' . $article->images->first()->path) }}" 
                                             alt="{{ $article->title }}">
                                    @else
                                        <div class="no-image">
                                            <i class="fas fa-image"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="article-details">
                                    <a href="{{ route('admin.articles.show', $article) }}" class="article-title">
                                        {{ Str::limit($article->title, 30) }}
                                    </a>
                                    <span class="article-id">#{{ $article->id }}</span>
                                </div>
                            </div>
                        </td>

                        {{-- Catégorie --}}
                        <td>
                            <span class="category-badge">
                                {{ $article->category->icon ?? '📦' }} {{ $article->category->name ?? 'N/A' }}
                            </span>
                        </td>

                        {{-- Prix --}}
                        <td>
                            <span class="price">{{ number_format($article->price, 0, ',', ' ') }} FCFA</span>
                        </td>

                        {{-- État --}}
                        <td>
                            @php
                                $conditions = [
                                    'neuf' => ['✨', 'Neuf', 'success'],
                                    'tres_bon' => ['👍', 'Très bon', 'success'],
                                    'bon' => ['👌', 'Bon', 'info'],
                                    'acceptable' => ['⚠️', 'Acceptable', 'warning'],
                                ];
                                $c = $conditions[$article->condition] ?? ['📦', $article->condition, 'secondary'];
                            @endphp
                            <span class="badge badge-{{ $c[2] }}">{{ $c[0] }} {{ $c[1] }}</span>
                        </td>

                        {{-- Statut --}}
                        <td>
                            @if($article->status === 'disponible')
                                <span class="badge badge-success">✅ Disponible</span>
                            @elseif($article->status === 'vendu')
                                <span class="badge badge-danger">🏷️ Vendu</span>
                            @else
                                <span class="badge badge-warning">⏳ Réservé</span>
                            @endif
                        </td>

                        {{-- Vendeur --}}
                        <td>
                            <div class="seller-info">
                                <span class="seller-name">{{ $article->user->name ?? 'N/A' }}</span>
                                <span class="seller-email">{{ $article->user->email ?? '' }}</span>
                            </div>
                        </td>

                        {{-- Vues --}}
                        <td>
                            <span class="views">
                                <i class="fas fa-eye"></i> {{ $article->views }}
                            </span>
                        </td>

                        {{-- Date --}}
                        <td>
                            <span class="date">{{ $article->created_at->format('d/m/Y') }}</span>
                        </td>

                        {{-- Actions --}}
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('articles.show', $article) }}" target="_blank" 
                                   class="action-btn view" title="Voir public">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.articles.edit', $article) }}" 
                                   class="action-btn edit" title="Modifier">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <form action="{{ route('admin.articles.toggle-status', $article) }}" method="POST" class="action-form">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="action-btn status" title="Changer statut">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.articles.destroy', $article) }}" method="POST" 
                                      class="action-form"
                                      onsubmit="return confirm('Supprimer définitivement cet article ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn delete" title="Supprimer">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr class="empty-row">
                        <td colspan="9">
                            <div class="empty-state">
                                <i class="fas fa-box-open"></i>
                                <h3>Aucun article trouvé</h3>
                                <p>Ajustez vos filtres ou créez un nouvel article</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($articles->hasPages())
            <div class="pagination-wrapper">
                {{ $articles->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>

<style>
.admin-articles-container {
    max-width: 1600px;
    margin: 0 auto;
    padding: 24px 32px;
}

.page-header {
    margin-bottom: 28px;
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

/* Stats */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-bottom: 28px;
}

.stat-card {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 20px;
    background: white;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
}

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

.stat-icon.primary { background: #e0e7ff; color: #4f46e5; }
.stat-icon.success { background: #d1fae5; color: #059669; }
.stat-icon.warning { background: #fef3c7; color: #d97706; }
.stat-icon.danger { background: #fee2e2; color: #dc2626; }

.stat-info {
    display: flex;
    flex-direction: column;
}

.stat-label {
    font-size: 13px;
    color: #6b7280;
}

.stat-value {
    font-size: 28px;
    font-weight: 700;
    color: #111827;
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
}

/* Filters */
.filters-bar {
    background: white;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    padding: 16px 20px;
    margin-bottom: 24px;
}

.filters-form {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    flex-wrap: wrap;
}

.search-wrapper {
    position: relative;
    flex: 1;
    min-width: 250px;
}

.search-icon {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: #9ca3af;
}

.search-input {
    width: 100%;
    padding: 10px 14px 10px 40px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    font-size: 14px;
}

.filter-group {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}

.filter-select {
    padding: 10px 14px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    font-size: 14px;
    background: white;
    min-width: 140px;
}

.btn-filter {
    padding: 10px 18px;
    background: #f3f4f6;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    color: #374151;
    cursor: pointer;
}

.btn-reset {
    padding: 10px 18px;
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    font-size: 14px;
    color: #6b7280;
    text-decoration: none;
}

/* Table */
.table-container {
    background: white;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    overflow: hidden;
}

.table-header {
    padding: 16px 20px;
    border-bottom: 1px solid #e5e7eb;
}

.results-count {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #6b7280;
    font-size: 14px;
}

.articles-table {
    width: 100%;
    border-collapse: collapse;
}

.articles-table thead tr {
    background: #f9fafb;
    border-bottom: 1px solid #e5e7eb;
}

.articles-table th {
    padding: 12px 16px;
    text-align: left;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    color: #6b7280;
}

.articles-table td {
    padding: 16px;
    border-bottom: 1px solid #f3f4f6;
    vertical-align: middle;
}

.article-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.article-image {
    width: 50px;
    height: 50px;
    border-radius: 8px;
    overflow: hidden;
    background: #f3f4f6;
    flex-shrink: 0;
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
}

.article-details {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.article-title {
    font-weight: 600;
    color: #111827;
    text-decoration: none;
}

.article-title:hover {
    color: #4f46e5;
}

.article-id {
    font-size: 11px;
    color: #9ca3af;
}

.category-badge {
    display: inline-block;
    padding: 4px 10px;
    background: #f3f4f6;
    border-radius: 20px;
    font-size: 13px;
}

.price {
    font-weight: 700;
    color: #059669;
}

.badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
}

.badge-success { background: #d1fae5; color: #065f46; }
.badge-danger { background: #fee2e2; color: #991b1b; }
.badge-warning { background: #fef3c7; color: #92400e; }
.badge-info { background: #dbeafe; color: #1e40af; }
.badge-secondary { background: #e5e7eb; color: #374151; }

.seller-info {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.seller-name {
    font-weight: 500;
    color: #111827;
}

.seller-email {
    font-size: 12px;
    color: #6b7280;
}

.views {
    display: flex;
    align-items: center;
    gap: 6px;
    color: #6b7280;
}

.date {
    color: #6b7280;
    font-size: 13px;
}

.action-buttons {
    display: flex;
    align-items: center;
    gap: 4px;
}

.action-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 6px;
    background: transparent;
    border: none;
    color: #6b7280;
    cursor: pointer;
    text-decoration: none;
}

.action-btn:hover {
    background: #f3f4f6;
}

.action-btn.view:hover { color: #3b82f6; }
.action-btn.edit:hover { color: #8b5cf6; }
.action-btn.status:hover { color: #f59e0b; }
.action-btn.delete:hover { color: #dc2626; }

.action-form {
    display: inline;
}

.empty-row td {
    padding: 60px !important;
    text-align: center;
}

.empty-state {
    color: #9ca3af;
}

.empty-state i {
    font-size: 48px;
    margin-bottom: 12px;
}

.empty-state h3 {
    margin: 0 0 8px 0;
    font-size: 18px;
    color: #6b7280;
}

.pagination-wrapper {
    padding: 16px 20px;
    border-top: 1px solid #e5e7eb;
}

@media (max-width: 1200px) {
    .stats-grid { grid-template-columns: repeat(2, 1fr); }
    .articles-table { display: block; overflow-x: auto; }
}

@media (max-width: 768px) {
    .admin-articles-container { padding: 16px; }
    .stats-grid { grid-template-columns: 1fr; }
    .filters-form { flex-direction: column; }
    .filter-group { justify-content: center; }
    .filter-select { min-width: 100%; }
}
</style>
@endsection