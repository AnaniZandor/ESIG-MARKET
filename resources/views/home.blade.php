@extends('layouts.app')

@section('title', 'Accueil — VintageESIG')

@section('content')

{{-- HERO --}}
<div class="hero">
    <div class="hero-content">
        <div class="hero-badge">
            <i class="fas fa-store"></i>
            Marketplace de l'ESIG
        </div>
        <h1>Achetez & vendez<br><em>entre étudiants</em></h1>
        <p>Des centaines d'articles de seconde main à petit prix, directement entre étudiants de l'ESIG.</p>

        <form action="{{ route('articles.index') }}" method="GET" class="hero-search">
            <input type="text" name="search"
                   placeholder="Rechercher un article..."
                   value="{{ request('search') }}">
            <button type="submit">
                <i class="fas fa-search"></i>
                Rechercher
            </button>
        </form>

        <div class="hero-stats">
            <div class="hero-stat">
                <div class="hero-stat-num">{{ \App\Models\Article::where('status','disponible')->count() }}</div>
                <div class="hero-stat-label">Articles disponibles</div>
            </div>
            <div class="hero-stat">
                <div class="hero-stat-num">{{ \App\Models\User::count() }}</div>
                <div class="hero-stat-label">Étudiants inscrits</div>
            </div>
            <div class="hero-stat">
                <div class="hero-stat-num">0 FCFA</div>
                <div class="hero-stat-label">Commission</div>
            </div>
        </div>
    </div>
</div>

{{-- CONTENU --}}
<div style="padding: 40px 0;">

    {{-- TITRE --}}
    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:24px;">
        <h2 class="section-title" style="margin-bottom:0;">
            Articles récents
        </h2>
        <a href="{{ route('articles.index') }}" class="btn btn-outline">
            Voir tout <i class="fas fa-arrow-right"></i>
        </a>
    </div>

    {{-- GRILLE --}}
    @if($articles->count() > 0)
    <div class="grid-articles">
        @foreach($articles as $article)
        <div class="article-card">

            {{-- IMAGE --}}
            <div class="article-card-img">
                <a href="{{ route('articles.show', $article->id) }}">
                    @if($article->images && $article->images->first())
                        {{-- ✅ path au lieu de image_path --}}
                        <img src="{{ asset('storage/'.$article->images->first()->path) }}"
                             alt="{{ $article->title }}">
                    @else
                        <div class="no-img">
                            <i class="fas fa-image"></i>
                        </div>
                    @endif
                </a>

                {{-- BADGE STATUT --}}
                @if($article->status !== 'disponible')
                    <span class="badge-status {{ $article->status }}">
                        {{ $article->status === 'vendu' ? '✓ Vendu' : '⚠ Suspendu' }}
                    </span>
                @endif

            
            {{-- FAVORI --}}
@auth
<form action="{{ route('favorites.toggle', $article->id) }}" method="POST"
      style="position:absolute; top:12px; right:12px;">
    @csrf
    @php
        $isFavorite = auth()->user()->favorites->contains($article->id);
    @endphp
    <button type="submit"
            class="btn-favorite {{ $isFavorite ? 'active' : '' }}"
            title="{{ $isFavorite ? 'Retirer des favoris' : 'Ajouter aux favoris' }}">
        <i class="{{ $isFavorite ? 'fas' : 'far' }} fa-heart"></i>
    </button>
</form>
@endauth
            </div>
            {{-- INFOS --}}
            <div class="article-card-body">
                <div class="article-card-category">
                    {{ $article->category->name ?? 'Sans catégorie' }}
                </div>
                <div class="article-card-title">
                    {{ $article->title }}
                </div>
                <div class="article-card-price">
                    {{ number_format($article->price, 0, ',', ' ') }}
                    <span>FCFA</span>
                </div>
                <div class="article-card-meta">
                    <div class="article-card-seller">
                        <div class="seller-avatar-sm">
                            {{ strtoupper(substr($article->user->name, 0, 1)) }}
                        </div>
                        {{ $article->user->name }}
                    </div>
                    <span class="article-card-condition">
                        @switch($article->condition)
                            @case('neuf')      ✨ Neuf @break
                            @case('tres_bon')  👍 Très bon @break
                            @case('bon')       👌 Bon état @break
                            @case('acceptable') ⚠️ Acceptable @break
                            @default {{ $article->condition }}
                        @endswitch
                    </span>
                </div>
            </div>

        </div>
        @endforeach
    </div>

    {{-- CALL TO ACTION --}}
    <div style="text-align:center; margin-top:48px; padding:40px; background:var(--secondary-light); border-radius:var(--radius-lg);">
        <h3 style="font-family:'Playfair Display',serif; font-size:24px; margin-bottom:8px;">
            Tu as des articles à vendre ?
        </h3>
        <p style="color:var(--text-mid); margin-bottom:20px;">
            Publie ton annonce gratuitement en moins de 2 minutes !
        </p>
        @auth
            <a href="{{ route('articles.create') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-plus"></i> Publier une annonce
            </a>
        @else
            <a href="{{ route('register') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-user-plus"></i> Créer un compte gratuitement
            </a>
        @endauth
    </div>

    @else
    <div class="empty-state">
        <i class="fas fa-box-open"></i>
        <h3>Aucun article disponible</h3>
        <p>Sois le premier à publier une annonce !</p>
        @auth
        <a href="{{ route('articles.create') }}" class="btn btn-primary" style="margin-top:16px;">
            <i class="fas fa-plus"></i> Publier un article
        </a>
        @endauth
    </div>
    @endif

</div>

@endsection