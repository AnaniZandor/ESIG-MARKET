@extends('layouts.app')

@section('title', $article->title)

@section('content')

{{-- FIL D'ARIANE --}}
<div style="padding: 16px 0; font-size:13px; color:var(--text-light); display:flex; align-items:center; gap:8px;">
    <a href="{{ route('articles.index') }}" style="color:var(--text-light);">Accueil</a>
    <i class="fas fa-chevron-right" style="font-size:10px;"></i>
    <span style="color:var(--text-mid);">{{ $article->category->name ?? 'Articles' }}</span>
    <i class="fas fa-chevron-right" style="font-size:10px;"></i>
    <span style="color:var(--text-dark); font-weight:500;">{{ Str::limit($article->title, 40) }}</span>
</div>

{{-- CONTENU PRINCIPAL --}}
<div class="article-detail">

    {{-- COLONNE GAUCHE : GALERIE --}}
    <div class="article-gallery">

        {{-- IMAGE PRINCIPALE --}}
        <div class="gallery-main">
            @if($article->images && $article->images->first())
                <img src="{{ asset('storage/'.$article->images->first()->path) }}"
                     alt="{{ $article->title }}"
                     id="main-img">
            @else
                <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:var(--bg);color:var(--text-light);flex-direction:column;gap:12px;">
                    <i class="fas fa-image" style="font-size:56px;"></i>
                    <span style="font-size:14px;">Aucune photo disponible</span>
                </div>
            @endif
        </div>

        {{-- MINIATURES --}}
        @if($article->images && $article->images->count() > 1)
        <div class="gallery-thumbs">
            @foreach($article->images as $i => $image)
            <div class="gallery-thumb {{ $i === 0 ? 'active' : '' }}"
                 onclick="switchImage('{{ asset('storage/'.$image->path) }}', this)">
                <img src="{{ asset('storage/'.$image->path) }}" alt="Photo {{ $i+1 }}">
            </div>
            @endforeach
        </div>
        @endif

        {{-- INFOS SUPPLÉMENTAIRES --}}
        <div style="background:var(--bg);border-radius:var(--radius);padding:16px;border:1px solid var(--border);">
            <div style="font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;color:var(--text-light);margin-bottom:12px;">
                Détails de l'annonce
            </div>
            <div style="display:flex;flex-direction:column;gap:8px;">
                <div style="display:flex;justify-content:space-between;font-size:14px;">
                    <span style="color:var(--text-light);">Catégorie</span>
                    <span style="font-weight:500;">{{ $article->category->name ?? '—' }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:14px;">
                    <span style="color:var(--text-light);">État</span>
                    <span style="font-weight:500;">
                        @switch($article->condition)
                            @case('neuf')      ✨ Neuf @break
                            @case('tres_bon')  👍 Très bon état @break
                            @case('bon')       👌 Bon état @break
                            @case('acceptable') ⚠️ Acceptable @break
                            @default {{ $article->condition }}
                        @endswitch
                    </span>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:14px;">
                    <span style="color:var(--text-light);">Vues</span>
                    <span style="font-weight:500;"><i class="fas fa-eye"></i> {{ $article->views }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:14px;">
                    <span style="color:var(--text-light);">Publié le</span>
                    <span style="font-weight:500;">{{ $article->created_at->format('d/m/Y') }}</span>
                </div>
                @if($article->location)
                <div style="display:flex;justify-content:space-between;font-size:14px;">
                    <span style="color:var(--text-light);">Lieu</span>
                    <span style="font-weight:500;"><i class="fas fa-location-dot"></i> {{ $article->location }}</span>
                </div>
                @endif
            </div>
        </div>

    </div>

    {{-- COLONNE DROITE : INFOS & ACTIONS --}}
    <div class="article-panel">

        {{-- CATÉGORIE --}}
        <div class="article-panel-category">
            {{ $article->category->name ?? 'Sans catégorie' }}
        </div>

        {{-- TITRE --}}
        <h1 class="article-panel-title">{{ $article->title }}</h1>

        {{-- PRIX --}}
        <div class="article-panel-price">
            {{ number_format($article->price, 0, ',', ' ') }}
            <span style="font-size:18px;font-weight:400;color:var(--text-light);">FCFA</span>
        </div>

        {{-- BADGE STATUT --}}
        <div style="margin-bottom:20px;">
            <span class="badge badge-{{ $article->status === 'disponible' ? 'success' : ($article->status === 'vendu' ? 'neutral' : 'warning') }}">
                <i class="fas fa-circle" style="font-size:8px;"></i>
                {{ $article->status === 'disponible' ? 'Disponible' : ($article->status === 'vendu' ? 'Vendu' : 'Suspendu') }}
            </span>
        </div>

        {{-- DESCRIPTION --}}
        <div class="article-description">
            {{ $article->description }}
        </div>

        {{-- VENDEUR --}}
        <div class="seller-card">
            <div class="seller-avatar">
                @if($article->user->avatar)
                    <img src="{{ asset('storage/'.$article->user->avatar) }}"
                         alt="{{ $article->user->name }}">
                @else
                    {{ strtoupper(substr($article->user->name, 0, 1)) }}
                @endif
            </div>
            <div class="seller-info">
                <div class="seller-name">{{ $article->user->name }}</div>
                @if($article->user->filiere)
                    <div class="seller-filiere">
                        <i class="fas fa-graduation-cap"></i>
                        {{ $article->user->filiere }}
                    </div>
                @endif
                <div class="seller-rating">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star" style="font-size:12px; {{ $i <= round($article->user->reviewsReceived()->avg('rating') ?? 0) ? 'color:var(--accent)' : 'color:var(--border-dark)' }}"></i>
                    @endfor
                    <span style="font-size:12px;color:var(--text-light);margin-left:4px;">
                        ({{ $article->user->reviewsReceived()->count() }} avis)
                    </span>
                </div>
            </div>
        </div>

        {{-- ACTIONS --}}
        <div class="article-panel-actions">

            @auth
                @if(auth()->id() !== $article->user_id)

                    {{-- MESSAGE AU VENDEUR --}}
                    @if($article->status === 'disponible')
                    <button onclick="toggleMessageForm()" class="btn btn-primary btn-full btn-lg">
                        <i class="fas fa-comment"></i>
                        Contacter le vendeur
                    </button>
                    @else
                    <button class="btn btn-outline-dark btn-full" disabled>
                        <i class="fas fa-ban"></i>
                        Article non disponible
                    </button>
                    @endif

                    {{-- FORMULAIRE MESSAGE (caché par défaut) --}}
                    <div id="message-form" style="display:none;">
                        <form action="{{ route('messages.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="receiver_id" value="{{ $article->user->id }}">
                            <input type="hidden" name="article_id" value="{{ $article->id }}">
                            <textarea name="content"
                                      class="form-control"
                                      rows="3"
                                      placeholder="Ex: Bonjour, est-ce que l'article est encore disponible ?"
                                      required
                                      style="margin-bottom:10px;"></textarea>
                            <button type="submit" class="btn btn-secondary btn-full">
                                <i class="fas fa-paper-plane"></i>
                                Envoyer le message
                            </button>
                        </form>
                    </div>

                    {{-- FAVORI --}}
                    <form action="{{ route('favorites.toggle', $article->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline btn-full">
                            @if(auth()->user()->favorites->contains($article->id))
                                <i class="fas fa-heart" style="color:var(--primary)"></i>
                                Retirer des favoris
                            @else
                                <i class="far fa-heart"></i>
                                Ajouter aux favoris
                            @endif
                        </button>
                    </form>

                    {{-- SIGNALER --}}
                    <button onclick="toggleReportForm()"
                            class="btn btn-ghost btn-full"
                            style="font-size:13px;color:var(--text-light);">
                        <i class="fas fa-flag"></i>
                        Signaler cette annonce
                    </button>

                    {{-- FORMULAIRE SIGNALEMENT --}}
                    <div id="report-form" style="display:none;">
                        <form action="{{ route('reports.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="article_id" value="{{ $article->id }}">
                            <select name="reason" class="form-control" style="margin-bottom:10px;">
                                <option value="">-- Motif du signalement --</option>
                                <option value="Contenu inapproprié">Contenu inapproprié</option>
                                <option value="Arnaque suspectée">Arnaque suspectée</option>
                                <option value="Article déjà vendu">Article déjà vendu</option>
                                <option value="Autre">Autre</option>
                            </select>
                            <button type="submit" class="btn btn-danger btn-full btn-sm">
                                <i class="fas fa-flag"></i> Envoyer le signalement
                            </button>
                        </form>
                    </div>

                @else
                    {{-- C'EST SON PROPRE ARTICLE --}}
                    <a href="{{ route('articles.edit', $article) }}" class="btn btn-outline btn-full">
                        <i class="fas fa-pen"></i> Modifier l'annonce
                    </a>
                    @if($article->status === 'disponible')
                    <form action="{{ route('articles.sold', $article) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-secondary btn-full">
                            <i class="fas fa-check"></i> Marquer comme vendu
                        </button>
                    </form>
                    @endif
                    <form action="{{ route('articles.destroy', $article) }}" method="POST"
                          onsubmit="return confirm('Supprimer cette annonce ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-full">
                            <i class="fas fa-trash"></i> Supprimer l'annonce
                        </button>
                    </form>
                @endif

            @else
                <a href="{{ route('login') }}" class="btn btn-primary btn-full btn-lg">
                    <i class="fas fa-right-to-bracket"></i>
                    Connecte-toi pour contacter le vendeur
                </a>
            @endauth

        </div>

    </div>

</div>

{{-- AVIS --}}
@if($article->reviews && $article->reviews->count() > 0)
<div style="margin-top:48px;">
    <h3 class="section-title">Avis sur le vendeur</h3>
    <div style="display:flex;flex-direction:column;gap:12px;">
        @foreach($article->reviews as $review)
        <div style="background:white;border:1px solid var(--border);border-radius:var(--radius);padding:16px;">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px;">
                <div class="seller-avatar-sm">
                    {{ strtoupper(substr($review->reviewer->name, 0, 1)) }}
                </div>
                <div>
                    <div style="font-weight:600;font-size:14px;">{{ $review->reviewer->name }}</div>
                    <div style="color:var(--accent);">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star" style="font-size:12px;{{ $i <= $review->rating ? '' : 'color:var(--border-dark)' }}"></i>
                        @endfor
                    </div>
                </div>
                <div style="margin-left:auto;font-size:12px;color:var(--text-light);">
                    {{ $review->created_at->diffForHumans() }}
                </div>
            </div>
            @if($review->comment)
            <p style="font-size:14px;color:var(--text-mid);margin:0;">{{ $review->comment }}</p>
            @endif
        </div>
        @endforeach
    </div>
</div>
@endif

@section('scripts')
<script>
function switchImage(src, thumb) {
    document.getElementById('main-img').src = src;
    document.querySelectorAll('.gallery-thumb').forEach(t => t.classList.remove('active'));
    thumb.classList.add('active');
}

function toggleMessageForm() {
    const form = document.getElementById('message-form');
    form.style.display = form.style.display === 'none' ? 'block' : 'none';
}

function toggleReportForm() {
    const form = document.getElementById('report-form');
    form.style.display = form.style.display === 'none' ? 'block' : 'none';
}
</script>
@endsection

@endsection