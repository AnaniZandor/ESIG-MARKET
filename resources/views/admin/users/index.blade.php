@extends('layouts.app')

@section('title', 'Gestion des utilisateurs')

@section('content')
<div class="admin-users-container">
    
    {{-- Header Section --}}
    <div class="page-header">
        <div class="header-content">
            <div class="title-section">
                <h1 class="page-title">
                    <span class="icon-badge">
                        <i class="fas fa-users"></i>
                    </span>
                    Utilisateurs
                </h1>
                <p class="page-subtitle">Gérez les comptes et les permissions des utilisateurs</p>
            </div>
            <a href="{{ route('admin.users.create') }}" class="btn-primary">
                <i class="fas fa-plus"></i>
                <span>Nouvel utilisateur</span>
            </a>
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

    @if(session('error'))
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            <span>{{ session('error') }}</span>
            <button type="button" class="alert-close" onclick="this.parentElement.remove()">×</button>
        </div>
    @endif

    {{-- Stats Overview --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon primary">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-info">
                <span class="stat-label">Total utilisateurs</span>
                <span class="stat-value">{{ $users->total() }}</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon danger">
                <i class="fas fa-user-shield"></i>
            </div>
            <div class="stat-info">
                <span class="stat-label">Administrateurs</span>
                <span class="stat-value">
                    @php
                        $adminCount = $users->filter(fn($u) => $u->role === 'admin')->count();
                    @endphp
                    {{ $adminCount }}
                </span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon success">
                <i class="fas fa-user-check"></i>
            </div>
            <div class="stat-info">
                <span class="stat-label">Actifs</span>
                <span class="stat-value">
                    @php
                        $activeCount = $users->filter(fn($u) => $u->is_active)->count();
                    @endphp
                    {{ $activeCount }}
                </span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon warning">
                <i class="fas fa-user-clock"></i>
            </div>
            <div class="stat-info">
                <span class="stat-label">Inactifs</span>
                <span class="stat-value">
                    @php
                        $inactiveCount = $users->filter(fn($u) => !$u->is_active)->count();
                    @endphp
                    {{ $inactiveCount }}
                </span>
            </div>
        </div>
    </div>

    {{-- Filters Bar --}}
    <div class="filters-bar">
        <form method="GET" action="{{ route('admin.users.index') }}" class="filters-form">
            <div class="search-wrapper">
                <i class="fas fa-search search-icon"></i>
                <input type="text" 
                       name="search" 
                       class="search-input" 
                       placeholder="Rechercher par nom ou email..."
                       value="{{ request('search') }}">
            </div>

            <div class="filter-group">
                <select name="role" class="filter-select">
                    <option value="">Tous les rôles</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Administrateurs</option>
                    <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>Utilisateurs</option>
                </select>

                <select name="status" class="filter-select">
                    <option value="">Tous les statuts</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actifs</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactifs</option>
                </select>

                <button type="submit" class="btn-filter">
                    <i class="fas fa-filter"></i>
                    Filtrer
                </button>

                @if(request()->anyFilled(['search', 'role', 'status']))
                    <a href="{{ route('admin.users.index') }}" class="btn-reset">
                        <i class="fas fa-times"></i>
                        Réinitialiser
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Users Table --}}
    <div class="table-container">
        <div class="table-header">
            <div class="results-count">
                <i class="fas fa-list-ul"></i>
                <span>{{ $users->total() }} utilisateur(s) trouvé(s)</span>
            </div>
        </div>

        <table class="users-table">
            <thead>
                <tr>
                    <th class="col-id">ID</th>
                    <th class="col-user">Utilisateur</th>
                    <th class="col-contact">Contact</th>
                    <th class="col-role">Rôle</th>
                    <th class="col-status">Statut</th>
                    <th class="col-date">Inscription</th>
                    <th class="col-actions">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr class="user-row {{ !$user->is_active ? 'inactive' : '' }}">
                        {{-- ID --}}
                        <td class="col-id">
                            <span class="user-id">#{{ $user->id }}</span>
                        </td>

                        {{-- Utilisateur --}}
                        <td class="col-user">
                            <div class="user-info">
                                <div class="user-avatar">
                                    @if($user->avatar)
                                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}">
                                    @else
                                        <span class="avatar-initial">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </span>
                                    @endif
                                </div>
                                <div class="user-details">
                                    <span class="user-name">{{ $user->name }}</span>
                                    @if($user->filiere)
                                        <span class="user-meta">
                                            <i class="fas fa-graduation-cap"></i>
                                            {{ $user->filiere }}
                                        </span>
                                    @endif
                                    @if($user->numero_etudiant)
                                        <span class="user-meta">
                                            <i class="fas fa-id-card"></i>
                                            {{ $user->numero_etudiant }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </td>

                        {{-- Contact --}}
                        <td class="col-contact">
                            <div class="contact-info">
                                <a href="mailto:{{ $user->email }}" class="user-email">
                                    <i class="fas fa-envelope"></i>
                                    {{ $user->email }}
                                </a>
                                @if($user->email_verified_at)
                                    <span class="verified-badge" title="Email vérifié">
                                        <i class="fas fa-check-circle"></i>
                                    </span>
                                @endif
                            </div>
                        </td>

                        {{-- Rôle --}}
                        <td class="col-role">
                            @if($user->role === 'admin')
                                <span class="badge badge-admin">
                                    <i class="fas fa-crown"></i>
                                    Admin
                                </span>
                            @else
                                <span class="badge badge-user">
                                    <i class="fas fa-user"></i>
                                    User
                                </span>
                            @endif
                        </td>

                        {{-- Statut --}}
                        <td class="col-status">
                            @if($user->is_active)
                                <span class="badge badge-active">
                                    <span class="status-dot"></span>
                                    Actif
                                </span>
                            @else
                                <span class="badge badge-inactive">
                                    <span class="status-dot"></span>
                                    Inactif
                                </span>
                            @endif
                        </td>

                        {{-- Date --}}
                        <td class="col-date">
                            <div class="date-info">
                                <span class="date-full">{{ $user->created_at->format('d/m/Y') }}</span>
                                <span class="date-relative">{{ $user->created_at->diffForHumans() }}</span>
                            </div>
                        </td>

                        {{-- Actions --}}
                        <td class="col-actions">
                            <div class="action-buttons">
                                <a href="{{ route('admin.users.show', $user) }}" 
                                   class="action-btn view" 
                                   title="Voir le profil">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                <a href="{{ route('admin.users.edit', $user) }}" 
                                   class="action-btn edit" 
                                   title="Modifier">
                                    <i class="fas fa-pen"></i>
                                </a>

                                @if($user->id !== auth()->id())
                                    <form action="{{ route('admin.users.toggle-active', $user) }}" 
                                          method="POST" 
                                          class="action-form">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                class="action-btn {{ $user->is_active ? 'deactivate' : 'activate' }}" 
                                                title="{{ $user->is_active ? 'Désactiver' : 'Activer' }}">
                                            <i class="fas fa-{{ $user->is_active ? 'ban' : 'check' }}"></i>
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.users.destroy', $user) }}" 
                                          method="POST" 
                                          class="action-form"
                                          onsubmit="return confirmDelete('{{ $user->name }}', '{{ $user->email }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="action-btn delete" 
                                                title="Supprimer">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr class="empty-row">
                        <td colspan="7">
                            <div class="empty-state">
                                <i class="fas fa-user-slash"></i>
                                <h3>Aucun utilisateur</h3>
                                <p>Commencez par créer votre premier utilisateur</p>
                                <a href="{{ route('admin.users.create') }}" class="btn-primary">
                                    <i class="fas fa-plus"></i>
                                    Créer un utilisateur
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pagination --}}
        @if($users->hasPages())
            <div class="pagination-wrapper">
                <div class="pagination-info">
                    {{ $users->firstItem() }} - {{ $users->lastItem() }} sur {{ $users->total() }}
                </div>
                {{ $users->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>

{{-- Delete Confirmation Script --}}
<script>
function confirmDelete(name, email) {
    return confirm(`Êtes-vous sûr de vouloir supprimer définitivement cet utilisateur ?\n\n${name}\n${email}\n\nCette action est irréversible.`);
}
</script>

{{-- Custom Styles --}}
<style>
/* Container */
.admin-users-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 24px 32px;
}

/* Page Header */
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

.page-title {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 28px;
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

/* Buttons */
.btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    background: #4f46e5;
    color: white;
    border-radius: 8px;
    font-weight: 500;
    font-size: 14px;
    text-decoration: none;
    transition: all 0.2s;
    border: none;
    cursor: pointer;
}

.btn-primary:hover {
    background: #4338ca;
    color: white;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
}

/* Alerts */
.alert {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 18px;
    border-radius: 10px;
    margin-bottom: 24px;
    position: relative;
}

.alert-success {
    background: #f0fdf4;
    border: 1px solid #86efac;
    color: #166534;
}

.alert-error {
    background: #fef2f2;
    border: 1px solid #fca5a5;
    color: #991b1b;
}

.alert i {
    font-size: 18px;
}

.alert-close {
    margin-left: auto;
    background: none;
    border: none;
    font-size: 22px;
    cursor: pointer;
    opacity: 0.6;
    color: inherit;
    padding: 0 4px;
}

.alert-close:hover {
    opacity: 1;
}

/* Stats Grid */
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
    transition: all 0.2s;
}

.stat-card:hover {
    border-color: #d1d5db;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
}

.stat-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 48px;
    height: 48px;
    border-radius: 10px;
    font-size: 20px;
}

.stat-icon.primary {
    background: #e0e7ff;
    color: #4f46e5;
}

.stat-icon.danger {
    background: #fee2e2;
    color: #dc2626;
}

.stat-icon.success {
    background: #d1fae5;
    color: #059669;
}

.stat-icon.warning {
    background: #fed7aa;
    color: #ea580c;
}

.stat-info {
    display: flex;
    flex-direction: column;
}

.stat-label {
    font-size: 13px;
    color: #6b7280;
    margin-bottom: 4px;
}

.stat-value {
    font-size: 28px;
    font-weight: 700;
    color: #111827;
    line-height: 1.2;
}

/* Filters Bar */
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
}

.search-wrapper {
    position: relative;
    flex: 1;
    max-width: 360px;
}

.search-icon {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: #9ca3af;
    font-size: 14px;
}

.search-input {
    width: 100%;
    padding: 10px 14px 10px 40px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    font-size: 14px;
    transition: all 0.2s;
}

.search-input:focus {
    outline: none;
    border-color: #4f46e5;
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
}

.filter-group {
    display: flex;
    align-items: center;
    gap: 10px;
}

.filter-select {
    padding: 10px 14px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    font-size: 14px;
    background: white;
    cursor: pointer;
    min-width: 150px;
}

.filter-select:focus {
    outline: none;
    border-color: #4f46e5;
}

.btn-filter {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 10px 18px;
    background: #f3f4f6;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    color: #374151;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-filter:hover {
    background: #e5e7eb;
}

.btn-reset {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 10px 18px;
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    font-size: 14px;
    color: #6b7280;
    text-decoration: none;
    transition: all 0.2s;
}

.btn-reset:hover {
    background: #f9fafb;
    color: #374151;
}

/* Table Container */
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

/* Users Table */
.users-table {
    width: 100%;
    border-collapse: collapse;
}

.users-table thead tr {
    background: #f9fafb;
    border-bottom: 1px solid #e5e7eb;
}

.users-table th {
    padding: 12px 16px;
    text-align: left;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #6b7280;
}

.users-table tbody tr {
    border-bottom: 1px solid #f3f4f6;
    transition: background 0.2s;
}

.users-table tbody tr:hover {
    background: #fafafa;
}

.users-table tbody tr.inactive {
    opacity: 0.7;
}

.users-table td {
    padding: 16px;
    vertical-align: middle;
}

/* Table Cells */
.col-id {
    width: 80px;
}

.user-id {
    font-weight: 600;
    color: #6b7280;
    font-size: 13px;
}

.col-user {
    min-width: 250px;
}

.user-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    overflow: hidden;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    flex-shrink: 0;
}

.user-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.avatar-initial {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
    color: white;
    font-weight: 600;
    font-size: 16px;
}

.user-details {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.user-name {
    font-weight: 600;
    color: #111827;
    font-size: 14px;
}

.user-meta {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    color: #6b7280;
}

.user-meta i {
    font-size: 11px;
    width: 14px;
}

/* Contact */
.contact-info {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

.user-email {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: #4f46e5;
    text-decoration: none;
    font-size: 13px;
}

.user-email:hover {
    text-decoration: underline;
}

.verified-badge {
    color: #059669;
    font-size: 14px;
}

/* Badges */
.badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
    white-space: nowrap;
}

.badge-admin {
    background: #fef2f2;
    color: #dc2626;
    border: 1px solid #fecaca;
}

.badge-user {
    background: #f3f4f6;
    color: #4b5563;
    border: 1px solid #e5e7eb;
}

.badge-active {
    background: #f0fdf4;
    color: #166534;
    border: 1px solid #bbf7d0;
}

.badge-inactive {
    background: #fef3c7;
    color: #92400e;
    border: 1px solid #fde68a;
}

.status-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: currentColor;
}

/* Date */
.date-info {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.date-full {
    font-size: 13px;
    color: #374151;
}

.date-relative {
    font-size: 11px;
    color: #9ca3af;
}

/* Actions */
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
    font-size: 14px;
    cursor: pointer;
    transition: all 0.2s;
    text-decoration: none;
}

.action-btn:hover {
    background: #f3f4f6;
    color: #374151;
}

.action-btn.view:hover {
    color: #3b82f6;
}

.action-btn.edit:hover {
    color: #8b5cf6;
}

.action-btn.activate:hover {
    color: #059669;
}

.action-btn.deactivate:hover {
    color: #ea580c;
}

.action-btn.delete:hover {
    color: #dc2626;
}

.action-form {
    display: inline;
}

/* Empty State */
.empty-row td {
    padding: 60px !important;
}

.empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
}

.empty-state i {
    font-size: 48px;
    color: #d1d5db;
    margin-bottom: 16px;
}

.empty-state h3 {
    font-size: 18px;
    font-weight: 600;
    color: #374151;
    margin: 0 0 8px 0;
}

.empty-state p {
    color: #6b7280;
    font-size: 14px;
    margin: 0 0 20px 0;
}

/* Pagination */
.pagination-wrapper {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 20px;
    border-top: 1px solid #e5e7eb;
}

.pagination-info {
    font-size: 14px;
    color: #6b7280;
}

/* Responsive */
@media (max-width: 1200px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .filters-form {
        flex-direction: column;
        align-items: stretch;
    }
    
    .search-wrapper {
        max-width: 100%;
    }
    
    .filter-group {
        flex-wrap: wrap;
    }
}

@media (max-width: 768px) {
    .admin-users-container {
        padding: 16px;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .header-content {
        flex-direction: column;
        gap: 16px;
    }
    
    .btn-primary {
        width: 100%;
        justify-content: center;
    }
    
    .users-table {
        display: block;
        overflow-x: auto;
    }
}
</style>
@endsection