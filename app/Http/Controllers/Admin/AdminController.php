<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Article;
use App\Models\Message;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Dashboard admin - Vue principale
     */
    public function dashboard()
    {
        // Statistiques globales
        $totalUsers = User::count();
        $totalArticles = Article::count();
        $totalMessages = Message::count();
        $totalCategories = Category::count();
        
        // Articles par statut
        $articlesDisponibles = Article::where('status', 'disponible')->count();
        $articlesVendus = Article::where('status', 'vendu')->count();
        $articlesReserves = Article::where('status', 'reserve')->count();
        
        // Vues totales
        $totalViews = Article::sum('views');
        
        // Utilisateurs récents
        $recentUsers = User::latest()->take(5)->get();
        
        // Articles récents
        $recentArticles = Article::with(['user', 'category'])
            ->latest()
            ->take(5)
            ->get();
        
        // Messages récents
        $recentMessages = Message::with(['sender', 'receiver', 'article'])
            ->latest()
            ->take(5)
            ->get();
        
        // Statistiques par catégorie
        $articlesByCategory = Category::withCount('articles')
            ->orderBy('articles_count', 'desc')
            ->take(5)
            ->get();
        
        // Données pour graphique (inscriptions par mois)
        $usersByMonth = User::select(
                DB::raw('COUNT(*) as count'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year')
            )
            ->whereYear('created_at', date('Y'))
            ->groupBy('year', 'month')
            ->orderBy('month')
            ->get();
        
        // Articles par mois
        $articlesByMonth = Article::select(
                DB::raw('COUNT(*) as count'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year')
            )
            ->whereYear('created_at', date('Y'))
            ->groupBy('year', 'month')
            ->orderBy('month')
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalArticles',
            'totalMessages',
            'totalCategories',
            'articlesDisponibles',
            'articlesVendus',
            'articlesReserves',
            'totalViews',
            'recentUsers',
            'recentArticles',
            'recentMessages',
            'articlesByCategory',
            'usersByMonth',
            'articlesByMonth'
        ));
    }

    /**
     * Liste des utilisateurs (JSON pour API)
     */
    public function users()
    {
        $users = User::latest()->get();
        return response()->json($users);
    }

    /**
     * Liste des articles (JSON pour API)
     */
    public function articles()
    {
        $articles = Article::with('user', 'category')
            ->latest()
            ->get();
        return response()->json($articles);
    }

    /**
     * Supprimer un utilisateur (JSON pour API)
     */
    public function deleteUser($id)
    {
        User::findOrFail($id)->delete();
        return response()->json(['message' => 'Utilisateur supprimé']);
    }

    /**
     * Supprimer un article (JSON pour API)
     */
    public function deleteArticle($id)
    {
        Article::findOrFail($id)->delete();
        return response()->json(['message' => 'Article supprimé']);
    }

    /**
     * Notifications système
     */
    public function notifications()
    {
        $notifications = DB::table('notifications')
            ->latest()
            ->limit(50)
            ->get();
        return response()->json($notifications);
    }

    /**
     * Messages récents (JSON pour API)
     */
    public function recentMessages()
    {
        $messages = Message::with(['sender', 'receiver', 'article'])
            ->latest()
            ->limit(50)
            ->get();
        return response()->json($messages);
    }
}