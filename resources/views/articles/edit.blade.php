@extends('layouts.app')

@section('title', 'Modifier l\'annonce')

@section('content')

<div class="form-container" style="margin: 32px auto;">

    {{-- ═══════════════════════════════════════
         EN-TÊTE DU FORMULAIRE
    ═══════════════════════════════════════ --}}
    <div class="form-header">
        <h2>Modifier l'annonce</h2>
        <p>Modifie les informations de ton article</p>
    </div>

    {{-- ═══════════════════════════════════════
         FORMULAIRE PRINCIPAL
    ═══════════════════════════════════════ --}}
    <div class="form-body">
        <form action="{{ route('articles.update', $article) }}"
              method="POST"
              enctype="multipart/form-data"
              id="article-form">
            @csrf
            @method('PUT')

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
                           value="{{ old('title', $article->title) }}"
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
                              required>{{ old('description', $article->description) }}</textarea>
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
                               value="{{ old('price', $article->price) }}"
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
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}"
                                    {{ old('category_id', $article->category_id) == $cat->id ? 'selected' : '' }}>
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
                <div class="form-group">
                    <label class="form-label">État de l'article</label>
                    <select name="condition"
                            class="form-control @error('condition') is-error @enderror"
                            required>
                        <option value="">-- Choisir l'état --</option>
                        <option value="neuf"
                            {{ old('condition', $article->condition) == 'neuf' ? 'selected' : '' }}>
                            ✨ Neuf
                        </option>
                        <option value="tres_bon"
                            {{ old('condition', $article->condition) == 'tres_bon' ? 'selected' : '' }}>
                            👍 Très bon état
                        </option>
                        <option value="bon"
                            {{ old('condition', $article->condition) == 'bon' ? 'selected' : '' }}>
                            👌 Bon état
                        </option>
                        <option value="acceptable"
                            {{ old('condition', $article->condition) == 'acceptable' ? 'selected' : '' }}>
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
                           value="{{ old('location', $article->location) }}">
                </div>

            </div>{{-- fin section 1 --}}

            {{-- ───────────────────────────────
                 SECTION 2 : PHOTOS EXISTANTES
            ___________________________________ --}}
            <div class="form-section">
                <div class="form-section-title">Photos actuelles</div>
                
                @if($article->images && $article->images->count() > 0)
                    <div class="current-images-grid">
                        @foreach($article->images as $image)
                            <div class="current-image-item">
                                <img src="{{ asset('storage/' . $image->path) }}" alt="Photo de l'article">
                                @if($image->is_primary)
                                    <span class="primary-badge">Photo principale</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                    <p style="font-size:13px; color:var(--text-light); margin-top:8px;">
                        <i class="fas fa-info-circle"></i>
                        Pour supprimer ou modifier les photos, utilise la page de gestion des photos
                    </p>
                @else
                    <p style="color:var(--text-light);">Aucune photo pour cet article</p>
                @endif
            </div>{{-- fin section 2 --}}

            {{-- ───────────────────────────────
                 SECTION 3 : AJOUTER DES PHOTOS
            ___________________________________ --}}
            <div class="form-section">
                <div class="form-section-title">Ajouter des photos</div>

                {{-- Zone de clic pour ouvrir le sélecteur de fichiers --}}
                <div class="upload-zone"
                     onclick="document.getElementById('images-input').click()"
                     id="upload-zone">
                    <i class="fas fa-cloud-arrow-up"></i>
                    <p><strong>Clique pour ajouter des photos</strong></p>
                    <p style="font-size:12px;margin-top:4px;">
                        JPG, PNG, WEBP — Max 2Mo par photo — Jusqu'à 5 photos au total
                    </p>
                </div>

                {{-- Input file caché --}}
                <input type="file"
                       id="images-input"
                       multiple
                       accept="image/jpg,image/jpeg,image/png,image/webp"
                       style="display:none;"
                       onchange="addImages(this)">

                {{-- Compteur de nouvelles photos --}}
                <p id="photo-count"
                   style="font-size:13px;color:var(--text-light);margin-top:10px;">
                    0 nouvelle(s) photo(s) sélectionnée(s)
                </p>

                {{-- Grille de prévisualisation --}}
                <div id="preview-grid"
                     style="display:flex;gap:10px;flex-wrap:wrap;margin-top:12px;">
                </div>

                {{-- Conteneur des inputs hidden --}}
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

            </div>{{-- fin section 3 --}}

            {{-- ───────────────────────────────
                 BOUTONS D'ACTION
            ___________________________________ --}}
            <div style="display:flex;gap:12px;justify-content:flex-end;margin-top:8px;">

                {{-- Annuler → retour à l'article --}}
                <a href="{{ route('articles.show', $article) }}"
                   class="btn btn-outline-dark">
                    <i class="fas fa-arrow-left"></i>
                    Annuler
                </a>

                {{-- Soumettre le formulaire --}}
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-save"></i>
                    Enregistrer les modifications
                </button>

            </div>

        </form>
    </div>

</div>

{{-- ═══════════════════════════════════════
     SCRIPT DE GESTION DES PHOTOS
═══════════════════════════════════════ --}}
@section('scripts')
<script>

// Tableau global pour les nouvelles photos
let allFiles = [];
// Nombre de photos existantes
const existingPhotosCount = {{ $article->images->count() }};
const maxTotalPhotos = 5;

// ─── Appelée à chaque sélection de fichiers ───────────────
function addImages(input) {
    const newFiles = Array.from(input.files);
    
    // Calculer le nombre total de photos (existantes + nouvelles)
    const currentTotal = existingPhotosCount + allFiles.length;

    // Vérifier qu'on ne dépasse pas 5 photos au total
    if (currentTotal + newFiles.length > maxTotalPhotos) {
        alert(`Maximum ${maxTotalPhotos} photos autorisées au total.\nTu as déjà ${existingPhotosCount} photo(s) existante(s) et ${allFiles.length} nouvelle(s) photo(s) sélectionnée(s).`);
        input.value = '';
        return;
    }

    // Ajouter chaque nouveau fichier
    newFiles.forEach(file => {
        const alreadyAdded = allFiles.some(
            f => f.name === file.name && f.size === file.size
        );
        if (!alreadyAdded) {
            allFiles.push(file);
        }
    });

    // Réinitialiser l'input
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

// ─── Afficher les miniatures des nouvelles photos ─────────
function renderPreviews() {
    const grid    = document.getElementById('preview-grid');
    const counter = document.getElementById('photo-count');
    const zone    = document.getElementById('upload-zone');

    const currentTotal = existingPhotosCount + allFiles.length;

    // Mettre à jour le compteur
    counter.textContent = `${allFiles.length} nouvelle(s) photo(s) sélectionnée(s) (Total: ${currentTotal}/${maxTotalPhotos})`;

    // Changer la couleur du compteur selon la limite
    counter.style.color = currentTotal >= maxTotalPhotos
        ? 'var(--primary)'
        : 'var(--text-light)';

    // Désactiver la zone de clic si on a atteint 5 photos
    zone.style.opacity  = currentTotal >= maxTotalPhotos ? '0.5' : '1';
    zone.style.cursor   = currentTotal >= maxTotalPhotos ? 'not-allowed' : 'pointer';
    zone.onclick        = currentTotal >= maxTotalPhotos
        ? null
        : () => document.getElementById('images-input').click();

    // Vider la grille
    grid.innerHTML = '';

    // Créer une miniature pour chaque nouveau fichier
    allFiles.forEach((file, i) => {
        const reader = new FileReader();

        reader.onload = (e) => {
            const div = document.createElement('div');
            div.style.cssText = `
                position: relative;
                width: 100px;
                height: 100px;
                border-radius: 8px;
                overflow: hidden;
                border: 2px solid var(--border);
                flex-shrink: 0;
            `;

            const img = document.createElement('img');
            img.src = e.target.result;
            img.style.cssText = 'width:100%;height:100%;object-fit:cover;';
            img.alt = `Nouvelle photo ${i + 1}`;

            // Bouton supprimer
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
function syncFilesToForm() {
    const container = document.getElementById('file-inputs');
    container.innerHTML = '';

    if (allFiles.length === 0) return;

    const dt = new DataTransfer();
    allFiles.forEach(file => dt.items.add(file));

    const input = document.createElement('input');
    input.type     = 'file';
    input.name     = 'images[]';
    input.multiple = true;
    input.style.display = 'none';

    try {
        input.files = dt.files;
    } catch(e) {
        console.warn('DataTransfer non supporté:', e);
    }

    container.appendChild(input);
}

</script>

{{-- Style pour les photos existantes --}}
<style>
.current-images-grid {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    margin-bottom: 8px;
}

.current-image-item {
    position: relative;
    width: 100px;
    height: 100px;
    border-radius: 8px;
    overflow: hidden;
    border: 2px solid var(--border);
}

.current-image-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.primary-badge {
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
}
</style>

@endsection

@endsection