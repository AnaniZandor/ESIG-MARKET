@extends('layouts.app')

@section('title', 'Mon Profil')

@section('content')

<div style="padding: 32px 0;">

    {{-- ALERTES --}}
    @if(session('success'))
        <div class="alert alert-success" style="margin-bottom:20px;">
            <i class="fas fa-circle-check"></i>
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger" style="margin-bottom:20px;">
            <i class="fas fa-circle-exclamation"></i>
            <div>
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        </div>
    @endif

    {{-- HEADER PROFIL --}}
    <div class="profile-header-card">

        {{-- AVATAR --}}
        <div class="profile-avatar-lg">
            @if($user->avatar)
                <img src="{{ asset('storage/'.$user->avatar) }}"
                     alt="{{ $user->name }}">
            @else
                {{ strtoupper(substr($user->name, 0, 1)) }}
            @endif
        </div>

        {{-- INFOS --}}
        <div class="profile-info">
            <h2>{{ $user->name }}</h2>
            <p><i class="fas fa-envelope"></i> {{ $user->email }}</p>
            @if($user->filiere)
                <p style="margin-top:4px;">
                    <i class="fas fa-graduation-cap"></i> {{ $user->filiere }}
                </p>
            @endif
            @if($user->numero_etudiant)
                <p style="margin-top:4px;">
                    <i class="fas fa-id-card"></i> {{ $user->numero_etudiant }}
                </p>
            @endif

            {{-- STATS --}}
            <div class="profile-stats-row">
                <div class="profile-stat">
                    <div class="profile-stat-num">{{ $articles->count() }}</div>
                    <div class="profile-stat-label">Annonces</div>
                </div>
                <div class="profile-stat">
                    <div class="profile-stat-num">
                        {{ $articles->where('status', 'disponible')->count() }}
                    </div>
                    <div class="profile-stat-label">Disponibles</div>
                </div>
                <div class="profile-stat">
                    <div class="profile-stat-num">
                        {{ $articles->where('status', 'vendu')->count() }}
                    </div>
                    <div class="profile-stat-label">Vendus</div>
                </div>
                <div class="profile-stat">
                    <div class="profile-stat-num">
                        {{ $user->reviewsReceived()->avg('rating')
                            ? number_format($user->reviewsReceived()->avg('rating'), 1)
                            : '—' }}
                    </div>
                    <div class="profile-stat-label">Note moyenne</div>
                </div>
            </div>
        </div>

        {{-- BOUTON MODIFIER --}}
        <div style="margin-left:auto; align-self:flex-start;">
            <a href="#edit-profile" class="btn btn-outline">
                <i class="fas fa-pen"></i> Modifier le profil
            </a>
        </div>

    </div>

    {{-- MES ANNONCES --}}
    <h2 class="section-title" style="margin-top:40px;">Mes annonces</h2>

    @if($articles->count() > 0)
    <div class="grid-articles">
        @foreach($articles as $article)
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
            </div>

            {{-- INFOS --}}
            <div class="article-card-body">
                <div class="article-card-category">
                    {{ $article->category->name ?? 'Sans catégorie' }}
                </div>
                <div class="article-card-title">{{ $article->title }}</div>
                <div class="article-card-price">
                    {{ number_format($article->price, 0, ',', ' ') }}
                    <span>FCFA</span>
                </div>

                {{-- ACTIONS --}}
                <div style="display:flex; gap:8px; margin-top:12px; padding-top:12px; border-top:1px solid var(--border);">

                    <a href="{{ route('articles.show', $article->id) }}"
                       class="btn btn-outline btn-sm" style="flex:1; text-align:center;">
                        <i class="fas fa-eye"></i> Voir
                    </a>

                    <a href="{{ route('articles.edit', $article) }}"
                       class="btn btn-outline-dark btn-sm">
                        <i class="fas fa-pen"></i>
                    </a>

                    @if($article->status === 'disponible')
                    <form action="{{ route('articles.sold', $article) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-sm"
                                style="background:var(--success-bg);color:var(--success);border:none;"
                                title="Marquer comme vendu">
                            <i class="fas fa-check"></i>
                        </button>
                    </form>
                    @endif

                    <form action="{{ route('articles.destroy', $article) }}"
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
            </div>

        </div>
        @endforeach
    </div>

    @else
    <div class="empty-state">
        <i class="fas fa-box-open"></i>
        <h3>Aucune annonce publiée</h3>
        <p>Tu n'as pas encore publié d'annonce.</p>
        <a href="{{ route('articles.create') }}" class="btn btn-primary" style="margin-top:16px;">
            <i class="fas fa-plus"></i> Publier mon premier article
        </a>
    </div>
    @endif

    {{-- MODIFIER LE PROFIL --}}
    <div id="edit-profile" style="margin-top:48px;">
        <h2 class="section-title">Modifier mon profil</h2>

        <div style="background:white; border:1px solid var(--border); border-radius:var(--radius-lg); padding:32px; max-width:600px; box-shadow:var(--shadow-sm);">
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- NOM --}}
                <div class="form-group">
                    <label class="form-label">Nom complet</label>
                    <input type="text" name="name" class="form-control @error('name') is-error @enderror"
                           value="{{ old('name', $user->name) }}" required>
                    @error('name')
                        <span class="form-error"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>
                    @enderror
                </div>

                {{-- EMAIL --}}
                <div class="form-group">
                    <label class="form-label">Adresse email</label>
                    <input type="email" name="email" class="form-control @error('email') is-error @enderror"
                           value="{{ old('email', $user->email) }}" required>
                    @error('email')
                        <span class="form-error"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>
                    @enderror
                </div>

                {{-- FILIÈRE --}}
                <div class="form-group">
                    <label class="form-label">Filière</label>
                    <select name="filiere" class="form-control">
                        <option value="">-- Choisir --</option>
                        <option value="Génie Logiciel"     {{ old('filiere', $user->filiere) == 'Génie Logiciel'     ? 'selected' : '' }}>Génie Logiciel</option>
                        <option value="Réseaux & Télécoms"  {{ old('filiere', $user->filiere) == 'Réseaux & Télécoms'  ? 'selected' : '' }}>Réseaux & Télécoms</option>
                        <option value="Systèmes Embarqués"  {{ old('filiere', $user->filiere) == 'Systèmes Embarqués'  ? 'selected' : '' }}>Systèmes Embarqués</option>
                        <option value="Autre"               {{ old('filiere', $user->filiere) == 'Autre'               ? 'selected' : '' }}>Autre</option>
                    </select>
                </div>

                {{-- NUMÉRO ÉTUDIANT --}}
                <div class="form-group">
                    <label class="form-label">Numéro étudiant</label>
                    <input type="text" name="numero_etudiant" class="form-control"
                           value="{{ old('numero_etudiant', $user->numero_etudiant) }}"
                           placeholder="Ex: ESIG2024001">
                </div>

                {{-- PHOTO DE PROFIL --}}
                <div class="form-group">
                    <label class="form-label">
                        Photo de profil
                        <span style="color:var(--text-light);font-weight:400;">(optionnel)</span>
                    </label>

                    {{-- Photo actuelle --}}
                    @if($user->avatar)
                    <div style="display:flex; align-items:center; gap:12px; margin-bottom:12px; padding:12px; background:var(--bg); border-radius:var(--radius); border:1px solid var(--border);">
                        <img src="{{ asset('storage/'.$user->avatar) }}"
                             alt="Avatar actuel"
                             style="width:56px; height:56px; border-radius:50%; object-fit:cover; border:3px solid var(--primary-light);">
                        <div>
                            <div style="font-size:13px; font-weight:500; color:var(--text-dark);">Photo actuelle</div>
                            <div style="font-size:12px; color:var(--text-light);">Sélectionne une nouvelle photo pour la remplacer</div>
                        </div>
                    </div>
                    @endif

                    <input type="file" name="avatar" class="form-control"
                           accept="image/jpg,image/jpeg,image/png,image/webp">
                    <span style="font-size:12px; color:var(--text-light); margin-top:4px; display:block;">
                        <i class="fas fa-info-circle"></i> JPG, PNG, WEBP — Max 2Mo
                    </span>

                    @error('avatar')
                        <span class="form-error"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>
                    @enderror
                </div>

                {{-- SÉPARATEUR --}}
                <div class="divider"></div>

                {{-- NOUVEAU MOT DE PASSE --}}
                <div class="form-group">
                    <label class="form-label">
                        Nouveau mot de passe
                        <span style="color:var(--text-light);font-weight:400;">(laisser vide pour ne pas changer)</span>
                    </label>
                    <input type="password" name="password"
                           class="form-control @error('password') is-error @enderror"
                           placeholder="Minimum 8 caractères">
                    @error('password')
                        <span class="form-error"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>
                    @enderror
                </div>

                {{-- CONFIRMATION MOT DE PASSE --}}
                <div class="form-group">
                    <label class="form-label">Confirmer le nouveau mot de passe</label>
                    <input type="password" name="password_confirmation" class="form-control"
                           placeholder="Répète le nouveau mot de passe">
                </div>

                {{-- BOUTON --}}
                <button type="submit" class="btn btn-primary btn-full" style="margin-top:8px;">
                    <i class="fas fa-save"></i> Enregistrer les modifications
                </button>

            </form>
        </div>
    </div>

</div>

@endsection