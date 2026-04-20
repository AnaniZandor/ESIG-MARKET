<?php

require __DIR__.'/auth.php';

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Admin\AdminController;

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

    // DASHBOARD — redirige selon le rôle ✅
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
    Route::get('/articles/{id}',           [ArticleController::class, 'show'])    ->name('articles.show');
    Route::get('/articles/{article}/edit', [ArticleController::class, 'edit'])    ->name('articles.edit');
    Route::put('/articles/{article}',      [ArticleController::class, 'update'])  ->name('articles.update');
    Route::delete('/articles/{article}',   [ArticleController::class, 'destroy']) ->name('articles.destroy');
    Route::patch('/articles/{article}/sold', [ArticleController::class, 'markAsSold'])
         ->name('articles.sold');

    // ─── MESSAGES ───────────────────────────────────────────────
    Route::get('/messages',                      [MessageController::class, 'inbox'])
         ->name('messages.index');
    Route::post('/messages',                     [MessageController::class, 'store'])
         ->name('messages.store');
    Route::get('/messages/{userId}/{articleId}', [MessageController::class, 'conversation'])
         ->name('messages.conversation');
    Route::patch('/messages/{message}/read',     [MessageController::class, 'markAsRead'])
         ->name('messages.read');

    // ─── FAVORIS ────────────────────────────────────────────────
    Route::get('/favorites',            [FavoriteController::class, 'index'])  ->name('favorites.index');
    Route::post('/favorites/{article}', [FavoriteController::class, 'toggle']) ->name('favorites.toggle');

    // ─── SIGNALEMENTS ───────────────────────────────────────────
    Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');

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
| ROUTES ADMIN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Utilisateurs
    Route::get('/users',         [AdminController::class, 'users'])     ->name('users.index');
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('users.destroy');

    // Articles
    Route::get('/articles',               [AdminController::class, 'articles'])       ->name('articles.index');
    Route::delete('/articles/{id}',       [AdminController::class, 'deleteArticle'])  ->name('articles.destroy');
    Route::patch('/articles/{id}/suspend',[AdminController::class, 'suspendArticle']) ->name('articles.suspend');

    // Signalements
    Route::get('/reports',               [AdminController::class, 'reports'])     ->name('reports.index');
    Route::patch('/reports/{id}/treat',  [AdminController::class, 'treatReport']) ->name('reports.treat');
    Route::patch('/reports/{id}/reject', [AdminController::class, 'rejectReport'])->name('reports.reject');

    // Monitoring
    Route::get('/notifications',   [AdminController::class, 'notifications']) ->name('notifications');
    Route::get('/messages/recent', [AdminController::class, 'recentMessages'])->name('messages.recent');

});