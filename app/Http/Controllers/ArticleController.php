<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use App\Http\Requests\StoreArticleRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LISTE ARTICLES
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $query = Article::with(['user', 'category', 'images']);

        // Recherche par mot-clé
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', '%'.$search.'%')
                  ->orWhere('description', 'LIKE', '%'.$search.'%');
            });
        }

        // Filtre par catégorie (slug)
        if ($request->filled('category')) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Filtre par prix minimum
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        // Filtre par prix maximum
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Tri
        if ($request->filled('sort')) {
            if ($request->sort === 'price_asc') {
                $query->orderBy('price', 'asc');
            } elseif ($request->sort === 'price_desc') {
                $query->orderBy('price', 'desc');
            }
        } else {
            $query->latest();
        }

        // Seulement les articles disponibles
        $query->where('status', 'disponible');

        $articles = $query->paginate(12);

        // ✅ Catégories pour les filtres
        $categories = \App\Models\Category::orderBy('name')->get();

        // ✅ IDs des favoris de l'utilisateur connecté
        $userFavorites = auth()->check()
            ? auth()->user()->favorites()->pluck('articles.id')
            : collect();

        return view('articles.index', compact('articles', 'categories', 'userFavorites'));
    }

    /*
    |--------------------------------------------------------------------------
    | FORMULAIRE CRÉATION
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        $categories = \App\Models\Category::orderBy('name')->get();
        return view('articles.create', compact('categories'));
    }

    /*
    |--------------------------------------------------------------------------
    | ENREGISTRER ARTICLE
    |--------------------------------------------------------------------------
    */
    public function store(StoreArticleRequest $request)
    {
        $data = $request->validated();

        $article = Article::create([
            'user_id'     => auth()->id(),
            'category_id' => $data['category_id'],
            'title'       => $data['title'],
            'slug'        => Str::slug($data['title']) . '-' . time(),
            'description' => $data['description'],
            'price'       => $data['price'],
            'condition'   => $data['condition'] ?? 'bon',
            'status'      => 'disponible',
            'location'    => $data['location'] ?? null,
        ]);

        // Upload des images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                if ($image->isValid()) {
                    $path = $image->store('articles', 'public');
                    $article->images()->create(['path' => $path]);
                }
            }
        }

        return redirect()
            ->route('articles.index')
            ->with('success', '🎉 Article publié avec succès !');
    }

    /*
    |--------------------------------------------------------------------------
    | AFFICHER UN ARTICLE
    |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        $article = Article::with(['user', 'category', 'images', 'reviews.reviewer'])
            ->findOrFail($id);

        // Incrémenter les vues
        $article->increment('views');

        // ✅ Vérifier si l'article est en favori
        $isFavorite = auth()->check()
            ? auth()->user()->favorites()->where('article_id', $id)->exists()
            : false;

        return view('articles.show', compact('article', 'isFavorite'));
    }

    /*
    |--------------------------------------------------------------------------
    | FORMULAIRE MODIFICATION
    |--------------------------------------------------------------------------
    */
    public function edit(Article $article)
    {
        if ($article->user_id !== auth()->id()) {
            abort(403, 'Action non autorisée.');
        }

        $categories = \App\Models\Category::orderBy('name')->get();
        return view('articles.edit', compact('article', 'categories'));
    }

    /*
    |--------------------------------------------------------------------------
    | METTRE À JOUR UN ARTICLE
    |--------------------------------------------------------------------------
    */
    public function update(StoreArticleRequest $request, Article $article)
    {
        if ($article->user_id !== auth()->id()) {
            abort(403, 'Action non autorisée.');
        }

        $article->update([
            'category_id' => $request->category_id,
            'title'       => $request->title,
            'description' => $request->description,
            'price'       => $request->price,
            'condition'   => $request->condition ?? 'bon',
            'status'      => $request->status ?? 'disponible',
        ]);

        // Nouvelles images ajoutées
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                if ($image->isValid()) {
                    $path = $image->store('articles', 'public');
                    $article->images()->create(['path' => $path]);
                }
            }
        }

        return redirect()
            ->route('articles.index')
            ->with('success', '✅ Article mis à jour avec succès !');
    }

    /*
    |--------------------------------------------------------------------------
    | SUPPRIMER UN ARTICLE
    |--------------------------------------------------------------------------
    */
    public function destroy(Article $article)
    {
        if ($article->user_id !== auth()->id()) {
            abort(403, 'Action non autorisée.');
        }

        // Supprimer les images du storage
        foreach ($article->images as $image) {
            if ($image->path) {
                Storage::disk('public')->delete($image->path);
            }
        }

        $article->delete();

        return redirect()
            ->route('articles.index')
            ->with('success', '🗑️ Article supprimé avec succès.');
    }

    /*
    |--------------------------------------------------------------------------
    | MARQUER COMME VENDU
    |--------------------------------------------------------------------------
    */
    public function markAsSold(Article $article)
    {
        if ($article->user_id !== auth()->id()) {
            abort(403, 'Action non autorisée.');
        }

        $article->update(['status' => 'vendu']);

        // Notifier les users qui ont cet article en favori
        foreach ($article->favoritedBy as $user) {
            $user->notify(new \App\Notifications\FavoriVenduNotification($article));
        }

        return back()->with('success', '✅ Article marqué comme vendu !');
    }
}