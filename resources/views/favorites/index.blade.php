@extends('layouts.app')

@section('title', 'Mes Favoris')

@section('content')

<div style="padding: 32px 0;">

    {{-- EN-TÊTE --}}
    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:28px;">
        <h1 style="font-family:'Playfair Display',serif; font-size:28px;">
            ❤️ Mes Favoris
        </h1>
        <span style="font-size:14px; color:var(--text-light);">
            {{ $favorites->count() }} article(s) sauvegardé(s)
        </span>
    </div>

    @if($favorites->count() > 0)

        <div class="grid-articles">
            @foreach($favorites as $article)
            <div class="article-card">

                {{-- IMAGE --}}
                <div class="article-card-img">
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

                    {{-- BOUTON RETIRER DES FAVORIS --}}
                    <form action="{{ route('favorites.toggle', $article->id) }}" method="POST"
                          style="position:absolute; top:12px; right:12px;">
                        @csrf
                        <button type="submit" class="btn-favorite active" title="Retirer des favoris">
                            <i class="fas fa-heart"></i>
                        </button>
                    </form>
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
                        <a href="{{ route('articles.show', $article->id) }}"
                           class="btn btn-primary btn-sm">
                            Voir
                        </a>
                    </div>
                </div>

            </div>
            @endforeach
        </div>

    @else

        {{-- ÉTAT VIDE --}}
        <div class="favorites-empty">
            <i class="fas fa-heart-crack"></i>
            <h3>Aucun favori pour l'instant</h3>
            <p>Parcours les annonces et sauvegarde celles qui t'intéressent !</p>
            <a href="{{ route('articles.index') }}" class="btn btn-primary">
                <i class="fas fa-store"></i>
                Parcourir les annonces
            </a>
        </div>

    @endif

</div>

@endsection