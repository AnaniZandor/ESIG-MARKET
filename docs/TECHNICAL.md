# 📘 Documentation technique - ESIG-MARKET

## Architecture

### Pattern MVC utilisé
Requête → Route → Controller → Model → Vue Blade → Réponse

text

### Structure des dossiers clés
app/
├── Http/
│ ├── Controllers/
│ │ ├── ArticleController.php # CRUD articles
│ │ ├── FavoriteController.php # Gestion favoris
│ │ ├── ReportController.php # Signalements
│ │ ├── ReviewController.php # Avis/notations
│ │ └── Admin/
│ │ ├── AdminController.php # Dashboard
│ │ ├── ArticleController.php # Modération
│ │ └── UserController.php # Gestion users
│ └── Middleware/
│ └── AdminMiddleware.php # Vérification rôle admin
├── Models/
│ ├── User.php
│ ├── Article.php
│ ├── Review.php
│ ├── Report.php
│ └── Favorite.php
└── Policies/
└── ArticlePolicy.php # Autorisations

text

## Middlewares

### AdminMiddleware.php

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Accès refusé - Admin uniquement');
        }
        return $next($request);
    }
}
Enregistrement dans Kernel.php
php
protected $routeMiddleware = [
    'auth' => \App\Http\Middleware\Authenticate::class,
    'admin' => \App\Http\Middleware\AdminMiddleware::class,
];
Routes principales (web.php)
Routes publiques
php
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/articles/{article}', [ArticleController::class, 'show'])->name('articles.show');
Routes authentifiées
php
Route::middleware(['auth'])->group(function () {
    Route::resource('/articles', ArticleController::class)->except(['index', 'show']);
    Route::post('/favorites/{article}', [FavoriteController::class, 'toggle']);
    Route::get('/favorites', [FavoriteController::class, 'index']);
    Route::post('/reviews', [ReviewController::class, 'store']);
    Route::post('/reports', [ReportController::class, 'store']);
});
Routes admin
php
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [Admin\AdminController::class, 'dashboard'])->name('dashboard');
    Route::resource('/users', Admin\UserController::class);
    Route::resource('/articles', Admin\ArticleController::class);
});
Modèles de données
User Model
php
protected $fillable = ['name', 'email', 'password', 'role', 'avatar', 'phone'];

// Relations
public function articles() { return $this->hasMany(Article::class); }
public function favorites() { return $this->belongsToMany(Article::class, 'favorites'); }
public function reviewsReceived() { return $this->hasMany(Review::class, 'reviewed_id'); }

// Méthodes
public function isAdmin() { return $this->role === 'admin'; }
Article Model
php
protected $fillable = ['title', 'description', 'price', 'image', 'user_id', 'status', 'category'];

// Relations
public function user() { return $this->belongsTo(User::class); }
public function reviews() { return $this->hasMany(Review::class); }

// Scopes
public function scopeAvailable($query) { return $query->where('status', 'available'); }
Review Model
php
protected $fillable = ['rating', 'comment', 'reviewer_id', 'reviewed_id', 'article_id'];
protected $casts = ['rating' => 'integer'];
Validation des formulaires
Exemple - ArticleController@store
php
$validated = $request->validate([
    'title' => 'required|min:3|max:255',
    'description' => 'required|min:10',
    'price' => 'required|numeric|min:0',
    'category' => 'required|in:electronics,books,clothing,other',
    'image' => 'nullable|image|max:2048'
]);
Politiques d'autorisation
ArticlePolicy
php
public function update(User $user, Article $article)
{
    return $user->id === $article->user_id || $user->isAdmin();
}

public function delete(User $user, Article $article)
{
    return $user->id === $article->user_id || $user->isAdmin();
}
Base de données (Migrations)
Articles table
php
Schema::create('articles', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->text('description');
    $table->decimal('price', 10, 0);
    $table->string('image')->nullable();
    $table->string('category');
    $table->enum('status', ['available', 'pending', 'sold'])->default('available');
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->timestamps();
});
Reviews table
php
Schema::create('reviews', function (Blueprint $table) {
    $table->id();
    $table->integer('rating');
    $table->text('comment');
    $table->foreignId('reviewer_id')->constrained('users');
    $table->foreignId('reviewed_id')->constrained('users');
    $table->foreignId('article_id')->nullable()->constrained();
    $table->timestamps();
});
Sécurité
Protection CSRF
Tous les formulaires incluent @csrf

Prévention XSS
Blade échappe automatiquement le HTML avec {{ }}

Upload d'images
php
if ($request->hasFile('image')) {
    $path = $request->file('image')->store('articles', 'public');
}
Commandes utiles
bash
# Migration et seeder
php artisan migrate:fresh --seed

# Optimisations production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Debug
php artisan tinker
php artisan route:list
Dépendances principales
Package	Utilisation
Laravel 10	Framework
MySQL	Base de données
Bootstrap/Tailwind	CSS Framework
Laravel UI	Authentification
Documentation technique - Version 1.0