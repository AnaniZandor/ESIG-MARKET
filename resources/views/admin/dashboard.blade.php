@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')

<div style="padding: 28px 0;">

    {{-- EN-TÊTE --}}
    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:32px;">
        <div>
            <h1 style="font-family:'Playfair Display',serif; font-size:28px; margin-bottom:4px;">
                🛡️ Panneau d'administration
            </h1>
            <p style="color:var(--text-light); font-size:14px;">
                Bienvenue, {{ auth()->user()->name }} — Voici l'état de la plateforme
            </p>
        </div>
        <span class="badge badge-danger" style="font-size:13px; padding:6px 14px;">
            <i class="fas fa-shield-halved"></i> Admin
        </span>
    </div>

    {{-- STATS GLOBALES --}}
    <div class="stats-grid" style="margin-bottom:32px;">

        <div class="stat-card">
            <div class="stat-icon info">
                <i class="fas fa-users"></i>
            </div>
            <div>
                <div class="stat-num">{{ $users }}</div>
                <div class="stat-label">Utilisateurs</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon primary">
                <i class="fas fa-box"></i>
            </div>
            <div>
                <div class="stat-num">{{ $articles }}</div>
                <div class="stat-label">Articles publiés</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon success">
                <i class="fas fa-comment"></i>
            </div>
            <div>
                <div class="stat-num">{{ $messages }}</div>
                <div class="stat-label">Messages échangés</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon warning">
                <i class="fas fa-tag"></i>
            </div>
            <div>
                <div class="stat-num">{{ $categories }}</div>
                <div class="stat-label">Catégories</div>
            </div>
        </div>

    </div>

    {{-- ACTIONS RAPIDES --}}
    <h2 class="section-title">Actions rapides</h2>

    <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(220px,1fr)); gap:16px; margin-bottom:40px;">

        <a href="{{ route('admin.users.index') }}"
           style="background:white; border:1px solid var(--border); border-radius:var(--radius-lg); padding:20px; display:flex; align-items:center; gap:16px; transition:var(--transition); text-decoration:none; color:inherit;">
            <div class="stat-icon info" style="flex-shrink:0;">
                <i class="fas fa-users"></i>
            </div>
            <div>
                <div style="font-weight:600; font-size:15px;">Gérer les utilisateurs</div>
                <div style="font-size:13px; color:var(--text-light);">Voir, désactiver, supprimer</div>
            </div>
        </a>

        <a href="{{ route('admin.articles.index') }}"
           style="background:white; border:1px solid var(--border); border-radius:var(--radius-lg); padding:20px; display:flex; align-items:center; gap:16px; transition:var(--transition); text-decoration:none; color:inherit;">
            <div class="stat-icon primary" style="flex-shrink:0;">
                <i class="fas fa-box"></i>
            </div>
            <div>
                <div style="font-weight:600; font-size:15px;">Gérer les annonces</div>
                <div style="font-size:13px; color:var(--text-light);">Modérer, suspendre, supprimer</div>
            </div>
        </a>

        <a href="{{ route('admin.reports.index') }}"
           style="background:white; border:1px solid var(--border); border-radius:var(--radius-lg); padding:20px; display:flex; align-items:center; gap:16px; transition:var(--transition); text-decoration:none; color:inherit;">
            <div class="stat-icon warning" style="flex-shrink:0;">
                <i class="fas fa-flag"></i>
            </div>
            <div>
                <div style="font-weight:600; font-size:15px;">Signalements</div>
                <div style="font-size:13px; color:var(--text-light);">Traiter les signalements</div>
            </div>
        </a>

        <a href="{{ route('admin.messages.recent') }}"
           style="background:white; border:1px solid var(--border); border-radius:var(--radius-lg); padding:20px; display:flex; align-items:center; gap:16px; transition:var(--transition); text-decoration:none; color:inherit;">
            <div class="stat-icon success" style="flex-shrink:0;">
                <i class="fas fa-comment"></i>
            </div>
            <div>
                <div style="font-weight:600; font-size:15px;">Messages récents</div>
                <div style="font-size:13px; color:var(--text-light);">Surveiller les échanges</div>
            </div>
        </a>

    </div>

    {{-- DERNIERS UTILISATEURS --}}
    <h2 class="section-title">Derniers utilisateurs inscrits</h2>

    <div class="data-table-wrapper" style="margin-bottom:32px;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Utilisateur</th>
                    <th>Email</th>
                    <th>Filière</th>
                    <th>Rôle</th>
                    <th>Inscrit le</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach(\App\Models\User::latest()->take(5)->get() as $user)
                <tr>
                    <td>
                        <div style="display:flex; align-items:center; gap:10px;">
                            <div class="seller-avatar-sm">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <span style="font-weight:500;">{{ $user->name }}</span>
                        </div>
                    </td>
                    <td style="color:var(--text-mid);">{{ $user->email }}</td>
                    <td style="color:var(--text-mid);">{{ $user->filiere ?? '—' }}</td>
                    <td>
                        <span class="badge {{ $user->role === 'admin' ? 'badge-danger' : 'badge-neutral' }}">
                            {{ $user->role }}
                        </span>
                    </td>
                    <td style="color:var(--text-light); font-size:13px;">
                        {{ $user->created_at->format('d/m/Y') }}
                    </td>
                    <td>
                        @if($user->role !== 'admin')
                        <form action="{{ route('admin.users.destroy', $user->id) }}"
                              method="POST"
                              onsubmit="return confirm('Supprimer cet utilisateur ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                        @else
                        <span style="font-size:12px; color:var(--text-light);">Protégé</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- DERNIERS ARTICLES --}}
    <h2 class="section-title">Dernières annonces publiées</h2>

    <div class="data-table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Vendeur</th>
                    <th>Catégorie</th>
                    <th>Prix</th>
                    <th>Statut</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach(\App\Models\Article::with(['user','category'])->latest()->take(8)->get() as $art)
                <tr>
                    <td style="font-weight:500;">{{ Str::limit($art->title, 30) }}</td>
                    <td style="color:var(--text-mid);">{{ $art->user->name }}</td>
                    <td style="color:var(--text-mid);">{{ $art->category->name ?? '—' }}</td>
                    <td style="font-weight:600; color:var(--primary);">
                        {{ number_format($art->price, 0, ',', ' ') }} FCFA
                    </td>
                    <td>
                        <span class="badge {{ $art->status === 'disponible' ? 'badge-success' : ($art->status === 'vendu' ? 'badge-neutral' : 'badge-warning') }}">
                            {{ $art->status }}
                        </span>
                    </td>
                    <td style="color:var(--text-light); font-size:13px;">
                        {{ $art->created_at->format('d/m/Y') }}
                    </td>
                    <td>
                        <div style="display:flex; gap:6px;">
                            {{-- Suspendre --}}
                            @if($art->status !== 'suspendu')
                            <form action="{{ route('admin.articles.suspend', $art->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm"
                                        style="background:var(--warning-bg);color:var(--warning);border:none;"
                                        title="Suspendre">
                                    <i class="fas fa-ban"></i>
                                </button>
                            </form>
                            @endif
                            {{-- Supprimer --}}
                            <form action="{{ route('admin.articles.destroy', $art->id) }}"
                                  method="POST"
                                  onsubmit="return confirm('Supprimer cette annonce ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                        title="Supprimer">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>

@endsection