@extends('layouts.app')

@section('title', 'Dashboard Administration')

@section('content')
<div class="admin-dashboard">
    
    {{-- Header --}}
    <div class="dashboard-header">
        <div class="header-left">
            <h1 class="dashboard-title">
                <span class="icon-badge">
                    <i class="fas fa-chart-pie"></i>
                </span>
                Tableau de bord
            </h1>
            <p class="dashboard-subtitle">Vue d'ensemble de la plateforme</p>
        </div>
        <div class="header-right">
            <span class="date-badge">
                <i class="fas fa-calendar-alt"></i>
                {{ now()->format('d/m/Y') }}
            </span>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="stats-grid">
        <div class="stat-card blue">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-content">
                <span class="stat-value">{{ number_format($totalUsers) }}</span>
                <span class="stat-label">Utilisateurs</span>
            </div>
            <a href="{{ route('admin.users.index') }}" class="stat-link">
                Gérer <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        <div class="stat-card green">
            <div class="stat-icon">
                <i class="fas fa-newspaper"></i>
            </div>
            <div class="stat-content">
                <span class="stat-value">{{ number_format($totalArticles) }}</span>
                <span class="stat-label">Articles</span>
            </div>
            <a href="{{ route('admin.articles.index') }}" class="stat-link">
                Gérer <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        <div class="stat-card purple">
            <div class="stat-icon">
                <i class="fas fa-comments"></i>
            </div>
            <div class="stat-content">
                <span class="stat-value">{{ number_format($totalMessages) }}</span>
                <span class="stat-label">Messages</span>
            </div>
            <span class="stat-link" style="opacity:0.5;">
                Voir <i class="fas fa-arrow-right"></i>
            </span>
        </div>

        <div class="stat-card orange">
            <div class="stat-icon">
                <i class="fas fa-tags"></i>
            </div>
            <div class="stat-content">
                <span class="stat-value">{{ number_format($totalCategories) }}</span>
                <span class="stat-label">Catégories</span>
            </div>
            <span class="stat-link" style="opacity:0.5;">
                Gérer <i class="fas fa-arrow-right"></i>
            </span>
        </div>
    </div>

    {{-- Articles Stats --}}
    <div class="stats-secondary">
        <div class="stat-item">
            <span class="stat-number">{{ number_format($articlesDisponibles) }}</span>
            <span class="stat-text">Articles disponibles</span>
            <div class="stat-badge success">✅</div>
        </div>
        <div class="stat-item">
            <span class="stat-number">{{ number_format($articlesVendus) }}</span>
            <span class="stat-text">Articles vendus</span>
            <div class="stat-badge danger">🏷️</div>
        </div>
        <div class="stat-item">
            <span class="stat-number">{{ number_format($articlesReserves) }}</span>
            <span class="stat-text">Articles réservés</span>
            <div class="stat-badge warning">⏳</div>
        </div>
        <div class="stat-item">
            <span class="stat-number">{{ number_format($totalViews) }}</span>
            <span class="stat-text">Vues totales</span>
            <div class="stat-badge info">👁️</div>
        </div>
    </div>

    {{-- Charts Row --}}
    <div class="charts-row">
        <div class="chart-card">
            <div class="chart-header">
                <h3><i class="fas fa-user-plus"></i> Inscriptions par mois</h3>
                <span class="chart-year">{{ date('Y') }}</span>
            </div>
            <div class="chart-body">
                <canvas id="usersChart"></canvas>
            </div>
        </div>

        <div class="chart-card">
            <div class="chart-header">
                <h3><i class="fas fa-newspaper"></i> Articles par mois</h3>
                <span class="chart-year">{{ date('Y') }}</span>
            </div>
            <div class="chart-body">
                <canvas id="articlesChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Bottom Grid --}}
    <div class="bottom-grid">
        
        {{-- Utilisateurs récents --}}
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-user-friends"></i> Nouveaux utilisateurs</h3>
                <a href="{{ route('admin.users.index') }}" class="view-all">Voir tout <i class="fas fa-chevron-right"></i></a>
            </div>
            <div class="card-body">
                @forelse($recentUsers as $user)
                    <div class="list-item">
                        <div class="item-avatar">
                            @if($user->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}">
                            @else
                                <span class="avatar-initial">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                            @endif
                        </div>
                        <div class="item-info">
                            <span class="item-name">{{ $user->name }}</span>
                            <span class="item-meta">{{ $user->email }}</span>
                        </div>
                        <div class="item-badge">
                            @if($user->role === 'admin')
                                <span class="badge-admin">Admin</span>
                            @endif
                        </div>
                        <div class="item-date">
                            {{ $user->created_at->diffForHumans() }}
                        </div>
                    </div>
                @empty
                    <p class="empty-text">Aucun utilisateur récent</p>
                @endforelse
            </div>
        </div>

        {{-- Articles récents --}}
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-clock"></i> Articles récents</h3>
                <a href="{{ route('admin.articles.index') }}" class="view-all">Voir tout <i class="fas fa-chevron-right"></i></a>
            </div>
            <div class="card-body">
                @forelse($recentArticles as $article)
                    <div class="list-item">
                        <div class="item-image">
                            @if($article->images->first())
                                <img src="{{ asset('storage/' . $article->images->first()->path) }}" alt="{{ $article->title }}">
                            @else
                                <span class="no-image"><i class="fas fa-image"></i></span>
                            @endif
                        </div>
                        <div class="item-info">
                            <span class="item-name">{{ Str::limit($article->title, 25) }}</span>
                            <span class="item-meta">
                                {{ $article->user->name }} • 
                                <span class="price">{{ number_format($article->price, 0, ',', ' ') }} F</span>
                            </span>
                        </div>
                        <div class="item-badge">
                            @if($article->status === 'disponible')
                                <span class="badge-success">Disponible</span>
                            @elseif($article->status === 'vendu')
                                <span class="badge-danger">Vendu</span>
                            @else
                                <span class="badge-warning">Réservé</span>
                            @endif
                        </div>
                        <div class="item-date">
                            {{ $article->created_at->diffForHumans() }}
                        </div>
                    </div>
                @empty
                    <p class="empty-text">Aucun article récent</p>
                @endforelse
            </div>
        </div>

        {{-- Catégories populaires --}}
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-chart-bar"></i> Catégories populaires</h3>
            </div>
            <div class="card-body">
                @forelse($articlesByCategory as $category)
                    <div class="category-item">
                        <div class="category-info">
                            <span class="category-icon">{{ $category->icon }}</span>
                            <span class="category-name">{{ $category->name }}</span>
                        </div>
                        <div class="category-stats">
                            <span class="category-count">{{ $category->articles_count }} article(s)</span>
                            <div class="progress-bar">
                                @php
                                    $percentage = $totalArticles > 0 ? ($category->articles_count / $totalArticles) * 100 : 0;
                                @endphp
                                <div class="progress-fill" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="empty-text">Aucune catégorie</p>
                @endforelse
            </div>
        </div>

        {{-- Messages récents --}}
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-envelope"></i> Messages récents</h3>
            </div>
            <div class="card-body">
                @forelse($recentMessages as $message)
                    <div class="list-item">
                        <div class="item-avatar small">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="item-info">
                            <span class="item-name">
                                {{ $message->sender->name }} → {{ $message->receiver->name }}
                            </span>
                            <span class="item-meta">{{ Str::limit($message->content, 30) }}</span>
                        </div>
                        <div class="item-date">
                            {{ $message->created_at->diffForHumans() }}
                        </div>
                    </div>
                @empty
                    <p class="empty-text">Aucun message récent</p>
                @endforelse
            </div>
        </div>

    </div>

    {{-- Quick Actions --}}
    <div class="quick-actions">
        <h3><i class="fas fa-bolt"></i> Actions rapides</h3>
        <div class="actions-grid">
            <a href="{{ route('admin.users.create') }}" class="action-btn">
                <i class="fas fa-user-plus"></i>
                <span>Nouvel utilisateur</span>
            </a>
            <a href="{{ route('articles.create') }}" class="action-btn">
                <i class="fas fa-plus-circle"></i>
                <span>Nouvel article</span>
            </a>
            <a href="{{ route('admin.users.index') }}" class="action-btn">
                <i class="fas fa-users-cog"></i>
                <span>Gérer utilisateurs</span>
            </a>
            <a href="{{ route('admin.articles.index') }}" class="action-btn">
                <i class="fas fa-newspaper"></i>
                <span>Gérer articles</span>
            </a>
        </div>
    </div>
</div>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Données pour le graphique des utilisateurs
    const userData = @json($usersByMonth);
    const articleData = @json($articlesByMonth);
    
    const months = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'];
    
    // Préparer les données utilisateurs
    const userCounts = Array(12).fill(0);
    userData.forEach(item => {
        userCounts[item.month - 1] = item.count;
    });
    
    // Préparer les données articles
    const articleCounts = Array(12).fill(0);
    articleData.forEach(item => {
        articleCounts[item.month - 1] = item.count;
    });
    
    // Graphique utilisateurs
    new Chart(document.getElementById('usersChart'), {
        type: 'line',
        data: {
            labels: months,
            datasets: [{
                label: 'Inscriptions',
                data: userCounts,
                borderColor: '#4f46e5',
                backgroundColor: 'rgba(79, 70, 229, 0.1)',
                tension: 0.3,
                fill: true,
                pointBackgroundColor: '#4f46e5',
                pointBorderColor: 'white',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#f3f4f6' }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });
    
    // Graphique articles
    new Chart(document.getElementById('articlesChart'), {
        type: 'bar',
        data: {
            labels: months,
            datasets: [{
                label: 'Articles publiés',
                data: articleCounts,
                backgroundColor: '#10b981',
                borderRadius: 6,
                barPercentage: 0.6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#f3f4f6' }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });
});
</script>

<style>
.admin-dashboard {
    max-width: 1600px;
    margin: 0 auto;
    padding: 24px 32px;
}

/* Header */
.dashboard-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 28px;
}

.dashboard-title {
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

.dashboard-subtitle {
    color: #6b7280;
    font-size: 15px;
    margin: 0 0 0 52px;
}

.date-badge {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 20px;
    font-size: 14px;
    color: #6b7280;
}

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-bottom: 20px;
}

.stat-card {
    background: white;
    border-radius: 16px;
    padding: 20px;
    border: 1px solid #e5e7eb;
    transition: all 0.2s;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
}

.stat-card.blue .stat-icon { background: #e0e7ff; color: #4f46e5; }
.stat-card.green .stat-icon { background: #d1fae5; color: #059669; }
.stat-card.purple .stat-icon { background: #f3e8ff; color: #9333ea; }
.stat-card.orange .stat-icon { background: #fed7aa; color: #ea580c; }

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    margin-bottom: 16px;
}

.stat-value {
    font-size: 32px;
    font-weight: 700;
    color: #111827;
    line-height: 1.2;
}

.stat-label {
    display: block;
    font-size: 14px;
    color: #6b7280;
    margin-top: 4px;
    margin-bottom: 16px;
}

.stat-link {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    font-weight: 500;
    color: #4f46e5;
    text-decoration: none;
}

.stat-link:hover {
    text-decoration: underline;
}

/* Stats Secondary */
.stats-secondary {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 28px;
}

.stat-item {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 16px;
    display: flex;
    align-items: center;
    gap: 12px;
}

.stat-number {
    font-size: 24px;
    font-weight: 700;
    color: #111827;
}

.stat-text {
    flex: 1;
    font-size: 14px;
    color: #6b7280;
}

.stat-badge {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
}

.stat-badge.success { background: #d1fae5; }
.stat-badge.danger { background: #fee2e2; }
.stat-badge.warning { background: #fef3c7; }
.stat-badge.info { background: #dbeafe; }

/* Charts */
.charts-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 28px;
}

.chart-card {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 16px;
    overflow: hidden;
}

.chart-header {
    padding: 18px 20px;
    border-bottom: 1px solid #e5e7eb;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.chart-header h3 {
    display: flex;
    align-items: center;
    gap: 8px;
    margin: 0;
    font-size: 16px;
    font-weight: 600;
    color: #374151;
}

.chart-year {
    font-size: 13px;
    color: #9ca3af;
    background: #f3f4f6;
    padding: 4px 10px;
    border-radius: 20px;
}

.chart-body {
    padding: 20px;
    height: 250px;
}

/* Bottom Grid */
.bottom-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    margin-bottom: 28px;
}

.card {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 16px;
    overflow: hidden;
}

.card-header {
    padding: 16px 20px;
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
    font-size: 15px;
    font-weight: 600;
    color: #374151;
}

.view-all {
    font-size: 13px;
    color: #4f46e5;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 4px;
}

.view-all:hover {
    text-decoration: underline;
}

.card-body {
    padding: 8px 0;
}

.list-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 20px;
    border-bottom: 1px solid #f3f4f6;
}

.list-item:last-child {
    border-bottom: none;
}

.item-avatar {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    overflow: hidden;
    flex-shrink: 0;
}

.item-avatar.small {
    width: 36px;
    height: 36px;
    background: #f3f4f6;
    color: #6b7280;
}

.item-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.item-image {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    background: #f3f4f6;
    overflow: hidden;
    flex-shrink: 0;
}

.item-image img {
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

.item-info {
    flex: 1;
    min-width: 0;
}

.item-name {
    display: block;
    font-weight: 500;
    color: #111827;
    font-size: 14px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.item-meta {
    display: block;
    font-size: 12px;
    color: #6b7280;
    margin-top: 2px;
}

.price {
    font-weight: 600;
    color: #059669;
}

.item-badge .badge-admin {
    background: #fef2f2;
    color: #dc2626;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
}

.item-badge .badge-success {
    background: #d1fae5;
    color: #065f46;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
}

.item-badge .badge-danger {
    background: #fee2e2;
    color: #991b1b;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
}

.item-badge .badge-warning {
    background: #fef3c7;
    color: #92400e;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
}

.item-date {
    font-size: 12px;
    color: #9ca3af;
    flex-shrink: 0;
}

.empty-text {
    text-align: center;
    color: #9ca3af;
    padding: 24px;
    margin: 0;
}

/* Category Items */
.category-item {
    padding: 12px 20px;
    border-bottom: 1px solid #f3f4f6;
}

.category-item:last-child {
    border-bottom: none;
}

.category-info {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 8px;
}

.category-icon {
    font-size: 18px;
}

.category-name {
    font-size: 14px;
    font-weight: 500;
    color: #374151;
}

.category-stats {
    display: flex;
    align-items: center;
    gap: 12px;
}

.category-count {
    font-size: 13px;
    color: #6b7280;
    min-width: 80px;
}

.progress-bar {
    flex: 1;
    height: 6px;
    background: #e5e7eb;
    border-radius: 3px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #4f46e5 0%, #9333ea 100%);
    border-radius: 3px;
    transition: width 0.3s;
}

/* Quick Actions */
.quick-actions {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 16px;
    padding: 20px;
}

.quick-actions h3 {
    display: flex;
    align-items: center;
    gap: 8px;
    margin: 0 0 16px 0;
    font-size: 16px;
    font-weight: 600;
    color: #374151;
}

.actions-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 12px;
}

.action-btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    padding: 20px;
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    text-decoration: none;
    transition: all 0.2s;
}

.action-btn:hover {
    background: white;
    border-color: #4f46e5;
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.1);
}

.action-btn i {
    font-size: 24px;
    color: #4f46e5;
}

.action-btn span {
    font-size: 14px;
    font-weight: 500;
    color: #374151;
}

/* Responsive */
@media (max-width: 1200px) {
    .stats-grid { grid-template-columns: repeat(2, 1fr); }
    .stats-secondary { grid-template-columns: repeat(2, 1fr); }
    .bottom-grid { grid-template-columns: 1fr; }
    .actions-grid { grid-template-columns: repeat(2, 1fr); }
}

@media (max-width: 768px) {
    .admin-dashboard { padding: 16px; }
    .stats-grid { grid-template-columns: 1fr; }
    .stats-secondary { grid-template-columns: 1fr; }
    .charts-row { grid-template-columns: 1fr; }
    .actions-grid { grid-template-columns: 1fr; }
    .dashboard-header { flex-direction: column; gap: 12px; }
}
</style>
@endsection