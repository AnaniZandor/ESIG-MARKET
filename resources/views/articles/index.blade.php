@extends('layouts.app')

@section('title', 'Accueil')

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

        {{-- BARRE DE RECHERCHE --}}
        <form action="{{ route('articles.index') }}" method="GET"
              class="hero-search" id="search-form-hero">
            <input type="text"
                   name="search"
                   id="search-input-hero"
                   placeholder="Rechercher un article..."
                   value="{{ request('search') }}"
                   oninput="handleSearch(this, 'search-form-hero')">
            <button type="submit">
                <i class="fas fa-search"></i>
                Rechercher
            </button>
        </form>

        <div class="hero-stats">
            <div class="hero-stat">
                <div class="hero-stat-num">{{ $articles->total() }}</div>
                <div class="hero-stat-label">Articles disponibles</div>
            </div>
            <div class="hero-stat">
                <div class="hero-stat-num">100%</div>
                <div class="hero-stat-label">Entre étudiants</div>
            </div>
            <div class="hero-stat">
                <div class="hero-stat-num">0 FCFA</div>
                <div class="hero-stat-label">Commission</div>
            </div>
        </div>
    </div>
</div>

{{-- CONTENU PRINCIPAL --}}
<div style="padding: 40px 0;">

    {{-- FILTRES CATÉGORIES --}}
    <div class="categories-bar">
        <a href="{{ route('articles.index') }}"
           class="category-pill {{ !request('category') ? 'active' : '' }}">
            <i class="fas fa-th"></i> Tout
        </a>
        @foreach($categories as $cat)
            <a href="{{ route('articles.index', ['category' => $cat->slug]) }}"
               class="category-pill {{ request('category') == $cat->slug ? 'active' : '' }}">
                {{ $cat->icon }} {{ $cat->name }}
            </a>
        @endforeach
    </div>

    {{-- TITRE SECTION --}}
    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:24px;">
        <h2 class="section-title" style="margin-bottom:0;">
            {{ request('search') ? 'Résultats pour "'.request('search').'"' : 'Articles récents' }}
        </h2>
        @auth
        <a href="{{ route('articles.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            Vendre un article
        </a>
        @endauth
    </div>

    {{-- GRILLE ARTICLES --}}
    @if($articles->count() > 0)
    <div class="grid-articles">
        @foreach($articles as $article)
        <div class="article-card" data-aos="fade-up">

            {{-- IMAGE --}}
            <div class="article-card-img">

                {{-- LIEN VERS L'ARTICLE --}}
                <a href="{{ route('articles.show', $article->id) }}">
                    @if($article->images && $article->images->first())
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

                {{-- BOUTON FAVORI ✅ --}}
                @auth
                @php
                    $isFavorite = $userFavorites->contains($article->id);
                @endphp
                <form action="{{ route('favorites.toggle', $article->id) }}"
                      method="POST"
                      style="position:absolute; top:12px; right:12px;">
                    @csrf
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
                            @case('neuf')       ✨ Neuf @break
                            @case('tres_bon')   👍 Très bon @break
                            @case('bon')        👌 Bon état @break
                            @case('acceptable') ⚠️ Acceptable @break
                            @default {{ $article->condition }}
                        @endswitch
                    </span>
                </div>

            </div>

        </div>
        @endforeach
    </div>

    {{-- PAGINATION --}}
    <div style="margin-top:40px; display:flex; justify-content:center;">
        {{ $articles->links() }}
    </div>

    @else
    <div class="empty-state">
        <i class="fas fa-box-open"></i>
        <h3>Aucun article trouvé</h3>
        <p>
            {{ request('search')
                ? 'Essaie avec d\'autres mots-clés.'
                : 'Sois le premier à publier une annonce !' }}
        </p>
        @auth
        <a href="{{ route('articles.create') }}" class="btn btn-primary" style="margin-top:16px;">
            <i class="fas fa-plus"></i> Publier un article
        </a>
        @endauth
    </div>
    @endif

</div>

@endsection