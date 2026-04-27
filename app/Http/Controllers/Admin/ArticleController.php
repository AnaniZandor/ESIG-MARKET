<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    /**
     * Afficher la liste de tous les articles
     */
    public function index(Request $request)
    {
        $query = Article::with(['user', 'category', 'images']);

        // Filtres
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }

        if ($request->filled('user')) {
            $query->where('user_id', $request->user);
        }

        $articles = $query->orderBy('created_at', 'desc')->paginate(15);
        $categories = Category::orderBy('name')->get();
        $users = User::orderBy('name')->get();

        // Statistiques
        $stats = [
            'total' => Article::count(),
            'disponible' => Article::where('status', 'disponible')->count(),
            'vendu' => Article::where('status', 'vendu')->count(),
            'reserve' => Article::where('status', 'reserve')->count(),
            'total_views' => Article::sum('views'),
        ];

        return view('admin.articles.index', compact('articles', 'categories', 'users', 'stats'));
    }

    /**
     * Afficher les détails d'un article
     */
    public function show(Article $article)
    {
        $article->load(['user', 'category', 'images']);
        return view('admin.articles.show', compact('article'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(Article $article)
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.articles.edit', compact('article', 'categories'));
    }

    /**
     * Mettre à jour l'article
     */
    public function update(Request $request, Article $article)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'condition' => 'required|in:neuf,tres_bon,bon,acceptable',
            'status' => 'required|in:disponible,vendu,reserve',
            'location' => 'nullable|string|max:255',
        ]);

        $article->update($validated);

        return redirect()->route('admin.articles.show', $article)
            ->with('success', 'Article mis à jour avec succès.');
    }

    /**
     * Supprimer un article
     */
    public function destroy(Article $article)
    {
        // Supprimer les images associées
        foreach ($article->images as $image) {
            if (Storage::exists($image->path)) {
                Storage::delete($image->path);
            }
            $image->delete();
        }

        $article->delete();

        return redirect()->route('admin.articles.index')
            ->with('success', 'Article supprimé avec succès.');
    }

    /**
     * Changer le statut d'un article
     */
    public function toggleStatus(Article $article)
    {
        $statuses = ['disponible', 'reserve', 'vendu'];
        $currentIndex = array_search($article->status, $statuses);
        $nextIndex = ($currentIndex + 1) % count($statuses);
        $article->status = $statuses[$nextIndex];
        $article->save();

        $statusLabels = [
            'disponible' => 'disponible',
            'reserve' => 'réservé',
            'vendu' => 'vendu'
        ];

        return back()->with('success', 'Statut changé en "' . $statusLabels[$article->status] . '".');
    }
}