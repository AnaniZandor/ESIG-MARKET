<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'slug',
        'description',
        'price',
        'condition',  // ✅ ajouté
        'location',
        'status',
        'views',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    // Un article appartient à un utilisateur (vendeur)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Un article appartient à une catégorie
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Un article peut avoir plusieurs images
    public function images()
    {
        return $this->hasMany(ArticleImage::class);
    }

    // Un article peut recevoir plusieurs avis
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Un article peut être ajouté aux favoris (N→N)
    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }

    // Signalements sur cet article
    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    public function isAvailable(): bool
    {
        return $this->status === 'disponible';
    }

    public function isVendu(): bool
    {
        return $this->status === 'vendu';
    }

    /*
    |--------------------------------------------------------------------------
    | BOOT — Génération automatique du slug
    |--------------------------------------------------------------------------
    */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($article) {
            if (empty($article->slug)) {
                $article->slug = Str::slug($article->title) . '-' . time();
            }
        });
    }
}