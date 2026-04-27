<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LISTE DES FAVORIS
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        // favorites() est un belongsToMany vers Article
        // donc on peut directement chaîner with() sur les articles
        $favorites = auth()->user()
                           ->favorites()
                           ->with(['images', 'category', 'user'])
                           ->get();

        // Ids des favoris pour afficher le cœur actif
        $userFavorites = $favorites->pluck('id');

        return view('favorites.index', compact('favorites', 'userFavorites'));
    }

    /*
    |--------------------------------------------------------------------------
    | AJOUTER / RETIRER UN FAVORI
    |--------------------------------------------------------------------------
    */
    public function toggle($articleId)
    {
        $user    = Auth::user();
        $article = Article::findOrFail($articleId);

        if ($user->favorites()->where('article_id', $articleId)->exists()) {
            $user->favorites()->detach($articleId);
            return back()->with('success', 'Retiré des favoris');
        }

        $user->favorites()->attach($articleId);
        return back()->with('success', 'Ajouté aux favoris ❤️');
    }
}