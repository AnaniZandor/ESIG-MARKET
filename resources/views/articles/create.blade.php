@extends('layouts.app')

@section('title', 'Publier une annonce')

@section('content')

<div class="form-container" style="margin: 32px auto;">

    {{-- ═══════════════════════════════════════
         EN-TÊTE DU FORMULAIRE
    ═══════════════════════════════════════ --}}
    <div class="form-header">
        <h2>Publier une annonce</h2>
        <p>Remplis les informations de ton article à vendre</p>
    </div>

    {{-- ═══════════════════════════════════════
         FORMULAIRE PRINCIPAL
         enctype="multipart/form-data" est
         obligatoire pour l'upload de fichiers
    ═══════════════════════════════════════ --}}
    <div class="form-body">
        <form action="{{ route('articles.store') }}"
              method="POST"
              enctype="multipart/form-data"
              id="article-form">
            @csrf

            {{-- ───────────────────────────────
                 SECTION 1 : INFOS PRINCIPALES
            ___________________________________ --}}
            <div class="form-section">
                <div class="form-section-title">
                    Informations principales
                </div>

                {{-- TITRE --}}
                <div class="form-group">
                    <label class="form-label">Titre de l'annonce</label>
                    <input type="text"
                           name="title"
                           class="form-control @error('title') is-error @enderror"
                           placeholder="Ex: iPhone 12 en bon état"
                           value="{{ old('title') }}"
                           required>
                    @error('title')
                        <span class="form-error">
                            <i class="fas fa-circle-exclamation"></i>
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                {{-- DESCRIPTION --}}
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description"
                              class="form-control @error('description') is-error @enderror"
                              placeholder="Décris ton article en détail : état, caractéristiques, raison de la vente..."
                              rows="5"
                              required>{{ old('description') }}</textarea>
                    @error('description')
                        <span class="form-error">
                            <i class="fas fa-circle-exclamation"></i>
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                {{-- PRIX & CATÉGORIE (côte à côte) --}}
                <div class="form-row">

                    {{-- PRIX --}}
                    <div class="form-group">
                        <label class="form-label">Prix (FCFA)</label>
                        <input type="number"
                               name="price"
                               class="form-control @error('price') is-error @enderror"
                               placeholder="Ex: 15000"
                               value="{{ old('price') }}"
                               min="0"
                               required>
                        @error('price')
                            <span class="form-error">
                                <i class="fas fa-circle-exclamation"></i>
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    {{-- CATÉGORIE --}}
                    <div class="form-group">
                        <label class="form-label">Catégorie</label>
                        <select name="category_id"
                                class="form-control @error('category_id') is-error @enderror"
                                required>
                            <option value="">-- Choisir une catégorie --</option>
                            {{-- $categories vient du ArticleController@create --}}
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}"
                                    {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->icon }} {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <span class="form-error">
                                <i class="fas fa-circle-exclamation"></i>
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                </div>

                {{-- ÉTAT DE L'ARTICLE --}}
                {{-- Les valeurs doivent correspondre exactement
                     à l'ENUM de la migration :
                     neuf | tres_bon | bon | acceptable --}}
                <div class="form-group">
                    <label class="form-label">État de l'article</label>
                    <select name="condition"
                            class="form-control @error('condition') is-error @enderror"
                            required>
                        <option value="">-- Choisir l'état --</option>
                        <option value="neuf"
                            {{ old('condition') == 'neuf' ? 'selected' : '' }}>
                            ✨ Neuf
                        </option>
                        <option value="tres_bon"
                            {{ old('condition') == 'tres_bon' ? 'selected' : '' }}>
                            👍 Très bon état
                        </option>
                        <option value="bon"
                            {{ old('condition') == 'bon' ? 'selected' : '' }}>
                            👌 Bon état
                        </option>
                        <option value="acceptable"
                            {{ old('condition') == 'acceptable' ? 'selected' : '' }}>
                            ⚠️ État acceptable
                        </option>
                    </select>
                    @error('condition')
                        <span class="form-error">
                            <i class="fas fa-circle-exclamation"></i>
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                {{-- LOCALISATION (optionnel) --}}
                <div class="form-group">
                    <label class="form-label">
                        Lieu de remise
                        <span style="color:var(--text-light);font-weight:400;">
                            (optionnel)
                        </span>
                    </label>
                    <input type="text"
                           name="location"
                           class="form-control"
                           placeholder="Ex: Campus ESIG, Bâtiment A"
                           value="{{ old('location') }}">
                </div>

            </div>{{-- fin section 1 --}}

            {{-- ───────────────────────────────
                 SECTION 2 : PHOTOS
                 Gestion accumulative :
                 on peut ajouter des photos
                 plusieurs fois sans perdre
                 les précédentes (max 5)
            ___________________________________ --}}
            <div class="form-section">
                <div class="form-section-title">Photos de l'article</div>

                {{-- Zone de clic pour ouvrir le sélecteur de fichiers --}}
                <div class="upload-zone"
                     onclick="document.getElementById('images-input').click()"
                     id="upload-zone">
                    <i class="fas fa-cloud-arrow-up"></i>
                    <p><strong>Clique pour ajouter des photos</strong></p>
                    <p style="font-size:12px;margin-top:4px;">
                        JPG, PNG, WEBP — Max 2Mo par photo — Jusqu'à 5 photos
                    </p>
                </div>

                {{-- Input file caché — onchange appelle addImages() --}}
                <input type="file"
                       id="images-input"
                       multiple
                       accept="image/jpg,image/jpeg,image/png,image/webp"
                       style="display:none;"
                       onchange="addImages(this)">

                {{-- Compteur de photos sélectionnées --}}
                <p id="photo-count"
                   style="font-size:13px;color:var(--text-light);margin-top:10px;">
                    0 / 5 photos sélectionnées
                </p>

                {{-- Grille de prévisualisation des photos --}}
                <div id="preview-grid"
                     style="display:flex;gap:10px;flex-wrap:wrap;margin-top:12px;">
                </div>

                {{-- Conteneur des inputs hidden générés par JS
                     C'est ici que les fichiers sont vraiment
                     envoyés au serveur via le formulaire --}}
                <div id="file-inputs"></div>

                @error('images')
                    <span class="form-error">
                        <i class="fas fa-circle-exclamation"></i>
                        {{ $message }}
                    </span>
                @enderror
                @error('images.*')
                    <span class="form-error">
                        <i class="fas fa-circle-exclamation"></i>
                        {{ $message }}
                    </span>
                @enderror

            </div>{{-- fin section 2 --}}

            {{-- ───────────────────────────────
                 BOUTONS D'ACTION
            ___________________________________ --}}
            <div style="display:flex;gap:12px;justify-content:flex-end;margin-top:8px;">

                {{-- Annuler → retour à la liste --}}
                <a href="{{ route('articles.index') }}"
                   class="btn btn-outline-dark">
                    <i class="fas fa-arrow-left"></i>
                    Annuler
                </a>

                {{-- Soumettre le formulaire --}}
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-paper-plane"></i>
                    Publier l'annonce
                </button>

            </div>

        </form>
    </div>

</div>

{{-- ═══════════════════════════════════════
     SCRIPT DE GESTION DES PHOTOS
     Logique : on accumule les fichiers dans
     un tableau JS (allFiles) au lieu de
     remplacer à chaque sélection
═══════════════════════════════════════ --}}
@section('scripts')
<script>

// Tableau global qui stocke tous les fichiers sélectionnés
let allFiles = [];

// ─── Appelée à chaque sélection de fichiers ───────────────
function addImages(input) {
    const newFiles = Array.from(input.files);

    // Vérifier qu'on ne dépasse pas 5 photos au total
    if (allFiles.length + newFiles.length > 5) {
        alert(`Maximum 5 photos autorisées. Tu en as déjà ${allFiles.length}.`);
        input.value = '';
        return;
    }

    // Ajouter chaque nouveau fichier s'il n'est pas déjà présent
    newFiles.forEach(file => {
        const alreadyAdded = allFiles.some(
            f => f.name === file.name && f.size === file.size
        );
        if (!alreadyAdded) {
            allFiles.push(file);
        }
    });

    // Réinitialiser l'input pour permettre de resélectionner
    input.value = '';

    // Mettre à jour l'affichage
    renderPreviews();
    syncFilesToForm();
}

// ─── Supprimer une photo par son index ────────────────────
function removeImage(index) {
    allFiles.splice(index, 1);
    renderPreviews();
    syncFilesToForm();
}

// ─── Afficher les miniatures des photos ───────────────────
function renderPreviews() {
    const grid    = document.getElementById('preview-grid');
    const counter = document.getElementById('photo-count');
    const zone    = document.getElementById('upload-zone');

    // Mettre à jour le compteur
    counter.textContent = `${allFiles.length} / 5 photos sélectionnées`;

    // Changer la couleur du compteur selon la limite
    counter.style.color = allFiles.length >= 5
        ? 'var(--primary)'
        : 'var(--text-light)';

    // Désactiver la zone de clic si on a atteint 5 photos
    zone.style.opacity  = allFiles.length >= 5 ? '0.5' : '1';
    zone.style.cursor   = allFiles.length >= 5 ? 'not-allowed' : 'pointer';
    zone.onclick        = allFiles.length >= 5
        ? null
        : () => document.getElementById('images-input').click();

    // Vider la grille
    grid.innerHTML = '';

    // Créer une miniature pour chaque fichier
    allFiles.forEach((file, i) => {
        const reader = new FileReader();

        reader.onload = (e) => {
            // Conteneur de la miniature
            const div = document.createElement('div');
            div.style.cssText = `
                position: relative;
                width: 100px;
                height: 100px;
                border-radius: 8px;
                overflow: hidden;
                border: 2px solid var(--border);
                flex-shrink: 0;
                transition: border-color 0.2s;
            `;

            // Image miniature
            const img = document.createElement('img');
            img.src = e.target.result;
            img.style.cssText = 'width:100%;height:100%;object-fit:cover;';
            img.alt = `Photo ${i + 1}`;

            // Badge "Principal" sur la première photo
            if (i === 0) {
                const badge = document.createElement('span');
                badge.style.cssText = `
                    position: absolute;
                    bottom: 4px;
                    left: 4px;
                    background: var(--primary);
                    color: white;
                    font-size: 9px;
                    font-weight: 700;
                    padding: 2px 6px;
                    border-radius: 10px;
                    text-transform: uppercase;
                `;
                badge.textContent = 'Principal';
                div.appendChild(badge);
            }

            // Bouton supprimer ✕
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.onclick = (e) => {
                e.stopPropagation();
                removeImage(i);
            };
            btn.style.cssText = `
                position: absolute;
                top: 4px;
                right: 4px;
                background: rgba(0,0,0,0.65);
                color: white;
                border: none;
                border-radius: 50%;
                width: 22px;
                height: 22px;
                font-size: 11px;
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
                line-height: 1;
            `;
            btn.innerHTML = '✕';

            div.appendChild(img);
            div.appendChild(btn);
            grid.appendChild(div);
        };

        reader.readAsDataURL(file);
    });
}

// ─── Synchroniser allFiles avec le formulaire ─────────────
// On recrée un input file avec tous les fichiers combinés
// grâce à DataTransfer, pour que le formulaire les envoie
function syncFilesToForm() {
    const container = document.getElementById('file-inputs');
    container.innerHTML = '';

    if (allFiles.length === 0) return;

    // DataTransfer permet de créer un FileList personnalisé
    const dt = new DataTransfer();
    allFiles.forEach(file => dt.items.add(file));

    // Créer l'input hidden avec tous les fichiers
    const input = document.createElement('input');
    input.type     = 'file';
    input.name     = 'images[]';
    input.multiple = true;
    input.style.display = 'none';

    // Assigner la FileList combinée
    try {
        input.files = dt.files;
    } catch(e) {
        // Fallback pour certains navigateurs
        console.warn('DataTransfer non supporté:', e);
    }

    container.appendChild(input);
}

</script>
@endsection

@endsection