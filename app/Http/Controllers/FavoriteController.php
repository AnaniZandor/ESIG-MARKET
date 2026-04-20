<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    // ─── Liste des favoris de l'utilisateur ───────────────────
    public function index()
    {
        $favorites = Auth::user()->favorites()
                        ->with(['images', 'category', 'user'])
                        ->latest('favorites.created_at')
                        ->get();

        return view('favorites.index', compact('favorites'));
    }

    // ─── Ajouter / Retirer un favori ──────────────────────────
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