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
     * Dashboard admin
     */
    public function dashboard()
    {
        // 📊 statistiques globales du système
        $users = User::count();
        $articles = Article::count();
        $messages = Message::count();
        $categories = Category::count();

        // 📦 on envoie les données à la vue admin
        return view('admin.dashboard', compact(
            'users',
            'articles',
            'messages',
            'categories'
        ));
    }

    /**
 * Liste des utilisateurs
 */
public function users()
{
    $users = User::latest()->get();

    return response()->json($users);
}

/**
 * Liste des articles
 */
public function articles()
{
    $articles = Article::with('user', 'category')
        ->latest()
        ->get();

    return response()->json($articles);
}

/**
 * Supprimer un utilisateur
 */
public function deleteUser($id)
{
    User::findOrFail($id)->delete();

    return response()->json([
        'message' => 'Utilisateur supprimé'
    ]);
}

/**
 * Supprimer un article
 */
public function deleteArticle($id)
{
    Article::findOrFail($id)->delete();

    return response()->json([
        'message' => 'Article supprimé'
    ]);
}

/**
 * Notifications système (messages, actions users)
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
 * Monitoring messages récents
 */
public function recentMessages()
{
    $messages = Message::with(['sender', 'receiver', 'article'])
        ->latest()
        ->limit(50)
        ->get();

    return response()->json($messages);
}
    //
}
