<?php

require __DIR__.'/auth.php';

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\ArticleController as AdminArticleController;

/*
|--------------------------------------------------------------------------
| PAGE D'ACCUEIL PUBLIQUE
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    $articles = \App\Models\Article::with(['user', 'category', 'images'])
                    ->where('status', 'disponible')
                    ->latest()
                    ->take(8)
                    ->get();
    return view('home', compact('articles'));
})->name('home');

/*
|--------------------------------------------------------------------------
| ROUTES AUTHENTIFIÉES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // DASHBOARD — redirige selon le rôle
    Route::get('/dashboard', function () {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('articles.index');
    })->name('dashboard');

    // ─── ARTICLES ───────────────────────────────────────────────
    Route::get('/articles',                [ArticleController::class, 'index'])   ->name('articles.index');
    Route::get('/articles/create',         [ArticleController::class, 'create'])  ->name('articles.create');
    Route::post('/articles',               [ArticleController::class, 'store'])   ->name('articles.store');
    Route::get('/articles/{article}',      [ArticleController::class, 'show'])    ->name('articles.show');
    Route::get('/articles/{article}/edit', [ArticleController::class, 'edit'])    ->name('articles.edit');
    Route::put('/articles/{article}',      [ArticleController::class, 'update'])  ->name('articles.update');
    Route::delete('/articles/{article}',   [ArticleController::class, 'destroy']) ->name('articles.destroy');
    Route::patch('/articles/{article}/sold', [ArticleController::class, 'markAsSold'])->name('articles.sold');

    // ─── MESSAGES ───────────────────────────────────────────────
    Route::get('/messages',                      [MessageController::class, 'inbox'])->name('messages.index');
    Route::post('/messages',                     [MessageController::class, 'store'])->name('messages.store');
    Route::get('/messages/{userId}/{articleId}', [MessageController::class, 'conversation'])->name('messages.conversation');
    Route::patch('/messages/{message}/read',     [MessageController::class, 'markAsRead'])->name('messages.read');

    // ─── FAVORIS ────────────────────────────────────────────────
    Route::get('/favorites',            [FavoriteController::class, 'index'])  ->name('favorites.index');
    Route::post('/favorites/{article}', [FavoriteController::class, 'toggle']) ->name('favorites.toggle');

    // ─── SIGNALEMENTS ───────────────────────────────────────────
    Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');

    // ⭐ ─── AVIS / REVIEWS ─────────────────────────────────────
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');

    // ─── PROFIL ─────────────────────────────────────────────────
    Route::get('/profile',    [ProfileController::class, 'index'])  ->name('profile.index');
    Route::put('/profile',    [ProfileController::class, 'update']) ->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ─── NOTIFICATIONS ──────────────────────────────────────────
    Route::get('/notifications', function () {
        $notifications = auth()->user()->notifications()->latest()->paginate(20);
        return view('notifications.index', compact('notifications'));
    })->name('notifications.index');

    Route::patch('/notifications/{id}/read', function ($id) {
        auth()->user()->notifications()->findOrFail($id)->markAsRead();
        return back();
    })->name('notifications.read');

    Route::patch('/notifications/read-all', function () {
        auth()->user()->unreadNotifications->markAsRead();
        return back()->with('success', 'Toutes les notifications marquées comme lues.');
    })->name('notifications.read-all');

    // ─── LOGOUT CONFIRMATION ────────────────────────────────────
    Route::get('/logout-confirm', function () {
        return view('auth.logout-confirm');
    })->name('logout.confirm');

});

/*
|--------------------------------------------------------------------------
| ROUTES ADMIN - VERSION CORRIGÉE ET NETTOYÉE
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // =============================================
    // DASHBOARD
    // =============================================
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard', [AdminController::class, 'dashboard']);

    // Messages admin
Route::get('/messages', [App\Http\Controllers\Admin\AdminController::class, 'messages'])->name('messages.index');

// Catégories admin
Route::resource('categories', App\Http\Controllers\Admin\CategoryController::class);

    // =============================================
    // API JSON (pour les appels AJAX)
    // =============================================
    Route::prefix('api')->name('api.')->group(function () {
        Route::get('/users', [AdminController::class, 'users'])->name('users.list');
        Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('users.delete');
        
        Route::get('/articles', [AdminController::class, 'articles'])->name('articles.list');
        Route::delete('/articles/{id}', [AdminController::class, 'deleteArticle'])->name('articles.delete');
        
        Route::get('/reports', [AdminController::class, 'reports'])->name('reports.list');
        Route::patch('/reports/{id}/treat', [AdminController::class, 'treatReport'])->name('reports.treat');
        Route::patch('/reports/{id}/reject', [AdminController::class, 'rejectReport'])->name('reports.reject');
        
        Route::get('/notifications', [AdminController::class, 'notifications'])->name('notifications');
        Route::get('/messages/recent', [AdminController::class, 'recentMessages'])->name('messages.recent');
    });

    // =============================================
    // GESTION DES UTILISATEURS (VUES BLADE)
    // =============================================
    Route::resource('users', AdminUserController::class);
    Route::patch('users/{user}/toggle-active', [AdminUserController::class, 'toggleActive'])->name('users.toggle-active');

    // =============================================
    // GESTION DES ARTICLES (VUES BLADE)
    // =============================================
    Route::resource('articles', AdminArticleController::class);
    Route::patch('articles/{article}/toggle-status', [AdminArticleController::class, 'toggleStatus'])->name('articles.toggle-status');
    Route::patch('articles/{article}/suspend', [AdminArticleController::class, 'suspend'])->name('articles.suspend');

    // =============================================
    // GESTION DES SIGNALEMENTS (VUES BLADE)
    // =============================================
    Route::get('/reports', function () {
        $reports = \App\Models\Report::with(['reporter', 'article'])->latest()->paginate(20);
        return view('admin.reports.index', compact('reports'));
    })->name('reports.index');
    
    Route::patch('/reports/{report}/treat', [AdminController::class, 'treatReport'])->name('reports.treat');
    Route::patch('/reports/{report}/reject', [AdminController::class, 'rejectReport'])->name('reports.reject');

});